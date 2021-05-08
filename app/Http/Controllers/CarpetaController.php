<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Carpeta;

class CarpetaController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function show(Carpeta $carpeta)
    {
      $this->authorize('view', $carpeta);

      $carpeta->load([
        'subcarpetas' => function ($query){
          $query->visible();
        },
        'documentos' => function ($query){
          $query->visible();
        },
      ]);

      return view('carpeta.show', compact('carpeta'));
    }
}
