<?php

namespace App\Http\Controllers\Admin\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Plantilla, PlantillaVariable, PlantillaSeccion, Empresa};

class PlantillaController extends Controller
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
    public function create()
    {
      $this->authorize('create', Plantilla::class);
      $variables = PlantillaVariable::toEditor(PlantillaVariable::withoutGlobalScopes()->global()->get());

      return view('admin.manage.plantilla.create', compact('variables'));
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

      $empresas = Empresa::all();

      foreach($empresas as $empresa){
        $plantilla = new Plantilla($request->only('nombre'));

        $secciones = [];
        foreach($request->secciones as $seccion){
          $secciones[] = new PlantillaSeccion($seccion);
        }

        if($empresa->plantillas()->save($plantilla)){
          $plantilla->secciones()->saveMany($secciones);
        }
      }

      return redirect()->route('admin.manage.plantilla.create', ['plantilla' => $plantilla->id])->with([
        'flash_message' => 'Plantilla registrada exitosamente para las Empresas.',
        'flash_class' => 'alert-success'
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function edit(Plantilla $plantilla)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Plantilla  $plantilla
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plantilla $plantilla)
    {
        //
    }
}
