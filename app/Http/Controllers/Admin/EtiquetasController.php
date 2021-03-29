<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
      $this->authorize('viewAny', Etiqueta::class);

      $etiquetas = Auth::user()->empresa->etiquetas()->withCount(['facturas', 'gastos', 'inventariosV2'])->get();

      return view('admin.etiquetas.index', compact('etiquetas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', Etiqueta::class);

      return view('admin.etiquetas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Etiqueta::class);

      $this->validate($request, [
        'etiqueta' => 'required|max:50',
      ]);

      $etiqueta = new Etiqueta([
                    'etiqueta' => $request->etiqueta
                  ]);

      if(Auth::user()->empresa->etiquetas()->save($etiqueta)){
        if($request->ajax()){
          return response()->json(['response' =>  true, 'etiqueta' => $etiqueta]);
        }

        return redirect()->route('admin.etiquetas.show', ['etiqueta' => $etiqueta->id])->with([
          'flash_message' => 'Etiqueta agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
      }

      return redirect()->route('admin.etiquetas.create')->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function show(Etiqueta $etiqueta)
    {
      $this->authorize('view', $etiqueta);

      $etiqueta->load(['facturas.contrato', 'gastos.contrato', 'inventariosV2.unidad']);

      return view('admin.etiquetas.show', compact('etiqueta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function edit(Etiqueta $etiqueta)
    {
      $this->authorize('update', $etiqueta);

      return view('admin.etiquetas.edit', compact('etiqueta'));
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
      $this->authorize('update', $etiqueta);
      $this->validate($request, [
        'etiqueta' => 'required|max:50',
      ]);

      $etiqueta->etiqueta = $request->etiqueta;

      if($etiqueta->save()){
        return redirect()->route('admin.etiquetas.show', ['etiqueta' => $etiqueta->id])->with([
          'flash_message' => 'Etiqueta modificada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Etiqueta  $etiqueta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Etiqueta $etiqueta)
    {
      $this->authorize('delete', $etiqueta);

      if($etiqueta->facturas()->count() > 0 || $etiqueta->gastos()->count() > 0){
        return redirect()->back()->with([
          'flash_message' => 'No se puede eliminar. Esta etiqueta tiene elementos agregados.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if($etiqueta->delete()){
        return redirect()->route('admin.etiquetas.index')->with([
          'flash_message' => 'Etiqueta eliminada exitosamente.',
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
