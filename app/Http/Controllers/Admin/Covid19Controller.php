<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Covid19Respuesta, Covid19Pregunta};

class Covid19Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $actualYear = request()->year ?? date('Y');
      $allYears = Covid19Respuesta::allYears()->get()->pluck('year')->toArray();
      $monthlyGrouped = Covid19Respuesta::monthlyGroupedByYear($actualYear);

      return view('admin.covid19.index', compact(
        'actualYear',
        'allYears',
        'monthlyGrouped'
      ));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Covid19Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function show(Covid19Respuesta $respuesta)
    {
      $this->authorize('view', Covid19Respuesta::class);

      $preguntas = Covid19Pregunta::all();

      return view('admin.covid19.show', compact('respuesta', 'preguntas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Covid19Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function edit(Covid19Respuesta $respuesta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Covid19Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Covid19Respuesta $respuesta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Covid19Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Covid19Respuesta $respuesta)
    {
      $this->authorize('delete', $respuesta);

      if($respuesta->delete()){
        return redirect()->route('admin.empresa.covid19.index')->with([
          'flash_message' => 'Encuesta eliminada exitosamente.',
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
