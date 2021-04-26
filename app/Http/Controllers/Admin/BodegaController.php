<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Bodega;

class BodegaController extends Controller
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
      $this->authorize('create', Bodega::class);

      return view('admin.bodega.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Bodega::class);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $bodega = new Bodega($request->only('nombre'));

      if(Auth::user()->empresa->bodegas()->save($bodega)){
        if($request->ajax()){
          return response()->json(['response' =>  true, 'bodega' => $bodega]);
        }

        return redirect()->route('admin.bodega.show', ['bodega' => $bodega->id])->with([
          'flash_message' => 'Bodega agregada exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
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
     * @param  \App\Bodega  $bodega
     * @return \Illuminate\Http\Response
     */
    public function show(Bodega $bodega)
    {
      $this->authorize('view', $bodega);
      
      $bodega->load([
        'inventariosV2.unidad',
        'ubicaciones.inventariosV2',
      ]);

      return view('admin.bodega.show', compact('bodega'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bodega  $bodega
     * @return \Illuminate\Http\Response
     */
    public function edit(Bodega $bodega)
    {
      $this->authorize('update', $bodega);

      return view('admin.bodega.edit', compact('bodega'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bodega  $bodega
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bodega $bodega)
    {
      $this->authorize('update', $bodega);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $bodega->nombre = $request->nombre;

      if($bodega->save()){
        return redirect()->route('admin.bodega.show', ['bodega' => $bodega->id])->with([
          'flash_message' => 'Bodega modificada exitosamente.',
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
     * @param  \App\Bodega  $bodega
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bodega $bodega)
    {
      $this->authorize('delete', $bodega);

      if($bodega->delete()){
        return redirect()->route('admin.inventario.v2.index')->with([
          'flash_message' => 'Bodega eliminada exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      return redirect()->back()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
      ]);
    }

    /**
     * Obtener las Ubicaciones de la Bodega proporcionada
     * 
     * @param  \App\Bodega  $bodega
     * @return \Illuminate\Http\Response
     */
    public function ubicaciones(Bodega $bodega)
    {
      $this->authorize('view', $bodega);

      return response()->json($bodega->ubicaciones);
    }
}
