<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\{Carpeta, Contrato, Empleado, TransporteConsumo, Requisito};

class CarpetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $type
     * @param  int  $id
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function create($type, $id, Carpeta $carpeta = null)
    {
      $class = Carpeta::getModelClass($type);
      $model = $class::findOrFail($id);
      $varName = Carpeta::getRouteVarNameByType($type);
      $requisitos = ($class == 'App\Empleado' || $class == 'App\Contrato' || $class == 'App\Transporte') ? $model->requisitosFaltantes(true) : [];
      $requisitoSelected = Requisito::where([['id', request()->requisito], ['type', $type]])->first();

      return view('admin.carpeta.create', compact('type', 'model', 'carpeta', 'varName', 'requisitos', 'requisitoSelected'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @param  int  $id
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type, $id, Carpeta $carpeta = null)
    {
      $class = Carpeta::getModelClass($type);
      $model = $class::findOrFail($id);

      $this->validate($request, [
        'nombre' => 'required_without:requisito|string|max:50',
        'visibilidad' => 'nullable|boolean',
      ]);

      $newCarpeta = new Carpeta($request->only('nombre'));
      $newCarpeta->empresa_id = Auth::user()->empresa->id;
      $newCarpeta->carpeta_id = optional($carpeta)->id;
      $newCarpeta->visibilidad = $request->has('visibilidad') && $request->visibilidad == '1';

      // Varificar si se esta cargando una carpeta que sea "requisito"
      if($request->requisito){
        $requisito = Requisito::where([['id', $request->requisito], ['type', $type]])->firstOrFail();
        $newCarpeta->nombre = $requisito->nombre;
        $newCarpeta->requisito_id = $requisito->id;
      }

      if($model->carpetas()->save($newCarpeta)){
        return redirect()->route('admin.carpeta.show', ['carpeta' => $newCarpeta->id])->with([
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
     * Display the specified resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function show(Carpeta $carpeta)
    {
      $carpeta->load([
        'subcarpetas',
        'documentos',
      ]);

      return view('admin.carpeta.show', compact('carpeta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function edit(Carpeta $carpeta)
    {
      $requisitos = ($carpeta->isType('App\Empleado') || $carpeta->isType('App\Contrato') || $carpeta->isType('App\Transporte')) ? $carpeta->carpetable->requisitosFaltantes() : [];

      return view('admin.carpeta.edit', compact('carpeta', 'requisitos'));
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
        'visibilidad' => 'nullable|boolean',
      ]);

      $carpeta->nombre = $request->nombre;
      $carpeta->visibilidad = $request->has('visibilidad') && $request->visibilidad == '1';

      if($carpeta->save()){
        return redirect()->route('admin.carpeta.show', ['carpeta' => $carpeta->id])->with([
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
