<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Etiqueta;

class EtiquetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $etiquetas = Auth::user()->empresa->etiquetas;

      return view('etiquetas.index', compact('etiquetas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('etiquetas.create');
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
        'etiqueta' => 'required|max:50',
      ]);

      $etiqueta = new Etiqueta([
                    'etiqueta' => $request->etiqueta
                  ]);

      if(Auth::user()->empresa->etiquetas()->save($etiqueta)){
        return redirect()->route('etiquetas.show', ['id' => $etiqueta->id])->with([
          'flash_message' => 'Etiqueta agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('etiquetas.create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function show(Etiqueta $etiqueta)
    {
      return view('etiquetas.show', compact('etiqueta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function edit(Etiqueta $etiqueta)
    {
      return view('etiquetas.edit', compact('etiqueta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Etiqueta $etiqueta)
    {
      $this->validate($request, [
        'etiqueta' => 'required|max:50',
      ]);

      $etiqueta->etiqueta = $request->etiqueta;

      if($etiqueta->save()){
        return redirect()->route('etiquetas.show', ['id' => $etiqueta->id])->with([
          'flash_message' => 'Etiqueta modificada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect()->route('etiquetas.edit', ['id' => $etiqueta->id])->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Etiqueta $etiqueta)
    {
      if($etiqueta->facturas->count() == 0 && $etiqueta->gastos->count() == 0){
        if($etiqueta->delete()){
          return redirect()->route('etiquetas.index')->with([
            'flash_message' => 'Etiqueta eliminada exitosamente.',
            'flash_class' => 'alert-success'
            ]);
        }else{
          return redirect()->route('etiquetas.show', ['id' => $etiqueta->id])->with([
            'flash_message' => 'Ha ocurrido un error.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
            ]);
        }
      }else{
        return redirect()->route('etiquetas.show', ['id' => $etiqueta->id])->with([
          'flash_message' => 'No se puede eliminar. Esta etiqueta tiene elementos agregados.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }
}
