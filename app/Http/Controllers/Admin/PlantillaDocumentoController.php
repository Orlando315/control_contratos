<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{PlantillaDocumento as Documento, Contrato, Empleado, Plantilla, PlantillaVariable};

class PlantillaDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $documentos = Documento::all();
      $plantillas = Plantilla::withCount(['secciones', 'documentos'])->get();
      $variables = PlantillaVariable::all();

      return view('admin.plantilla-documento.index', compact('documentos', 'plantillas', 'variables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Contrato  $contrato
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato = null, Empleado $empleado = null)
    {
      $selected = $contrato;
      $contratos = Contrato::all();
      $plantillas = Plantilla::all();
      $padres = Documento::all();

      return view('admin.plantilla-documento.create', compact('contratos', 'selected', 'plantillas', 'padres', 'empleado'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'nombre' => 'nullable|string|max:50',
        'contrato' => 'required',
        'empleado' => 'required',
        'plantilla' => 'required',
        'caducidad' => 'nullable|date',
      ]);

      $documento = new Documento([
                                  'contrato_id' => $request->contrato,
                                  'empleado_id' => $request->empleado,
                                  'plantilla_id' => $request->plantilla,
                                  'documento_id' => $request->padre,
                                  'nombre' => $request->nombre,
                                  'caducidad' => $request->caducidad,
                                  'secciones' => $request->secciones
                                ]);

      if(Auth::user()->empresa->documentos()->save($documento)){
        return redirect()->route('admin.plantilla.documento.show', ['documento' => $documento->id])->with([
          'flash_message' => 'Documento registrado exitosamente.',
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
     * @param  \App\PlantillaDocumento  $documento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
      $documento->load('plantilla.secciones');

      return view('admin.plantilla-documento.show', compact('documento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PlantillaDocumento  $documento
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {
      $contratos = Contrato::all();
      $plantillas = Plantilla::all();
      $padres = Documento::all();

      return view('admin.plantilla-documento.edit', compact('documento', 'contratos', 'plantillas', 'padres'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PlantillaDocumento  $documento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Documento $documento)
    {
      $this->validate($request, [
        'nombre' => 'nullable|string|max:50',
        'contrato' => 'required',
        'empleado' => 'required',
        'plantilla' => 'required',
        'caducidad' => 'nullable|date',
      ]);

      $documento->fill([
                        'contrato_id' => $request->contrato,
                        'empleado_id' => $request->empleado,
                        'plantilla_id' => $request->plantilla,
                        'documento_id' => $request->padre,
                        'nombre' => $request->nombre,
                        'caducidad' => $request->caducidad,
                        'secciones' => $request->secciones
                      ]);

      if($documento->save()){
        return redirect()->route('admin.plantilla.documento.show', ['documento' => $documento->id])->with([
          'flash_message' => 'Documento registrado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      if($documento->save()){
        return redirect()->route('admin.plantilla.documento.show', ['documento' => $documento->id])->with([
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
     * @param  \App\PlantillaDocumento  $documento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documento $documento)
    {
      if($documento->delete()){
        return redirect()->route('admin.plantilla.documento.index')->with([
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
