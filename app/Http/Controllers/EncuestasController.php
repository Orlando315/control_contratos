<?php

namespace App\Http\Controllers;

use App\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncuestasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $encuestas = Encuesta::all();

      return view('encuestas.index', ['encuestas' => $encuestas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('encuestas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'titulo' => 'required|string'
      ]);

      $encuesta = new Encuesta($request->all());

      if(Auth::user()->encuestas()->save($encuesta)){
        return redirect('preguntas/create/' . $encuesta->id)->with([
          'flash_message' => 'Encuesta agregada exitosamente.',
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
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function show(Encuesta $encuesta)
    {
      $respuestas = $encuesta->respuestas()->with('usuario')->groupBy('user_id')->get();

      return view('encuestas.show', ['encuesta' => $encuesta, 'respuestas' => $respuestas]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function edit(Encuesta $encuesta)
    {
      return view('encuestas.edit', ['encuesta' => $encuesta]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Encuesta $encuesta)
    {
      $this->validate($request, [
        'titulo' => 'required|string'
      ]);

      $encuesta->titulo = $request->titulo;

      if($encuesta->save()){
        return redirect('encuestas/' . $encuesta->id)->with([
          'flash_message' => 'Encuesta modificada exitosamente.',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Encuesta $encuesta)
    {
      if($encuesta->delete()){
        return redirect('encuestas')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Encuesta eliminada exitosamente.'
        ]);
      }else{
        return redirect('encuestas/' . $encuesta->id)->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Encuesta  $encuesta
     * @return \Illuminate\Http\Response
     */
    public function showPublic(Encuesta $encuesta)
    {
      return view('encuestas.encuesta', ['encuesta' => $encuesta]);
    }
}
