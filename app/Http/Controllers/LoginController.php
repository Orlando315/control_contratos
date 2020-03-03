<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Empleado;
use App\Usuario;
use App\Contrato;
use App\Inventario;
use App\EmpleadosContrato;
use App\Documento;

class LoginController extends Controller
{
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

	 public function auth(Request $request)
	 {
	 		/*----------- LOGIN MANUAL , MODIFICABLE ----------*/
    	$this->validate($request, [
    		'usuario' =>'required',
    		'password' => 'required',
    	]);

      if(Auth::attempt($request->only(['usuario', 'password']))){
      	return redirect()->intended('dashboard');
      }else{
      	return redirect()->route('login.view')->withErrors('¡Combinación de usuario y clave incorrecta!');
      }
	 }

	 public function logout()
	 {
	 		Auth::logout();

	 		return redirect()->route('login.view');
	 }
    
}
