<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Route};
use App\{Empleado, Contrato, Documento, Carpeta, TransporteConsumo};

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
     * @param  int  $id
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function create($id, Carpeta $carpeta = null)
    {
      $type = explode('.', Route::currentRouteName())[2];
      $class = 'App\\'.($type == 'contratos' ? 'Contrato' : ($type == 'empleados' ? 'Empleado' : 'TransporteConsumo'));
      $model = $class::findOrFail($id);

      return view('documentos.create', compact('model', 'carpeta', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \App\Carpeta  $carpeta
     * @return bool
     */
    public function store(Request $request, $id, Carpeta $carpeta = null)
    {
      $type = explode('.', Route::currentRouteName())[2];
      $class = 'App\\'.($type == 'contratos' ? 'Contrato' : ($type == 'empleados' ? 'Empleado' : 'TransporteConsumo'));
      $model = $class::findOrFail($id);

      if($type == 'contratos'){
        $modelDirectory = '/Contrato'.$model->id;
      }

      if($type == 'empleados'){
        $class = 'App\Empleado';
        $modelDirectory = '/Empleado'.$model->id;
      }

      if($type == 'consumos'){
        $modelDirectory = '/Transportes/'.$model->transporte_id;
      }

      $this->validate($request, [
        'nombre' => 'required|string',
        'documento' => 'required|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'vencimiento' => 'nullable|date_format:d-m-Y'
      ]);

      $documento = new Documento($request->only('nombre', 'vencimiento'));
      $documento->mime = $request->documento->getMimeType();
      $documento->empresa_id = Auth::user()->empresa->id;
      $documento->carpeta_id = optional($carpeta)->id;

      if($model->documentos()->save($documento)){
        $directory = 'Empresa' . Auth::user()->empresa->id . $modelDirectory;

        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        $documento->path = $request->file('documento')->store($directory);
        $documento->save();
        
        $redirect = $carpeta ? route('carpeta.show', ['carpeta' => $carpeta]) : route($type.'.show', ['id' => $model->id]);

        return redirect($redirect)->with([
          'flash_message' => 'Documento agregado exitosamente.',
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
      return view('documentos.edit', compact('documento'));
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
        'nombre' => 'required|string',
        'vencimiento' => 'nullable|date_format:d-m-Y'
      ]);

      $documento->nombre = $request->nombre;
      $documento->vencimiento = $request->vencimiento;

      if($documento->save()){        
        return redirect($documento->backUrl)->with([
          'flash_message' => 'Documento editado exitosamente.',
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

        $response = ['response' => true, 'id' => $documento->id];
      }else{
        $response = ['response' => false];
      }

      return $response;
    }

    /**
     * Descargar el Documento especificado
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function download(Documento $documento)
    {
      if(!Storage::exists($documento->path)){
        abort(404);
      }

      return Storage::download($documento->path);
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
