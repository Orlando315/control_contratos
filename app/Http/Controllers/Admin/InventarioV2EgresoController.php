<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{InventarioV2Egreso, InventarioV2, Cliente, Contrato, Faena, CentroCosto};

class InventarioV2EgresoController extends Controller
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
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function create(InventarioV2 $inventario)
    {
      $this->authorize('create', InventarioV2Egreso::class);

      $usuarios = Auth::user()->empresa->users;
      $clientes = Cliente::all();
      $contratos = Contrato::all();
      $faenas = Faena::all();
      $centrosCostos = CentroCosto::all();

      return view('admin.inventarioV2.egreso.create', compact('inventario', 'usuarios', 'clientes', 'contratos', 'faenas', 'centrosCostos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, InventarioV2 $inventario)
    {
      $this->authorize('create', InventarioV2Egreso::class);
      $this->validate($request, [
        'tipo' => 'required',
        'usuario' => 'nullable',
        'cliente' => 'nullable',
        'cantidad' => 'required|numeric|min:1|max:9999',
        'costo' => 'nullable|numeric|min:0|max:999999999',
        'contrato' => 'nullable',
        'faena' => 'nullable',
        'centro_costo' => 'nullable',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $egreso = new InventarioV2Egreso($request->only('cantidad', 'costo', 'descripcion'));
      $egreso->empresa_id = Auth::user()->empresa->id;
      $egreso->user_id = $request->usuario;
      $egreso->cliente_id = $request->cliente;
      $egreso->contrato_id = $request->contrato;
      $egreso->faena_id = $request->faena;
      $egreso->centro_costo_id = $request->centro_costo;

      if($inventario->egresos()->save($egreso)){
        if($request->hasFile('foto')){
          if(!Storage::exists($egreso->directory)){
            Storage::makeDirectory($egreso->directory);
          }

          $egreso->foto = $request->file('foto')->store($egreso->directory);
          $egreso->save();
        }

        $inventario->subStock($egreso->cantidad);

        return redirect()->route('admin.inventario.egreso.show', ['egreso' => $egreso->id])->with([
          'flash_message' => 'Egreso de Stock agregado exitosamente.',
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
     * @param  \App\InventarioV2Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function show(InventarioV2Egreso $egreso)
    {
      $this->authorize('view', $egreso);

      $egreso->load([
        'inventario',
        'user',
        'cliente',
        'contrato',
        'faena',
        'centroCosto',
      ]);

      return view('admin.inventarioV2.egreso.show', compact('egreso'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InventarioV2Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function edit(InventarioV2Egreso $egreso)
    {
      $this->authorize('update', $egreso);

      $usuarios = Auth::user()->empresa->users;
      $clientes = Cliente::all();
      $contratos = Contrato::all();
      $faenas = Faena::all();
      $centrosCostos = CentroCosto::all();

      return view('admin.inventarioV2.egreso.edit', compact('egreso', 'usuarios', 'clientes', 'contratos', 'faenas', 'centrosCostos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioV2Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventarioV2Egreso $egreso)
    {
      $this->authorize('update', $egreso);
      $this->validate($request, [
        'tipo' => 'required',
        'usuario' => 'nullable',
        'cliente' => 'nullable',
        'cantidad' => 'required|numeric|min:1|max:9999',
        'costo' => 'nullable|numeric|min:0|max:999999999',
        'contrato' => 'nullable',
        'faena' => 'nullable',
        'centro_costo' => 'nullable',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $cantidad = $egreso->cantidad - $request->cantidad;
      $egreso->fill($request->only('cantidad', 'costo', 'descripcion'));
      $egreso->empresa_id = Auth::user()->empresa->id;
      $egreso->user_id = $request->usuario;
      $egreso->cliente_id = $request->cliente;
      $egreso->contrato_id = $request->contrato;
      $egreso->faena_id = $request->faena;
      $egreso->centro_costo_id = $request->centro_costo;

      if($egreso->save()){
        if($request->hasFile('foto')){
          if(!Storage::exists($egreso->directory)){
            Storage::makeDirectory($egreso->directory);
          }

          // Si ya tine una foto, eliminarlo
          if($egreso->foto){
            Storage::delete($egreso->foto);
          }

          $egreso->foto = $request->file('foto')->store($egreso->directory);
          $egreso->save();
        }

        $egreso->inventario->updateStock($cantidad, false);

        return redirect()->route('admin.inventario.egreso.show', ['egreso' => $egreso->id])->with([
          'flash_message' => 'Egreso de Stock modificado exitosamente.',
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
     * @param  \App\InventarioV2Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventarioV2Egreso $egreso)
    {
      $this->authorize('delete', $egreso);

      if($egreso->delete()){
        if($egreso->foto){
          Storage::deleteDirectory($egreso->directory);
        }

        $egreso->inventario->addStock($egreso->cantidad);

        return redirect()->route('admin.inventario.v2.show', ['inventario' => $egreso->inventario_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Egreso de Stock eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
