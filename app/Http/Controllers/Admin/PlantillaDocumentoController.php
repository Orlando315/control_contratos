<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{PlantillaDocumento as Documento, Contrato, Empleado, Plantilla, PlantillaVariable, Postulante};
use PDF;

class PlantillaDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Documento::class);

      $documentos = Documento::with(['contrato', 'empleado', 'padre'])->get();
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
      if($contrato){
        $this->authorize('view', $contrato);
      }

      if($empleado){
        $this->authorize('view', $empleado);
      }

      $contratos = Contrato::all();
      $plantillas = Plantilla::all();
      $padres = Documento::all();
      $postulantes = Postulante::all();
      $postulanteSelected = Postulante::find(request()->postulante);

      return view('admin.plantilla-documento.create', compact('contratos', 'contrato', 'plantillas', 'padres', 'empleado', 'postulantes', 'postulanteSelected'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Documento::class);
      $this->validate($request, [
        'nombre' => 'nullable|string|max:50',
        'contrato' => 'required_without:dirigido',
        'empleado' => 'required_without:dirigido',
        'postulante' => 'required_with:dirigido',
        'plantilla' => 'required',
        'caducidad' => 'nullable|date',
        'visibilidad' => 'nullable|boolean',
      ]);

      $documento = new Documento([
        'contrato_id' => $request->contrato,
        'empleado_id' => $request->empleado,
        'postulante_id' => $request->postulante,
        'plantilla_id' => $request->plantilla,
        'documento_id' => $request->padre,
        'nombre' => $request->nombre,
        'caducidad' => $request->caducidad,
        'secciones' => $request->secciones
      ]);
      $documento->visibilidad = $request->has('visibilidad') && $request->visibilidad == '1';

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
      $this->authorize('view', $documento);

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
      $this->authorize('update', $documento);

      $contratos = Contrato::all();
      $plantillas = Plantilla::all();
      $padres = Documento::all();
      $postulantes = Postulante::all();

      return view('admin.plantilla-documento.edit', compact('documento', 'contratos', 'plantillas', 'padres', 'postulantes'));
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
      $this->authorize('update', $documento);
      $this->validate($request, [
        'nombre' => 'nullable|string|max:50',
        'contrato' => 'required_without:dirigido',
        'empleado' => 'required_without:dirigido',
        'postulante' => 'required_with:dirigido',
        'plantilla' => 'required',
        'caducidad' => 'nullable|date',
      ]);

      $documento->fill([
        'contrato_id' => $request->contrato,
        'empleado_id' => $request->empleado,
        'postulante_id' => $request->postulante,
        'plantilla_id' => $request->plantilla,
        'documento_id' => $request->padre,
        'nombre' => $request->nombre,
        'caducidad' => $request->caducidad,
        'secciones' => $request->secciones
      ]);
      $documento->visibilidad = $request->has('visibilidad') && $request->visibilidad == '1';

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
      $this->authorize('delete', $documento);

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
