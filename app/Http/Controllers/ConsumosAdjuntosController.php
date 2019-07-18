<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\ConsumoAdjunto;
use App\TransporteConsumo;

class ConsumosAdjuntosController extends Controller
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
    public function create(TransporteConsumo $consumo)
    {
      if($consumo->adjuntos()->count() >= 10){
        return redirect('transportes/consumos/' . $consumo->id)->with([
          'flash_message' => 'No se pueden agregar mas adjuntos a este consumo.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      return view('transportes.consumos.adjuntos.create', compact('consumo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TransporteConsumo $consumo)
    {
      if($consumo->adjuntos()->count() >= 10){
        return redirect('transportes/consumos/' . $consumo->id)->with([
          'flash_message' => 'No se pueden agregar mas adjuntos a este consumo.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $this->validate($request, [
        'adjunto' => 'required|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $adjunto = new ConsumoAdjunto;
      $adjunto->nombre = pathinfo($request->adjunto->getClientOriginalName(), PATHINFO_FILENAME);
      $adjunto->mime   = $request->adjunto->getMimeType();

      if($adjunto = $consumo->adjuntos()->save($adjunto)){
        $directory = 'Empresa' . Auth::user()->empresa_id . '/Transportes/' . $consumo->transporte_id;

        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        $adjunto->path = $request->file('adjunto')->store($directory);
        $adjunto->save();

        return redirect('transportes/consumos/' . $consumo->id)->with([
          'flash_message' => 'Adjunto agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('transportes/consumos/'. $consumo->id.'/adjuntos/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ConsumoAdjunto  $adjunto
     * @return \Illuminate\Http\Response
     */
    public function show(ConsumoAdjunto $adjunto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ConsumoAdjunto  $adjunto
     * @return \Illuminate\Http\Response
     */
    public function edit(ConsumoAdjunto $adjunto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ConsumoAdjunto  $adjunto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConsumoAdjunto $adjunto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ConsumoAdjunto  $adjunto
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConsumoAdjunto $adjunto)
    {
      if($adjunto->delete()){
        Storage::delete($adjunto->path);

        $response = ['response' => true, 'id' => $adjunto->id];
      }else{
        $response = ['response' => false];
      }

      return $response;
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  \App\ConsumoAdjunto  $adjunto
     * @return \Illuminate\Http\Response
     */
    public function download(ConsumoAdjunto $adjunto)
    {
      return Storage::download($adjunto->path);
    }
}
