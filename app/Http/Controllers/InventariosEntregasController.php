<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{InventarioEntrega, Inventario, Contrato};

class InventariosEntregasController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventarioEntrega $entrega)
    {
      if(Auth::user()->id == $entrega->entregado){
        $entrega->recibido = true;

        if($entrega->save()){
          $response = ['response' => true];
        }else{
          $response = ['response' => false, 'message' => 'Ha ocurrido un error.'];
        }
      }else{
        $response = ['response' => false, 'message' => 'No estas autorizado a confirmar esta entrega.'];
      }


      return $response;
    }

    /**
     * Descargar el ajunto de la Entrega.
     *
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function download(InventarioEntrega $entrega)
    {
      return Storage::exists($entrega->adjunto) ? Storage::download($entrega->adjunto) : abort(404);
    }
}
