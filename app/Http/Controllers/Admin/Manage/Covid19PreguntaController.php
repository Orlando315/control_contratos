<?php

namespace App\Http\Controllers\Admin\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use App\Covid19Pregunta;

class Covid19PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $preguntas = Covid19Pregunta::all();

      return view('admin.manage.covid19.index', compact('preguntas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.manage.covid19.create');
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
        'pregunta' => 'required|string|max:250',
      ]);

      $pregunta = new Covid19Pregunta([
        'pregunta' => $request->pregunta,
        'order' => (Covid19Pregunta::count() + 1),
      ]);

      if($pregunta->save()){
        return redirect()->route('admin.manage.covid19.show', ['pregunta' => $pregunta->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Pregunta agregada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Covid19Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function show(Covid19Pregunta $pregunta)
    {
      return view('admin.manage.covid19.show', compact('pregunta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Covid19Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function edit(Covid19Pregunta $pregunta)
    {
      return view('admin.manage.covid19.edit', compact('pregunta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Covid19Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Covid19Pregunta $pregunta)
    {
      $this->validate($request, [
        'pregunta' => 'required|string|max:250',
      ]);

      $pregunta->fill([
        'pregunta' => $request->pregunta,
      ]);

      if($pregunta->save()){
        return redirect()->route('admin.manage.covid19.show', ['pregunta' => $pregunta->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Pregunta modificada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Covid19Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Covid19Pregunta $pregunta)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      if($pregunta->delete()){
        return redirect()->route('admin.manage.covid19.index')->with([
          'flash_message' => 'Pregunta eliminada exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      return redirect()->back()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
      ]);
    }
}
