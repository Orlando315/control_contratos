<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PreguntaOpcion as Opcion;
use App\EncuestaPregunta as Pregunta;

class PreguntasOpcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Pregunta $pregunta)
    {
      if($pregunta->opciones()->count() > 3){
        return redirect('preguntas/' . $pregunta->id)->with([
          'flash_message' => 'No se pueden agregar más opciones a esta pregunta.',
          'flash_class' => 'alert-success'
          ]);
      }

      return view('encuestas.preguntas.opciones.create', ['pregunta' => $pregunta]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Pregunta $pregunta)
    {
      if($pregunta->opciones()->count() > 3){
        return redirect('preguntas/' . $pregunta->id)->with([
          'flash_message' => 'No se pueden agregar más opciones a esta pregunta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $this->validate($request, [
        'opcion' => 'required|string',
      ]);

      $opcion = new Opcion([
        'opcion' => $request->opcion
      ]);

      if($pregunta->opciones()->save($opcion)){
        return redirect('preguntas/' . $pregunta->id)->with([
          'flash_message' => 'Opcion agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('opciones/create/' . $pregunta->id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PreguntaOpcion  $preguntaOpcion
     * @return \Illuminate\Http\Response
     */
    public function show(Opcion $opcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PreguntaOpcion  $preguntaOpcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Opcion $opcion)
    {
      return view('encuestas.preguntas.opciones.edit', ['opcion' => $opcion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PreguntaOpcion  $preguntaOpcion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Opcion $opcion)
    {
      $this->validate($request, [
        'opcion' => 'required|string',
      ]);

      $opcion->opcion = $request->opcion;

      if($opcion->save()){
        return redirect('preguntas/' . $opcion->pregunta_id)->with([
          'flash_message' => 'Opción modificada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('opciones/create/' . $opcion->pregunta_id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PreguntaOpcion  $preguntaOpcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opcion $opcion)
    {
      if($opcion->delete()){
        return redirect('preguntas/' . $opcion->pregunta_id)->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Opción eliminada exitosamente.'
        ]);
      }else{
        return redirect('preguntas/' . $opcion->pregunta_id)->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
