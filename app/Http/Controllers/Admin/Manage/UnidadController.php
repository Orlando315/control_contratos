<?php

namespace App\Http\Controllers\Admin\Manage;

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
      $unidades = Unidad::withoutGlobalScopes()
      ->global()
      ->withCount([
        'inventariosV2' => function ($query){
          return $query->withoutGlobalScopes();
        }
      ])
      ->get();

      return view('admin.manage.unidad.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.manage.unidad.create');
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
        'nombre' => 'required|max:50',
      ]);

      $unidad = new Unidad($request->only('nombre'));

      if($unidad->save()){
        return redirect()->route('admin.manage.unidad.show', ['unidad' => $unidad->id])->with([
          'flash_message' => 'Unidad agregada exitosamente.',
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
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(Unidad $unidad)
    {
      $unidad->loadCount([
        'inventariosV2' => function ($query){
          return $query->withoutGlobalScopes();
        }
      ]);

      return view('admin.manage.unidad.show', compact('unidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function edit(Unidad $unidad)
    {
      return view('admin.manage.unidad.edit', compact('unidad'));
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
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $unidad->nombre = $request->nombre;

      if($unidad->save()){
        return redirect()->route('admin.manage.unidad.show', ['unidad' => $unidad->id])->with([
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
      if($unidad->inventariosV2()->withoutGlobalScopes()->count() > 0){
        return redirect()->back()->with([
          'flash_message' => 'Esta Unidad tiene Inventarios V2 agregados.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      if($unidad->delete()){
        return redirect()->route('admin.manage.unidad.index')->with([
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

    /**
     * Cambiar status de la Unidad proporcionada
     * 
     * @param  \App\Unidad  $unidad
     * @return \Illuminate\Http\Response
     */
    public function status(Unidad $unidad)
    {
      Unidad::withoutGlobalScopes()
      ->global()
      ->update([
        'status' => false,
      ]);
      $unidad->status = true;

      if($unidad->save()){
        return redirect()->route('admin.manage.unidad.show', ['unidad' => $unidad->id])->with([
          'flash_message' => 'Unidad establecida como predeterminada exitosamente.',
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
