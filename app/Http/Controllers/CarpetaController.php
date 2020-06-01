<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\{Carpeta, Contrato, Empleado};

class CarpetaController extends Controller
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
     * @param  string  $type
     * @param  int  $id
     * @param  int  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function create($type, $id, $carpeta = null)
    {
      $model = ($type == 'contrato') ? Contrato::findOrFail($id) : Empleado::findOrFail($id);
      $carpeta = $carpeta ? Carpeta::findOrFail($carpeta) : null;

      return view('carpeta.create', compact('type', 'model', 'carpeta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @param  int  $id
     * @param  int  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type, $id, $carpeta = null)
    {
      $model = ($type == 'contrato') ? Contrato::findOrFail($id) : Empleado::findOrFail($id);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
      ]);

      $newCarpeta = new Carpeta($request->only('nombre'));
      $newCarpeta->empresa_id = Auth::user()->empresa->id;
      $newCarpeta->carpeta_id = $carpeta;

      if($model->carpetas()->save($newCarpeta)){
        return redirect()->route('carpeta.show', ['carpeta' => $newCarpeta->id])->with([
          'flash_message' => 'Carpeta agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect('transportes/consumos/'. $consumo->id.'/adjuntos/create')->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function show(Carpeta $carpeta)
    {
      return view('carpeta.show', compact('carpeta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function edit(Carpeta $carpeta)
    {
      return view('carpeta.edit', compact('carpeta'));
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
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $carpeta->nombre = $request->nombre;

      if($carpeta->save()){
        return redirect()->route('carpeta.show', ['carpeta' => $carpeta->id])->with([
          'flash_message' => 'Carpeta modificada exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      return redirect()->route('carpeta.edit', ['carpeta' => $carpeta->id])->withInput()->with([
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
      $redirect = $carpeta->backUrl;

      if($carpeta->delete()){
        Carpeta::where('carpeta_id', $carpeta->id)->delete();
        return redirect($redirect)->with([
          'flash_message' => 'Carpeta Eliminada exitosamente.',
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
