<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{ProveedorProducto, Proveedor, InventarioV2};

class ProveedorProductoController extends Controller
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
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function create(Proveedor $proveedor)
    {
      $this->authorize('edit', $proveedor);

      $inventariosIds = $proveedor->productos->pluck('inventario_id')->toArray();
      $inventarios = InventarioV2::whereNotIn('id', $inventariosIds)->get();

      return view('admin.proveedor.producto.create', compact('proveedor', 'inventarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Proveedor $proveedor)
    {
      $this->authorize('edit', $proveedor);
      $this->validate($request, [
        'inventario' => 'required',
        'nombre' => 'required|string|max:100',
        'costo' => 'required|numeric|min:0|max:999999999',
      ]);

      $producto = new ProveedorProducto($request->only('nombre', 'costo'));
      $producto->empresa_id = $proveedor->empresa_id;
      $producto->inventario_id = $request->inventario;

      if($proveedor->productos()->save($producto)){
        return redirect()->route('admin.proveedor.show', ['proveedor' => $proveedor->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Producto agregado exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProveedorProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(ProveedorProducto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProveedorProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(ProveedorProducto $producto)
    {
      $this->authorize('edit', $producto->proveedor);

      $producto->load('proveedor.productos');
      $productosIds = $producto->proveedor->productos->except($producto->id)->pluck('inventario_id')->toArray();
      $inventarios = InventarioV2::whereNotIn('id', $productosIds)->get();

      return view('admin.proveedor.producto.edit', compact('producto', 'inventarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProveedorProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProveedorProducto $producto)
    {
      $this->authorize('edit', $producto->proveedor);
      $this->validate($request, [
        'inventario' => 'required',
        'nombre' => 'required|string|max:100',
        'costo' => 'nullable|numeric|min:0|max:999999999',
      ]);

      $producto->fill($request->only('nombre', 'costo'));
      $producto->inventario_id = $request->inventario;

      if($producto->save()){
        return redirect()->route('admin.proveedor.show', ['proveedor' => $producto->proveedor_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Producto modificado exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProveedorProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProveedorProducto $producto)
    {
      $this->authorize('edit', $producto->proveedor);

      if($producto->delete()){
        return redirect()->back()->with([
          'flash_message' => 'Producto eliminado exitosamente.',
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
