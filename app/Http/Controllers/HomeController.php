<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Usuario;
use App\Contrato;
use App\Inventario;
use App\EmpleadosContrato;
use App\Documento;

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

      return view('dashboard', [
        'inventarios' => $inventarios,
        'usuarios' => $usuarios,
        'contratos' => $contratos,
        'contratosPorVencer' => $contratosPorVencer,
        'documentosDeContratosPorVencer' => $documentosDeContratosPorVencer,
        'empleadosContratosPorVencer' => $empleadosContratosPorVencer,
        'documentosDeEmpleadosPorVencer' => $documentosDeEmpleadosPorVencer,
      ]);
    }    
}
