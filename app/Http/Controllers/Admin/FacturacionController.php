<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{Facturacion, Cotizacion};

class FacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $facturaciones = Facturacion::all();

      return view('admin.cotizacion.facturacion.index', compact('facturaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function create(Cotizacion $cotizacion = null)
    {
      $cotizaciones = cotizacion::doesntHave('facturacion')->get();

      return view('admin.cotizacion.facturacion.create', ['cotizaciones' => $cotizaciones, 'selectedCotizacion' => $cotizacion]);
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
        'cotizacion' => 'required',
        'rut' => 'required|regex:/^(\d{4,9})$/',
        'digito_validador' => 'required|regex:/^([\dkK])$/',
        'firma' => 'required',
      ]);

      if(Auth::user()->empresa->configuracion->isIntegrationIncomplete('sii')){
        return redirect()->back()->withErrors('!Error! Integración incompleta.');
      }

      $cotizacion = cotizacion::findOrFail($request->cotizacion);
      [$response, $data] = $cotizacion->facturar($request->rut, $request->digito_validador);

      if(!$response){
        return redirect()->back()->withInput()->with([
          'flash_message' => $data,
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      $facturacion = new Facturacion([
        'cotizacion_id' => $cotizacion->id,
        'sii_factura_id' => $data,
        'rut' => $request->rut,
        'dv' => $request->digito_validador,
      ]);

      if(Auth::user()->empresa->facturaciones()->save($facturacion)){
        return redirect()->route('admin.cotizacion.facturacion.show', ['facturacion' => $facturacion->id])->with([
            'flash_message' => 'Facturación agregada exitosamente.',
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
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function show(Facturacion $facturacion)
    {
      return view('admin.cotizacion.facturacion.show', compact('facturacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Facturacion $facturacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facturacion $facturacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facturacion $facturacion)
    {
        //
    }
}
