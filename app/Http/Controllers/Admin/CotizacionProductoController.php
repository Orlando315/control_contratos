<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CotizacionProducto;

class CotizacionProductoController extends Controller
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
     * @param  \App\CotizacionProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(CotizacionProducto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CotizacionProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(CotizacionProducto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CotizacionProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CotizacionProducto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CotizacionProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(CotizacionProducto $producto)
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
