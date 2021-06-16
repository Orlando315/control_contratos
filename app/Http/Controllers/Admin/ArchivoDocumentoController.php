<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Documento;
use App\Carpeta;

class ArchivoDocumentoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function create(Carpeta $carpeta = null)
    {
      $this->authorize('createArchivoDocumento', Documento::class);

      $users = is_null($carpeta) ? Auth::user()->empresa->users : $carpeta->archivoUsers;

      return view('admin.archivo.documento.create', compact('carpeta', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Carpeta $carpeta = null)
    {
      $this->authorize('createArchivoDocumento', Documento::class);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'observacion' => 'nullable|string|max:100',
        'documento' => 'required|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'vencimiento' => 'nullable|date_format:d-m-Y',
        'publico' => 'nullable|boolean',
      ]);

      $documento = new Documento($request->only('nombre', 'observacion', 'vencimiento'));
      $documento->mime = $request->documento->getMimeType();
      $documento->empresa_id = Auth::user()->empresa->id;
      $documento->carpeta_id = optional($carpeta)->id;
      $documento->public = $carpeta ? $carpeta->public : ($request->has('publico') && $request->publico == '1');

      if($documento->save()){
        $directory = $carpeta ? $carpeta->location : Auth::user()->empresa->directory.'/Archivos';

        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        $documento->path = $request->file('documento')->store($directory);
        $documento->save();

        $documento->archivoUsers()->attach($request->usuarios ?? []);

        return redirect($documento->backArchivoUrl)->with([
          'flash_message' => 'Adjunto agregado exitosamente.',
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
     * Show the form for creating a new resource.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
      $this->authorize('viewArchivoDocumento', $documento);

      $documento->load(['archivoUsers']);

      return view('admin.archivo.documento.show', compact('documento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {
      $this->authorize('updateArchivoDocumento', $documento);

      $documento->load([
        'archivoUsers',
        'carpeta.archivoUsers',
      ]);
      $users = is_null($documento->carpeta_id) ? Auth::user()->empresa->users : $documento->carpeta->archivoUsers;

      return view('admin.archivo.documento.edit', compact('documento', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Documento $documento)
    {
      $this->authorize('updateArchivoDocumento', $documento);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'observacion' => 'nullable|string|max:100',
        'vencimiento' => 'nullable|date_format:d-m-Y',
        'publico' => 'nullable|boolean',
      ]);

      $documento->fill($request->only('nombre', 'observacion', 'vencimiento'));
      $documento->public = $documento->carpeta_id ? $documento->carpeta->public : ($request->has('publico') && $request->publico == '1');

      if($documento->save()){
        $documento->archivoUsers()->sync($request->usuarios ?? []);

        return redirect($documento->backArchivoUrl)->with([
          'flash_message' => 'Documento modificado exitosamente.',
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
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
      $this->authorize('deleteArchivoDocumento', $documento);

      if($documento->delete()){
        return redirect($documento->backArchivoUrl)->with([
          'flash_message' => 'Documento eliminado exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      return redirect()->back()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
      ]);
    }
}
