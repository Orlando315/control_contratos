<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Carpeta;

class ArchivoCarpetaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function create(Carpeta $carpeta = null)
    {
      $this->authorize('createArchivoCarpeta', Carpeta::class);

      $users = is_null($carpeta) ? Auth::user()->empresa->users : $carpeta->archivoUsers;

      return view('admin.archivo.carpeta.create', compact('carpeta', 'users'));
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
      $this->authorize('createArchivoCarpeta', Carpeta::class);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'publica' => 'nullable|boolean',
      ]);

      $newCarpeta = new Carpeta(['nombre' => $request->nombre]);
      $newCarpeta->empresa_id = Auth::user()->empresa->id;
      $newCarpeta->carpeta_id = optional($carpeta)->id;
      $newCarpeta->public = $carpeta ? $carpeta->public : ($request->has('publica') && $request->publica == '1');

      if($newCarpeta->save()){
        $newCarpeta->location = Auth::user()->empresa->directory.'/Archivos/'.$newCarpeta->id;
        $newCarpeta->save();

        Storage::makeDirectory($newCarpeta->location);

        $newCarpeta->archivoUsers()->attach($request->usuarios ?? []);

        return redirect()->route('archivo.carpeta.show', ['carpeta' => $newCarpeta->id])->with([
          'flash_message' => 'Carpeta agregada exitosamente.',
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function edit(Carpeta $carpeta)
    {
      $this->authorize('updateArchivoCarpeta', $carpeta);

      $carpeta->load([
        'archivoUsers',
        'main.archivoUsers',
      ]);
      $users = $carpeta->isMain() ? Auth::user()->empresa->users : $carpeta->main->archivoUsers;

      return view('admin.archivo.carpeta.edit', compact('carpeta', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carpeta $carpeta)
    {
      $this->authorize('updateArchivoCarpeta', $carpeta);
      $this->validate($request, [
        'nombre' => 'required|max:50',
        'publica' => 'nullable|boolean',
      ]);

      $carpeta->nombre = $request->nombre;
      $carpeta->public = $carpeta->isSubfolder() ? $carpeta->main->public : ($request->has('publica') && $request->publica == '1');

      if($carpeta->save()){
        if($carpeta->changed('public')){
          $carpeta->changePublicStatus();
        }

        $carpeta->archivoUsers()->sync($request->usuarios ?? []);

        return redirect()->route('archivo.carpeta.show', ['carpeta' => $carpeta->id])->with([
          'flash_message' => 'Carpeta modificada exitosamente.',
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
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carpeta $carpeta)
    {
      $this->authorize('deleteArchivoCarpeta', $carpeta);

      $redirect = $carpeta->backArchivoUrl;

      if($carpeta->delete()){
        return redirect($redirect)->with([
          'flash_message' => 'Carpeta eliminada exitosamente.',
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
