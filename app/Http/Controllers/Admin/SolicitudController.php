<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
      $this->authorize('viewAny', Solicitud::class);

      $solicitudes = Solicitud::all();

      return view('admin.solicitud.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Solicitud  $solicitud
     * @return \Illuminate\Http\Response
     */
    public function show(Solicitud $solicitud)
    {
      $this->authorize('view', $solicitud);

      return view('admin.solicitud.show', compact('solicitud'));
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

      return view('admin.solicitud.edit', compact('solicitud'));
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
        'observacion' => 'nullable|string|max:200',
        'estatus' => 'required|in:aprobar,rechazar',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $solicitud->fill($request->only('observacion'));
      $solicitud->status = $request->estatus == 'aprobar';

      if($solicitud->save()){
        if($request->hasFile('adjunto')){
          $directory = $solicitud->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $solicitud->adjunto = $request->file('adjunto')->store($directory);
          $solicitud->save();
        }

        return redirect()->route('admin.solicitud.show', ['solicitud' => $solicitud->id])->with([
          'flash_message' => 'Solicitud respondida exitosamente.',
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
      $this->authorize('delete', $solicitud);

      if($solicitud->delete()){
        if(Storage::exists($solicitud->adjunto)){
          Storage::delete($solicitud->adjunto); 
        }

        return redirect()->route('admin.solicitud.index')->with([
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
}
