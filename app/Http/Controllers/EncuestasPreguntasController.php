<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EncuestaPregunta as Pregunta;
use App\Encuesta;

class EncuestasPreguntasController extends Controller
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
    public function create(Encuesta $encuesta)
    {
      return view('encuestas.preguntas.create', ['encuesta' => $encuesta]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Encuesta $encuesta)
    {
      $this->validate($request, [
        'pregunta' => 'required',
        'opciones.1' => 'required',
        'opciones.2' => 'required',
        'opciones.3' => 'nullable',
        'opciones.4' => 'nullable',
      ]);

      $pregunta = new Pregunta();
      $pregunta->pregunta = $request->pregunta;
      $pregunta->encuesta_id = $encuesta->id;

      if($pregunta = Auth::user()->preguntas()->save($pregunta)){

        $pregunta->storeOpciones($request->opciones);

        return redirect('encuestas/' . $encuesta->id)->with([
          'flash_message' => 'Pregunta agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('encuestas/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EncuestaPregunta  $encuestaPregunta
     * @return \Illuminate\Http\Response
     */
    public function show(Pregunta $pregunta)
    {
      return view('encuestas.preguntas.show', ['pregunta' => $pregunta]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EncuestaPregunta  $encuestaPregunta
     * @return \Illuminate\Http\Response
     */
    public function edit(Pregunta $pregunta)
    {
      return view('encuestas.preguntas.edit', ['pregunta' => $pregunta]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EncuestaPregunta  $encuestaPregunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pregunta $pregunta)
    {
      $this->validate($request, [
        'pregunta' => 'required',
      ]);

      $pregunta->pregunta = $request->pregunta;

      if($pregunta->save()){
        return redirect('preguntas/' . $pregunta->id)->with([
          'flash_message' => 'Pregunta modificada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('preguntas/')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EncuestaPregunta  $encuestaPregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pregunta $pregunta)
    {
      if($pregunta->delete()){
        return redirect('encuestas/' . $pregunta->encuesta_id)->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Pregunta eliminada exitosamente.'
        ]);
      }else{
        return redirect('encuestas/' . $pregunta->encuesta_id)->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
