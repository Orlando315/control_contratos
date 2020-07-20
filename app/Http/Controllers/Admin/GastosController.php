<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{Gasto, Contrato, Etiqueta};

class GastosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $gastos = Gasto::all();
      return view('admin.gastos.index', compact('gastos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $contratos = Contrato::all();
      $etiquetas = Etiqueta::all();

      return view('admin.gastos.create', compact('contratos', 'etiquetas'));
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
        'contrato_id' => 'required',
        'etiqueta_id' => 'required',
        'nombre' => 'required|max:200',
        'valor' => 'required|numeric|min:0',
      ]);

      $gasto = new Gasto($request->only('contrato_id', 'etiqueta_id', 'nombre', 'valor'));

      if(Auth::user()->empresa->gastos()->save($gasto)){
        return redirect()->route('admin.gastos.show', ['gasto' => $gasto->id])->with([
          'flash_message' => 'Gasto agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->route('admin.gastos.create')->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function show(gasto $gasto)
    {
      return view('admin.gastos.show', compact('gasto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function edit(gasto $gasto)
    {
      $contratos = Contrato::all();
      $etiquetas = Etiqueta::all();

      return view('admin.gastos.edit', compact('contratos', 'etiquetas', 'gasto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, gasto $gasto)
    {
      $this->validate($request, [
        'contrato_id' => 'required',
        'etiqueta_id' => 'required',
        'nombre' => 'required|max:200',
        'valor' => 'required|numeric|min:0',
      ]);

      $gasto->fill($request->all());

      if($gasto->save()){
        return redirect()->route('admin.gastos.show', ['gasto' => $gasto->id])->with([
          'flash_message' => 'Gasto modificado exitosamente.',
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
     * @param  \App\gasto  $gasto
     * @return \Illuminate\Http\Response
     */
    public function destroy(gasto $gasto)
    {
      if($gasto->delete()){
        return redirect()->route('admin.gastos.index')->with([
          'flash_message' => 'Gasto eliminado exitosamente.',
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
