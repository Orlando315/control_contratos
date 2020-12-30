<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Plantilla, PlantillaVariable, PlantillaSeccion};

class PlantillaController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', Plantilla::class);
      $variables = PlantillaVariable::toFormEditor();

      return view('admin.plantilla.create', compact('variables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Plantilla::class);
      $this->validate($request, [
        'nombre' => 'required',
        'secciones' => 'required|min:1',
        'secciones.*.nombre' => 'nullable|string|max:50',
      ]);

      $plantilla = new Plantilla($request->only('nombre'));

      $newSecciones = [];
      foreach($request->secciones as $seccion){
        $newSecciones[] = new PlantillaSeccion($seccion);
      }

      if(Auth::user()->empresa->plantillas()->save($plantilla)){
        $plantilla->secciones()->saveMany($newSecciones);

        return redirect()->route('admin.plantilla.show', ['plantilla' => $plantilla->id])->with([
          'flash_message' => 'Plantilla registrada exitosamente.',
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
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function show(Plantilla $plantilla)
    {
      $this->authorize('view', $plantilla);

      $plantilla->load('secciones');

      return view('admin.plantilla.show', compact('plantilla'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function edit(Plantilla $plantilla)
    {
      $this->authorize('update', $plantilla);

      $plantilla->load('secciones');
      $variables = PlantillaVariable::toFormEditor();

      return view('admin.plantilla.edit', compact('plantilla', 'variables'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plantilla $plantilla)
    {
      $this->authorize('update', $plantilla);
      $this->validate($request, [
        'nombre' => 'required',
        'secciones' => 'required|min:1',
        'secciones.*.nombre' => 'nullable|string|max:50',
      ]);

      $plantilla->nombre = $request->nombre;

      $newSecciones = [];
      $plantillaSecciones = $plantilla->secciones;

      foreach($request->secciones as $seccion){
        // 
        if(isset($seccion['id'])){
          $plantillaSecciones->find($seccion['id'])
                              ->update([
                                'nombre' => $seccion['nombre'],
                                'contenido' => $seccion['contenido'],
                              ]);
        }else{
          $newSecciones[] = new PlantillaSeccion($seccion);
        }
      }

      if($plantilla->save()){
        if(count($newSecciones) > 0){
          $plantilla->secciones()->saveMany($newSecciones); 
        }

        return redirect()->route('admin.plantilla.show', ['plantilla' => $plantilla->id])->with([
          'flash_message' => 'Plantilla modificada exitosamente.',
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
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plantilla $plantilla)
    {
      $this->authorize('delete', $plantilla);

      if($plantilla->delete()){
        return redirect()->route('admin.plantilla.documento.index')->with([
          'flash_message' => 'Plantilla eliminada exitosamente.',
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
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function variables(Plantilla $plantilla)
    { 
      $secciones = $plantilla->secciones()->select('id', 'nombre', 'variables')->where('variables', 'LIKE', '[{"id":%')->get();

      return response()->json(['response' => $secciones->count() > 0, 'secciones' => $secciones]);
    }
}
