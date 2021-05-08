<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Documento;

class DocumentosController extends Controller
{
    /**
     * Descargar el Documento especificado
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function download(Documento $documento)
    {
      $this->authorize('download', $documento);

      if(!Storage::exists($documento->path)){
        abort(404);
      }

      return Storage::download($documento->path, $documento->nombre.'.'.$documento->getExtension());
    }
}
