<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PlantillaDocumento as Documento;
use PDF;

class PlantillaDocumentoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\PlantillaDocumento  $documento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
      $this->authorize('view', $documento);

      $documento->load('plantilla.secciones');

      return view('plantilla-documento.show', compact('documento'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PlantillaDocumento  $documento
     * @return \Illuminate\Http\Response
     */
    public function pdf(Documento $documento)
    {
      $this->authorize('view', $documento);

      $documento->load('plantilla.secciones');
      $nombre = $documento->nombre ?? ($documento->plantilla->nombre ?? 'documento');

      PDF::setOptions(['dpi' => 150]);
      $pdf = PDF::loadView('plantilla-documento.pdf', compact('documento', 'nombre'));

      return $pdf->download($nombre.'.pdf');
    }
}
