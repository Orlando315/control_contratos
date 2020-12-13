<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\OrdenCompraProducto;

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrdenCompraProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenCompraProducto $producto)
    {
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
