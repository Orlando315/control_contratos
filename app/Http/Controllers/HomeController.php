<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Usuario, Contrato, Inventario, EmpleadosContrato, Documento};
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
      $inventarios = Inventario::all();
      $usuarios  = Usuario::adminsYSupervisores();
      $contratos = Contrato::all();
      $contratosPorVencer = Contrato::porVencer();
      $documentosDeContratosPorVencer = Documento::deContratosPorVencer();
      $empleadosContratosPorVencer = EmpleadosContrato::porVencer();
      $documentosDeEmpleadosPorVencer = Documento::deEmpleadosPorVencer();
      $otrosEmpleados = Auth::user()->isEmpleado() ? Auth::user()->empleado->otrosEmpleados()->get()
                                              : [];

      return view('dashboard', [
        'inventarios' => $inventarios,
        'usuarios' => $usuarios,
        'contratos' => $contratos,
        'contratosPorVencer' => $contratosPorVencer,
        'documentosDeContratosPorVencer' => $documentosDeContratosPorVencer,
        'empleadosContratosPorVencer' => $empleadosContratosPorVencer,
        'documentosDeEmpleadosPorVencer' => $documentosDeEmpleadosPorVencer,
        'otrosEmpleados' => $otrosEmpleados,
      ]);
    }

    /**
     * Cronjob para generar las asistencias de los Empleados
     *
     * @param  \App\Empleado  $empleado
     */
    public function cronjobAsistencias(){
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
}
