<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Contrato, Inventario, EmpleadosContrato, Documento};
use App\Scopes\EmpresaScope;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
      $inventarios = Inventario::count();
      $contratos = Contrato::count();
      $contratosPorVencer = Contrato::groupedAboutToExpire();
      $empleadosContratosPorVencer = EmpleadosContrato::groupedAboutToExpire();
      $documentosContratosPorVencer = Documento::groupedAboutToExpireByType('contratos');
      $documentosEmpleadosPorVencer = Documento::groupedAboutToExpireByType('empleados');
      $documentosTransportesPorVencer = Documento::groupedAboutToExpireByType('transportes');
      $otrosEmpleados = Auth::user()->isEmpleado()
        ? Auth::user()->empleado->otrosEmpleados()->get()
        : [];

      return view('dashboard', [
        'inventarios' => $inventarios,
        'contratos' => $contratos,
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
     */
    public function roleToggle()
    {
      $roleActivo = Auth::user()->role();
      $roleInactivo = Auth::user()->inactiveRole();

      if($roleActivo && $roleInactivo){
        Auth::user()->roles(null)->updateExistingPivot($roleActivo->id, ['active' => false]);
        Auth::user()->roles(null)->updateExistingPivot($roleInactivo->id, ['active' => true]);
        Auth::user()->flushCache();
      }

      return redirect()->route('dashboard');
    }
}
