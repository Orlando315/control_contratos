<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\Solicitud;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $solicitudes = Solicitud::all();

      return view('solicitud.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', Solicitud::class);

      return view('solicitud.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Solicitud::class);
      $this->validate($request, [
        'tipo' => 'required',
        'otro' => 'required_if:tipo,otro|string|max:50',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $solicitud = new Solicitud($request->only('tipo', 'otro', 'descripcion'));
      $solicitud->empresa_id = Auth::user()->empresa->id;

      if(Auth::user()->empleado->solicitudes()->save($solicitud)){
        return redirect()->route('solicitud.show', ['solicitud' => $solicitud->id])->with([
          'flash_message' => 'Solicitud agregada exitosamente.',
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
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function show(Solicitud $solicitud)
    {
      $this->authorize('create', $solicitud);

      return view('solicitud.show', compact('solicitud'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function edit(Solicitud $solicitud)
    {
      $this->authorize('update', $solicitud);

      return view('solicitud.edit', compact('solicitud'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Solicitud $solicitud)
    {
      $this->authorize('update', $solicitud);
      $this->validate($request, [
        'tipo' => 'required',
        'otro' => 'required_if:tipo,otro|string|max:50',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $solicitud->fill($request->only('tipo', 'otro', 'descripcion'));

      if($solicitud->save()){
        return redirect()->route('solicitud.show', ['solicitud' => $solicitud->id])->with([
          'flash_message' => 'Solicitud modificado exitosamente.',
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
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solicitud $solicitud)
    {
      if($solicitud->delete()){
        Storage::delete($solicitud->adjunto);

        return redirect()->route('solicitud.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Solicitud eliminada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Descargar el ajunto de la Solicitud.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function download(Solicitud $solicitud)
    {
      return Storage::exists($solicitud->adjunto) ? Storage::download($solicitud->adjunto) : abort(404);
    }
}
