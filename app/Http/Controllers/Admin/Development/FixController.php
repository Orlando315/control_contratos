<?php

namespace App\Http\Controllers\Admin\Development;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth, Storage};
use App\{User, Role, Empresa, PlantillaVariable, Transporte, InventarioV2, Documento, Carpeta};
use App\Integrations\Logger\ActivityLogStatus;

class FixController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('permission:god');
    }

    /**
     * Ejecutar el metodo segun el fix proporcionado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $fix
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      return view('admin.development.fix.index');
    }

    /**
     * Ejecutar el metodo segun el fix proporcionado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $fix
     * @return \Illuminate\Http\Response
     */
    public function route(Request $request, $fix)
    {
      $method = 'fix'.Str::studly(str_replace('.', '_', strtolower($fix)));

      if(method_exists($this, $method)){
        return $this->{$method}();
      }

      abort(404);
    }

    /**
     * Crear los roles de todos los usuarios que no tengan uno.
     *
     * @return \Illuminate\Http\Response
     */
    private function fixCreateUsersPermissions()
    {
      $empresas = User::where('tipo', 1)->whereDoesntHaveRole()->get();
      $roleEmpresa = Role::firstWhere('name', 'empresa');
      foreach ($empresas as $empresa){
        $empresa->attachRole($roleEmpresa);
      }

      $administradores = User::where('tipo', 2)->whereDoesntHaveRole()->get();
      $roleAdministrador = Role::firstWhere('name', 'administrador');

      foreach ($administradores as $administrador){
        $administrador->attachRole($roleAdministrador);
      }

      $supervisores = User::where('tipo', 3)->whereDoesntHaveRole()->get();
      $roleSupervisor = Role::firstWhere('name', 'supervisor');
      foreach ($supervisores as $supervisor){
        $supervisor->attachRole($roleSupervisor);
      }

      $empleados = User::whereIn('tipo', [4, 5])->whereDoesntHaveRole()->get();
      $roleEmpleado = Role::firstWhere('name', 'empleado');
      foreach ($empleados as $empleado){
        $empleado->attachRole($roleEmpleado);
      }

      return response()->json([
        'empresas' => $empresas->count(),
        'administradores' => $administradores->count(),
        'supervisores' => $supervisores->count(),
        'empleados' => $empleados->count(),
      ]);
    }

    /**
     * Crear los roles de todos los usuarios que no tengan uno.
     *
     * @return \Illuminate\Http\Response
     */
    private function fixAttachUsersEmpresas()
    {
      $users = User::whereNotNull('empresa_id')->doesntHave('empresas')->get();
      $groupedByEmpresa = $users->groupBy('empresa_id');
      $asociados = 0;

      // Se agrupan los usuarios por empresa para minimizar la cantidad de querys a ejecutar
      foreach ($groupedByEmpresa as $empresaId => $empresaUsers) {
        $empresa = Empresa::find($empresaId);

        if($empresa){
          $usersId = $empresaUsers->pluck('id');
          $empresa->users()->attach($usersId);

          $asociados +=  count($usersId);
        }
      }

      return response()->json([
        'users' => $users->count(),
        'empresas' => $groupedByEmpresa->count(),
        'usuarios_asociados' => $asociados,
      ]);
    }

    /**
     * Crear los roles de todos los usuarios que no tengan uno.
     *
     * @return \Illuminate\Http\Response
     */
    private function fixCopyRutEmpresas()
    {
      $empresas = Empresa::whereNull('rut')->get();
      $actualizadas = 0;

      foreach ($empresas as $empresa) {
        $user = $empresa->user;

        if($user){
          $empresa->fill($user->only('rut', 'telefono', 'email'));
          $empresa->save();
          $actualizadas++;
        }
      }

      return response()->json([
        'empresas' => $empresas->count(),
        'actualizadas' => $actualizadas
      ]);
    }

    /**
     * Agregar el Role de Empleado a los User que tengan Roles de staff y un registro de Empleado
     * 
     * @return \Illuminate\Http\Response
     */
    private function fixMissingEmpleadoRole()
    {
      $usuarios = User::has('empleado')
      ->whereRoleIs(['empresa', 'administrador', 'supervisor'])
      ->has('allRoles', '=', 1)
      ->get();

      $role = Role::firstWhere('name', 'empleado');
      $actualizados = 0;

      foreach ($usuarios as $usuario) {
        $usuario->roles()->attach($role->id, ['active' => false]);
        $actualizados++;
      }

      return response()->json([
        'usuarios' => $usuarios->count(),
        'actualizados' => $actualizados,
      ]);
    }

    /**
     * Eliminar las variables reservadas del sistema, que esten registradas por las Empresas
     * 
     * @return \Illuminate\Http\Response
     */
    private function fixRemoveStaticVariables()
    {
      $deleted = PlantillaVariable::withoutGlobalScopes()
      ->whereNotNull('empresa_id')
      ->whereIn('variable', PlantillaVariable::getReservedVariables())
      ->delete();

      return response()->json([
        'eliminadas' => $deleted,
      ]);
    }

    /**
     * Migrar informacion de supervisor y feana a la nueva relacion belongsToMany
     * 
     * @return \Illuminate\Http\Response
     */
    public function fixMigrateTransporteData()
    {
      $transportes = Transporte::withoutGlobalScopes()
      ->where(function ($query) {
        return $query->whereNotNull('user_id')
        ->orWhereNotNull('faena_id');
      })
      ->get();

      foreach($transportes as $transporte){
        $transporte->supervisores()->attach($transporte->user_id);
        $transporte->faenas()->attach($transporte->faena_id ?? []);
        $transporte->user_id = null;
        $transporte->faena_id = null;
        $transporte->save();
      }

      return response()->json([
        'transportes' => $transportes->count(),
      ]);
    }

    /**
     * Migrar informacion de Bodega y Ubicacion de InventarioV2 a la nueva relacion belongsToMany
     * 
     * @return \Illuminate\Http\Response
     */
    public function fixInventarioBodegasUbicaciones()
    {
      $inventarios = InventarioV2::withoutGlobalScopes()
      ->where(function ($query) {
        $query->whereNotNull('bodega_id')
        ->orWhereNotNull('ubicacion_id');
      })
      ->get();

      foreach ($inventarios as $inventario) {
        $inventario->bodegas()->attach([$inventario->bodega_id]);

        if($inventario->ubicacion_id){
          $inventario->ubicaciones()->attach([$inventario->ubicacion_id]); 
        }

        $inventario->update([
          'bodega_id' => null,
          'ubicacion_id' => null,
        ]);
      }

      return response()->json([
        'inventarios' => $inventarios->count(),
      ]);
    }

    /**
     * Eliminar todos los registros de InventarioV1 y sus Entregas y archivos
     * 
     * @return \Illuminate\Http\Response
     */
    public function fixRemoveInventarioV1()
    {
      // Desactivar Logs
      $logStatus = app(ActivityLogStatus::class);
      $logStatus->disable();
      $deletedInventarios = false;
      $deletedFolders = false;
      $deletedDocuments = false;
      $deletedDirectories = false;
      $empresasCount = false;

      try {
        // Eliminar Documentos y Carpetas
        $deletedDocuments = Documento::withoutGlobalScopes()->byType('App\Inventario')->delete();
        $deletedFolders = Carpeta::withoutGlobalScopes()->byType('App\Inventario')->delete();

        // Eliminar Inventario y sus entregas. Las Entrega estan relacionadas en la BD con on delete cascade.
        if(class_exists('\App\Inventario')){
          $deletedInventarios = \App\inventario::withoutGlobalScopes()->delete();

          // Eliminar directorio de Inventario y Entregas
          $empresas = Empresa::all();
          $empresasCount = $empresas->count();
          $deletedDirectories = 0;

          foreach($empresas as $empresa){
            $directory = $empresa->directory.'/Inventarios';

            if(Storage::exists($directory)){
              Storage::deleteDirectory($directory);
              $deletedDirectories++;
            }
          }
        }
      } catch (\Exception $e) {
      }

      return response()->json([
        'inventarios' => $deletedInventarios,
        'folders' => $deletedFolders,
        'documents' => $deletedDocuments,
        'directories' => $deletedDirectories,
        'empresas' => $empresasCount,
      ]);
    }
}
