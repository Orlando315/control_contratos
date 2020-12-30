<?php

namespace App\Http\Controllers\Admin\Development;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth};
use App\{User, Role};

class FixsController extends Controller
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
    public function index(Request $request, $fix)
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
    function fixAttachUsersEmpresas()
    {
      $usuarios = User::all();
    }
}
