<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EncuestaRespuesta as Respuesta;
use App\Encuesta;
use App\User;

class EncuestasRespuestasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Encuesta $encuesta)
    {
      $this->validate($request, $encuesta->validatePreguntas());

      $respuestas = $encuesta->createRespuestas($request->pregunta);

      if(Auth::user()->respuestas()->createMany($respuestas)){
        return redirect('dashboard')->with([
          'flash_message' => '¡Gracias por responder la encuesta!',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('encuesta/' . $encuesta->id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Encuesta  $encuesta
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Encuesta $encuesta, User $usuario)
    {
      $respuestas = Respuesta::with(['pregunta:id,pregunta', 'opcion'])->where([
                                ['encuesta_id', $encuesta->id],
                                ['user_id', $usuario->id]
                              ])->get();

      // Si el usuario no ha repondido esa encuesta, mostrar 404
      if(!count($respuestas)){
        abort(404);
      }

      return view('encuestas.respuesta', ['respuestas' => $respuestas, 'encuesta' => $encuesta, 'usuario' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EncuestaRespuesta  $encuestaRespuesta
     * @return \Illuminate\Http\Response
     */
    public function edit(EncuestaRespuesta $encuestaRespuesta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EncuestaRespuesta  $encuestaRespuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EncuestaRespuesta $encuestaRespuesta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Encuesta  $encuesta
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Encuesta $encuesta, User $usuario)
    {
      $respuestasIds = Respuesta::where([
                                  ['encuesta_id', $encuesta->id],
                                  ['user_id', $usuario->id]
                                ])->pluck('id')->toArray();

      $eliminados = Respuesta::destroy($respuestasIds);

      if($eliminados){
        return redirect('encuestas/' . $encuesta->encuesta_id)->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Respuesta eliminada exitosamente.'
        ]);
      }else{
        return redirect('encuestas/' . $encuesta->encuesta_id)->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
