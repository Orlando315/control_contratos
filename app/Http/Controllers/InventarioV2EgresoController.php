<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InventarioV2Egreso;
use PDF;

class InventarioV2EgresoController extends Controller
{
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

      return view('egreso.show', compact('egreso'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InventarioV2Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function pdf(InventarioV2Egreso $egreso)
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

      PDF::setOptions(['dpi' => 150]);
      $pdf = PDF::loadView('egreso.pdf', compact('egreso'));

      return $pdf->download('inventario-egreso.pdf');
    }

    /**
     * Confirmar el Egreso de Inventario por el User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, InventarioV2Egreso $egreso)
    {
      $this->authorize('accept', $egreso);
      
      $egreso->update(['recibido' => date('Y-m-d H:i:s')]);

      return ['response' => true];
    }
}
