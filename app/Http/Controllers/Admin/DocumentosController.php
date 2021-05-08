<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Route};
use App\{Empleado, Contrato, Documento, Carpeta, TransporteConsumo, Requisito};

class DocumentosController extends Controller
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
     * @param  string $type
     * @param  int  $id
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function create($type, $id, Carpeta $carpeta = null)
    {
      $class = Carpeta::getModelClass($type);
      $model = $class::findOrFail($id);
      $requisitos = ($class == 'App\Empleado' || $class == 'App\Contrato' || $class == 'App\Transporte') ? $model->requisitosFaltantes() : [];
      $requisitoSelected = Requisito::where([['id', request()->requisito], ['type', $type]])->first();
      $varName = Carpeta::getRouteVarNameByType($type);

      return view('admin.documentos.create', compact('model', 'carpeta', 'type', 'varName', 'requisitos', 'requisitoSelected'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @param  int  $id
     * @param  \App\Carpeta  $carpeta
     * @return bool
     */
    public function store(Request $request, $type, $id, Carpeta $carpeta = null)
    {
      $class = Carpeta::getModelClass($type);
      $model = $class::findOrFail($id);
      $varName = Carpeta::getRouteVarNameByType($type);

      if($type == 'contratos'){
        $directory = 'Empresa' . Auth::user()->empresa->id . '/Contrato'.$model->id;
      }

      if($type == 'empleados'){
        $class = 'App\Empleado';
        $directory = 'Empresa' . Auth::user()->empresa->id . '/Empleado'.$model->id;
      }

      if($type == 'consumos' || $type == 'transportes'){
        $directory = 'Empresa' . Auth::user()->empresa->id . '/Transportes/'.$model->transporte_id ?? $model->id;
      }

      if($type == 'inventarios'){
        $class = 'App\Inventario';
        $directory = $model->directory();
      }

      $this->validate($request, [
        'nombre' => 'required_without:requisito|string|max:50',
        'observacion' => 'nullable|string|max:100',
        'documento' => 'required|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'vencimiento' => 'nullable|date_format:d-m-Y',
        'visibilidad' => 'nullable|boolean',
      ]);

      $documento = new Documento($request->only('nombre', 'observacion', 'vencimiento'));
      $documento->mime = $request->documento->getMimeType();
      $documento->empresa_id = Auth::user()->empresa->id;
      $documento->carpeta_id = optional($carpeta)->id;
      $documento->visibilidad = $request->has('visibilidad') && $request->visibilidad == '1';

      // Varificar si se esta cargando un documento que sea "requisito"
      if($request->requisito){
        $requisito = Requisito::where([['id', $request->requisito], ['type', $type]])->firstOrFail();
        $documento->nombre = $requisito->nombre;
        $documento->requisito_id = $requisito->id;
      }

      if($model->documentos()->save($documento)){
        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        $documento->path = $request->file('documento')->store($directory);
        $documento->save();
        
        $redirect = $carpeta ? route('admin.carpeta.show', ['carpeta' => $carpeta]) : route('admin.'.$type.'.show', [$varName => $model->id]);

        return redirect($redirect)->with([
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
     * Display the specified resource.
     *
     * @param  \App\Documento  $documeento
     * @return \Illuminate\Http\Response
     */
    public function show(Documento $documento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function edit(Documento $documento)
    {
      $requisitos = ($documento->isType('App\Empleado') || $documento->isType('App\Contrato') || $documento->isType('App\Transporte')) ? $documento->documentable->requisitosFaltantes() : [];

      return view('admin.documentos.edit', compact('documento', 'requisitos'));
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
      $this->validate($request, [
        'nombre' => 'required_without:requisito|string|max:50',
        'observacion' => 'nullable|string|max:100',
        'vencimiento' => 'nullable|date_format:d-m-Y',
        'visibilidad' => 'nullable|boolean',
      ]);

      $documento->fill($request->only('nombre', 'observacion', 'vencimiento'));
      $documento->visibilidad = $request->has('visibilidad') && $request->visibilidad == '1';

      if($request->requisito){
        $requisito = Requisito::where([['id', $request->requisito], ['type', Carpeta::getTypeFromClass($documento->documentable_type)]])->firstOrFail();
        $documento->nombre = $requisito->nombre;
        $documento->requisito_id = $requisito->id;
      }

      if($documento->save()){
        return redirect($documento->backUrl)->with([
          'flash_message' => 'Adjunto editado exitosamente.',
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
      if($documento->delete()){
        Storage::delete($documento->path);

        if(request()->ajax()){
          $response = ['response' => true, 'id' => $documento->id];
        }

        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Documento eliminado exitosamente.'
        ]);
      }

      if(request()->ajax()){
        return response()->json(['response' => false]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Actualizar las relaciones de Documentos => Contrato/Empleado
     * a relaciones usando morph
     */
    public function migrateToMorph(){
      $documentos = Documento::withoutGlobalScope(\App\Scopes\EmpresaScope::class)->get();

      foreach ($documentos as $documento) {
        $documento->documentable_type = $documento->contrato_id ? 'App\Contrato' : 'App\Empleado';
        $documento->documentable_id = $documento->contrato_id ?? $documento->empleado_id;
        $documento->contrato_id = null;
        $documento->empleado_id = null;
        $documento->save();
      }
    }

    /**
     * Migrar la informacion de la tabla de ConsumoAdjunto a Documentos
     */
    public function migrateTransporteAdjuntosToDocumentos(){
      $consumos = TransporteConsumo::has('adjuntos')->with(['transporte' => function ($query){
                                      $query->withoutGlobalScope(\App\Scopes\EmpresaScope::class);
                                    }])
                                    ->get();

      foreach ($consumos as $consumo) {
        $adjuntos = $consumo->adjuntos->map(function ($item, $key) use ($consumo){
                      return collect($item)->except('id', 'consumo_id')->put('empresa_id', $consumo->transporte->empresa_id);
                    })
                    ->toArray();
        $consumo->documentos()->createMany($adjuntos);
      }
    }
}
