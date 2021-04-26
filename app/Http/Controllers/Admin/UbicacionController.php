<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Ubicacion, Bodega};

class UbicacionController extends Controller
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
     * @param  \App\Bodega  $bodega
     * @return \Illuminate\Http\Response
     */
    public function create(Bodega $bodega)
    {
      return view('admin.bodega.ubicacion.create', compact('bodega'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Bodega  $bodega
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Bodega $bodega)
    {
      $this->authorize('create', Ubicacion::class);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $ubicacion = new Ubicacion($request->only('nombre'));
      $ubicacion->empresa_id = Auth::user()->empresa->id;

      if($bodega->ubicaciones()->save($ubicacion)){
        return redirect()->route('admin.ubicacion.show', ['ubicacion' => $ubicacion->id])->with([
          'flash_message' => 'Ubicación agregada exitosamente.',
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
     * Display the specified resource.
     *
     * @param  \App\Ubicacion  $ubicacion
     * @return \Illuminate\Http\Response
     */
    public function show(Ubicacion $ubicacion)
    {
      $this->authorize('view', $ubicacion);
      
      $ubicacion->load([
        'bodega',
        'inventariosV2.unidad',
      ]);

      return view('admin.bodega.ubicacion.show', compact('ubicacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ubicacion  $ubicacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Ubicacion $ubicacion)
    {
      $this->authorize('update', $ubicacion);

      return view('admin.bodega.ubicacion.edit', compact('ubicacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ubicacion  $ubicacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
      $this->authorize('update', $ubicacion);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $ubicacion->nombre = $request->nombre;

      if($ubicacion->save()){
        return redirect()->route('admin.ubicacion.show', ['ubicacion' => $ubicacion->id])->with([
          'flash_message' => 'Ubicación modificada exitosamente.',
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
     * @param  \App\Ubicacion  $ubicacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ubicacion $ubicacion)
    {
      $this->authorize('delete', $ubicacion);

      if($ubicacion->delete()){
        return redirect()->route('admin.bodega.show', ['bodega' => $ubicacion->bodega_id])->with([
          'flash_message' => 'Ubicación eliminada exitosamente.',
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
