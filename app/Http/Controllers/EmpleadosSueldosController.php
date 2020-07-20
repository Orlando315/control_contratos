<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\EmpleadosSueldo;

class EmpleadosSueldosController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function show(EmpleadosSueldo $sueldo)
    {
      // Los usuarios Supervisores (3) y Empleados (4), solo pueden ver sus propios sueldos
      if(Auth::user()->tipo >= 3 && Auth::user()->empleado_id != $sueldo->empleado_id){
        abort(404);
      }

      return view('sueldos.show', compact('sueldo'));
    }

    /**
     * Marcar el Sueldo como recibido
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return array
     */
    public function recibido(EmpleadosSueldo $sueldo)
    {
      if(Auth::user()->empleado_id === $sueldo->empleado_id){
        $sueldo->recibido = true;

        if($sueldo->save()){
          $response = ['response' => true];
        }else{
          $response = ['response' => false, 'message' => 'Ha ocurrido un error.'];
        }
      }else{
        $response = ['response' => false, 'message' => 'No estas autorizado a confirmar este sueldo.'];
      }

      return $response;
    }

    /**
     * Descargar el adjunto del Sueldo especificado
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function download(EmpleadosSueldo $sueldo)
    {
      return Storage::download($sueldo->adjunto);
    }
}
