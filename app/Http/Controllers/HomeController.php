<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Contrato, InventarioV2, EmpleadosContrato, Documento, Solicitud};
use App\Scopes\EmpresaScope;

class HomeController extends Controller
{
    /**
     * Pantalla de bienvenida
     * 
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
      $empresas = Auth::user()->empresas;
      $roles = Auth::user()->allRoles;

      return view('welcome', compact('empresas', 'roles'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
      $inventarios = InventarioV2::count();
      $contratos = Contrato::count();
      $contratosPorVencer = Contrato::groupedAboutToExpire();
      $empleadosContratosPorVencer = EmpleadosContrato::groupedAboutToExpire();
      $documentosContratosPorVencer = Documento::groupedAboutToExpireByType('contratos');
      $documentosEmpleadosPorVencer = Documento::groupedAboutToExpireByType('empleados');
      $documentosTransportesPorVencer = Documento::groupedAboutToExpireByType('transportes');
      $otrosEmpleados = Auth::user()->isEmpleado()
        ? Auth::user()->empleado->otrosEmpleados()->get()
        : [];
      $solicitudes = Solicitud::count();

      return view('dashboard', [
        'inventarios' => $inventarios,
        'contratos' => $contratos,
        'solicitudes' => $solicitudes,
        'contratosPorVencer' => $contratosPorVencer,
        'empleadosContratosPorVencer' => $empleadosContratosPorVencer,
        'documentosContratosPorVencer' => $documentosContratosPorVencer,
        'documentosEmpleadosPorVencer' => $documentosEmpleadosPorVencer,
        'documentosTransportesPorVencer' => $documentosTransportesPorVencer,
        'otrosEmpleados' => $otrosEmpleados,
      ]);
    }

    /**
     * Cronjob para generar las asistencias de los Empleados
     *
     * @param  \App\Empleado  $empleado
     */
    public function cronjobAsistencias()
    {
      $empleados = Empleado::withoutGlobalScope(EmpresaScope::class)->get();
      $today = date('Y-m-d');

      foreach($empleados as $empleado){
        if($empleado->isWorkDay()){
          $eventosExists = $empleado->eventsToday()->exists();          
          
          $empleado->eventos()->firstOrCreate([
            'inicio' => $today,
            'tipo' =>  1,
            'jornada' => $empleado->contratos->last()->jornada
          ],[
            'comida' => !$eventosExists,
            'pago' => !$eventosExists
          ]);
        }
      }
    }

    /**
     * Cambiar el Role activo del User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function roleToggle(Request $request)
    {
      $roleActivo = Auth::user()->role();
      $roleToActivate = $request->role;

      if($roleActivo && $roleToActivate){
        Auth::user()->roles(null)->updateExistingPivot($roleActivo->id, ['active' => false]);
        Auth::user()->roles(null)->updateExistingPivot($roleToActivate, ['active' => true]);
        Auth::user()->flushCache();
      }

      return redirect()->route('dashboard');
    }

    /**
     * Mostrar la pagina de terminos y condiciones de la empresa
     * 
     * @return \Illuminate\Http\Response
     */
    public function terminos()
    {
      if(!Auth::user()->empresa->configuracion->hasActiveTerminos()){
        abort(404);
      }

      $terminos = Auth::user()->empresa->configuracion->terminos;

      return view('terminos', compact('terminos'));
    }

    /**
     * Aceptar los terminos y condiciones
     * 
     * @return \Illuminate\Http\Response
     */
    public function terminosAccept()
    {
      $response = false;

      if(Auth::user()->empresa->configuracion->hasActiveTerminos()){
        $response = Auth::user()->empresa->configuracion->acceptTerms();
      }

      return response()->json(['response' => $response]);
    }
}
