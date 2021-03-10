<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{InventarioV2Ingreso, InventarioV2, Proveedor};

class InventarioV2IngresoController extends Controller
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
      $this->authorize('create', InventarioV2Ingreso::class);

      $proveedores = Proveedor::all();

      return view('admin.inventarioV2.ingreso.create', compact('inventario', 'proveedores'));
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
      $this->authorize('create', InventarioV2Ingreso::class);
      $this->validate($request, [
        'proveedor' => 'nullable',
        'cantidad' => 'required|numeric|min:1|max:9999',
        'costo' => 'nullable|numeric|min:0|max:999999999',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $ingreso = new InventarioV2Ingreso($request->only('cantidad', 'costo', 'descripcion'));
      $ingreso->empresa_id = Auth::user()->empresa->id;
      $ingreso->proveedor_id = $request->proveedor;

      if($inventario->ingresos()->save($ingreso)){
        if($request->hasFile('foto')){
          $directory = $ingreso->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $ingreso->foto = $request->file('foto')->store($directory);
          $ingreso->save();
        }

        $inventario->addStock($ingreso->cantidad);
        $ingreso->updateProveedorProducto();

        return redirect()->route('admin.inventario.ingreso.show', ['ingreso' => $ingreso->id])->with([
          'flash_message' => 'Ingreso de Stock agregado exitosamente.',
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
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function show(InventarioV2Ingreso $ingreso)
    {
      $this->authorize('view', $ingreso);

      $ingreso->load('inventario', 'proveedor');

      return view('admin.inventarioV2.ingreso.show', compact('ingreso'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function edit(InventarioV2Ingreso $ingreso)
    {
      $this->authorize('update', $ingreso);

      $proveedores = Proveedor::all();

      return view('admin.inventarioV2.ingreso.edit', compact('ingreso', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventarioV2Ingreso $ingreso)
    {
      $this->authorize('update', $ingreso);
      $this->validate($request, [
        'proveedor' => 'nullable',
        'cantidad' => 'required|numeric|min:1|max:9999',
        'costo' => 'nullable|numeric|min:0|max:999999999',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $cantidad = $ingreso->cantidad - $request->cantidad;
      $ingreso->fill($request->only('cantidad', 'costo', 'descripcion'));
      $ingreso->proveedor_id = $request->proveedor;

      if($ingreso->save()){
        if($request->hasFile('foto')){
          $directory = $ingreso->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          // Si ya tine una foto, eliminarlo
          if($ingreso->foto){
            Storage::delete($ingreso->foto);
          }

          $ingreso->foto = $request->file('foto')->store($directory);
          $ingreso->save();
        }

        $ingreso->inventario->updateStock($cantidad);

        return redirect()->route('admin.inventario.ingreso.show', ['ingreso' => $ingreso->id])->with([
          'flash_message' => 'Ingreso de Stock modificado exitosamente.',
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
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventarioV2Ingreso $ingreso)
    {
      $this->authorize('delete', $ingreso);

      if($ingreso->delete()){
        if($ingreso->foto){
          Storage::deleteDirectory($ingreso->directory);
        }

        $ingreso->inventario->subStock($ingreso->cantidad);

        return redirect()->route('admin.inventario.v2.show', ['inventario' => $ingreso->inventario_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Ingreso de Stock eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
