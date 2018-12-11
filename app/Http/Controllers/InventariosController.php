<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Inventario;

class InventariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $inventarios = Inventario::all();

      return view('inventarios.index', ['inventarios' => $inventarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('inventarios.create');
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
        'tipo' => 'required',
        'nombre' => 'required|string',
        'valor' => 'required|numeric',
        'fecha' => 'required|date_format:d-m-Y',
        'cantidad' => 'required|numeric'
      ]);

      $inventario = new Inventario($request->all());

      if($inventario = Auth::user()->empresa->inventarios()->save($inventario)){
        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Inventario agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('inventarios/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
      return view('inventarios.show', ['inventario' => $inventario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
      return view('inventarios.edit', ['inventario' => $inventario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventario $inventario)
    {
      $this->validate($request, [
        'tipo' => 'required',
        'nombre' => 'required|string',
        'valor' => 'required|numeric',
        'fecha' => 'required|date_format:d-m-Y',
        'cantidad' => 'required|numeric'
      ]);

      $inventario->fill($request->all());

      if($inventario->save()){
        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Inventario modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
      if($inventario->delete()){
        return redirect('inventarios')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Inventario eliminado exitosamente.'
        ]);
      }else{
        return redirect('inventarios')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
