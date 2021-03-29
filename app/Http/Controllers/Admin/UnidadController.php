<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Unidad;

class UnidadController extends Controller
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
      $this->authorize('create', Unidad::class);

      return view('admin.unidad.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Unidad::class);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $unidad = new Unidad($request->only('nombre'));

      if(Auth::user()->empresa->unidades()->save($unidad)){
        if($request->ajax()){
          return response()->json(['response' =>  true, 'unidad' => $unidad]);
        }

        return redirect()->route('admin.unidad.show', ['unidad' => $unidad->id])->with([
          'flash_message' => 'Unidad agregada exitosamente.',
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
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(Unidad $unidad)
    {
      $this->authorize('view', $unidad);
      
      $unidad->load('inventariosV2.unidad');

      return view('admin.unidad.show', compact('unidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Unidad $unidad)
    {
      $this->authorize('update', $unidad);

      return view('admin.unidad.edit', compact('unidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidad $unidad)
    {
      $this->authorize('update', $unidad);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $unidad->nombre = $request->nombre;

      if($unidad->save()){
        return redirect()->route('admin.unidad.show', ['unidad' => $unidad->id])->with([
          'flash_message' => 'Unidad modificada exitosamente.',
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
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidad $unidad)
    {
      $this->authorize('delete', $unidad);

      if($unidad->inventariosV2()->count() > 0){
        return redirect()->back()->with([
          'flash_message' => 'Esta Unidad tiene Inventarios V2 agregados.',
          'flash_class' => 'alert-success'
        ]);
      }

      if($unidad->delete()){
        return redirect()->route('admin.inventario.v2.index')->with([
          'flash_message' => 'Unidad eliminada exitosamente.',
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
