<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{OrdenCompraProducto, InventarioV2};

class OrdenCompraProductoController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrdenCompraProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenCompraProducto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdenCompraProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenCompraProducto $producto)
    {
      $this->authorize('update', $producto->ordenCompra);

      $inventarios = InventarioV2::with('unidad')->get();

      return view('admin.compra.producto.edit', compact('producto', 'inventarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdenCompraProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenCompraProducto $producto)
    {
      $this->authorize('update', $producto->ordenCompra);
      $this->validate($request, [
        'tipo_codigo' => 'nullable|string|max:20',
        'codigo' => 'nullable|string|max:50',
        'nombre' => 'required|max:100',
        'cantidad' =>  'required|integer|min:1|max:99999',
        'precio' => 'required|numeric|min:1|max:99999999',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $producto->fill($request->except('inventario', 'precio', 'iva'));
      $producto->precio = round($request->precio_total / $request->cantidad, 2);
      $producto->impuesto_adicional = $request->iva;
      $producto->inventario_id = $request->inventario;

      if($producto->save()){
        return redirect()->route('admin.compra.show', ['compra' => $producto->orden_compra_id])->with([
          'flash_message' => 'Producto modificado exitosamente.',
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
     * @param  \App\OrdenCompraProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenCompraProducto $producto)
    {
      $this->authorize('update', $producto->ordenCompra);

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
