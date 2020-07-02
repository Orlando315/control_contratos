<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{TransporteConsumo, Transporte, Documento};

class TransportesConsumosController extends Controller
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
    public function create(Transporte $transporte)
    {
      $contratos = $transporte->contratos()->get();

      return view('transportes.consumos.create', ['transporte' => $transporte, 'contratos' => $contratos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transporte $transporte)
    {
      $contrato = \App\Contrato::findOrFail($request->contrato);

      $this->validate($request, [
        'contrato' => 'required',
        'tipo' => 'required|in:1,2,3,4',
        'fecha' => 'nullable|date_format:d-m-Y',
        'cantidad' => 'nullable|numeric',
        'valor' => 'required|numeric',
        'chofer' => 'required',
        'observacion' => 'nullable',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $consumo = new TransporteConsumo($request->all());
      $consumo->contrato_id = $contrato->id;

      if($consumo = $transporte->consumos()->save($consumo)){
        if($request->hasFile('adjunto')){
          $directory = 'Empresa' . Auth::user()->empresa_id . '/Transportes/' . $transporte->id;

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $documento = new Documento([
                        'empresa_id' => $transporte->empresa_id,
                        'nombre' => pathinfo($request->adjunto->getClientOriginalName(), PATHINFO_FILENAME),
                        'mime' => $request->adjunto->getMimeType(),
                        'path' => $request->file('adjunto')->store($directory),
                      ]);

          $consumo->documentos()->save($documento);
        }

        return redirect()->route('consumos.show', ['consumo' => $consumo->id])->with([
          'flash_message' => 'Consumo agregado exitosamente.',
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
     * @param  \App\TransporteConsumo  $transporteConsumo
     * @return \Illuminate\Http\Response
     */
    public function show(TransporteConsumo $consumo)
    {
      return view('transportes.consumos.show', ['consumo' => $consumo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TransporteConsumo  $consumo
     * @return \Illuminate\Http\Response
     */
    public function edit(TransporteConsumo $consumo)
    {
      return view('transportes.consumos.edit', ['consumo' => $consumo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransporteConsumo  $consumo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransporteConsumo $consumo)
    {
      $this->validate($request, [
        'tipo' => 'required|in:1,2,3,4',
        'fecha' => 'nullable|date_format:d-m-Y',
        'cantidad' => 'nullable|numeric',
        'valor' => 'required|numeric',
        'chofer' => 'required',
        'observacion' => 'nullable',
      ]);

      $consumo->fill($request->all());

      if($consumo->save()){
        return redirect()->route('consumos.show',['consumo' => $consumo->id])->with([
          'flash_message' => 'Consumo modificado exitosamente.',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\TransporteConsumo  $consumo
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransporteConsumo $consumo)
    {
      if($consumo->delete()){
        $consumo->carpetas()->delete();
        $consumo->documentos()->delete();

        return redirect()->route('transportes.show', ['consumo' => $consumo->transporte_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Consumo eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
