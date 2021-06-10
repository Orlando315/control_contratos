<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{FacturacionCompra, OrdenCompra};

class OrdenCompraFacturacionController extends Controller
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
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function create(OrdenCompra $compra)
    {
      $this->authorize('update', $compra);

      $facturas = FacturacionCompra::facturasRecibidas();

      return view('admin.compra.facturacion.create', compact('compra', 'facturas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, OrdenCompra $compra)
    {
      $this->authorize('update', $compra);
      $this->validate($request, [
        'factura' => 'required',
      ]);

      $facturacion = new FacturacionCompra([
        'empresa_id' => Auth::user()->empresa->id,
        'codigo' => $request->factura,
        'emisor' => $request->emisor,
        'razon_social' => $request->razon_social,
        'documento' => $request->documento,
        'folio' => $request->folio,
        'fecha' => $request->fecha,
        'monto' => $request->monto,
        'estado' => $request->estado,
      ]);

      if($compra->facturacion()->save($facturacion)){
        return redirect()->route('admin.compra.show', ['compra' => $compra->id])->with([
            'flash_message' => 'Factura asociada exitosamente.',
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
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenCompra $compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenCompra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenCompra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FacturacionCompra  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(FacturacionCompra $facturacion)
    {
      $this->authorize('update', $facturacion->compra);

      if($facturacion->delete()){
        return redirect()->route('admin.compra.show', ['compra' => $facturacion->orden_compra_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Facturación eliminada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Obtener los productos de una factura de la API Facturacion Sii
     * 
     * @param  string  $codigo
     * @return \Illuminate\Http\Response
     */
    public function getProductos($codigo)
    {
      $factura = sii()->consultaFactura($codigo);

      return response()->json(['productos' => $factura['productos'] ?? []]);
    }

    /**
     * Sincronizar informacion de Facturacion
     * 
     * @param  \App\FacturacionCompra $facturacion
     * @return \Illuminate\Http\Response
     */
    public function sync(FacturacionCompra $facturacion)
    {
      $this->authorize('update', $facturacion->compra);

      if(Auth::user()->empresa->configuracion->doesntHaveSiiAccount()){
        return redirect()->back()->withErrors('!Error! Integración incompleta.');
      }

      if($facturacion->syncFacturacion()){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Facturación actualizada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]); 
    }
}
