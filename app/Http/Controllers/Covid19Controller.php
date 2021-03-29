<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Covid19Respuesta, Covid19Pregunta};

class Covid19Controller extends Controller
{
    /**
     * Mostrar encuesta de Covid19
     * 
     * @return \Illuminate\Http\Response
     */
    public function encuesta()
    {
      if(!Auth::user()->empresa->configuracion->hasActiveCovid19Encuesta() || !Auth::user()->haventAnsweredCovid19Today()){
        abort(404);
      }

      $preguntas = Covid19Pregunta::all();

      return view('covid19', compact('preguntas'));
    }

    /**
     * Almacenar respuesta del User a la encuesta Covid-19
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if(!Auth::user()->empresa->configuracion->hasActiveCovid19Encuesta() || !Auth::user()->haventAnsweredCovid19Today()){
        abort(404);
      }

      $this->validate($request, [
        'respuestas.*' => 'required|boolean',
      ]);

      $respuesta = new Covid19Respuesta([
        'empresa_id' =>  Auth::user()->empresa->id,
        'respuestas' => $request->respuestas,
      ]);

      if(Auth::user()->covid19Respuestas()->save($respuesta)){
        return redirect()->route('dashboard')->with([
          'flash_message' => 'Encuesta respondida exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
      ]);
    }
}
