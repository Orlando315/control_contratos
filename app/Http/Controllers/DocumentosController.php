<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Empleado;
use App\Contrato;
use App\Documento;
use App\Carpeta;

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
     * @param  \App\Empleado  $empleado
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function createEmpleado(Empleado $empleado, Carpeta $carpeta = null)
    {
      return view('documentos.create', ['route'=> route('documentos.storeEmpleado', ['empleado' => $empleado->id, 'carpeta' => $carpeta])]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Contrato  $contrato
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function createContrato(Contrato $contrato, Carpeta $carpeta = null)
    {
      return view('documentos.create', ['route'=> route('documentos.storeContrato', ['contrato' => $contrato->id, 'carpeta' => $carpeta])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object  $model
     * @param  string  $directory
     * @param  \App\Carpeta  $carpeta
     * @return bool
     */
    protected function store(Request $request, $model, $directory, Carpeta $carpeta = null)
    {
      $this->validate($request, [
        'nombre' => 'required|string',
        'documento' => 'required|file|mimetypes:image/jpeg,image/png,application/postscript,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'vencimiento' => 'nullable|date_format:d-m-Y'
      ]);

      $documento = new Documento;
      $documento->nombre = $request->nombre;
      $documento->mime   = $request->documento->getMimeType();
      $documento->vencimiento = $request->vencimiento;
      $documento->empresa_id = Auth::user()->empresa->id;
      $documento->carpeta_id = optional($carpeta)->id;

      if($documento = $model->documentos()->save($documento)){
        $directory = 'Empresa' . Auth::user()->empresa->id . $directory . $model->id;

        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        $documento->path = $request->file('documento')->store($directory);

        $documento->save();
        
        return true;
      }
      
      return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function storeEmpleado(Request $request, Empleado $empleado, Carpeta $carpeta = null)
    {
      if($empleado->documentos()->count() >= 10){
        return redirect('empleados/' . $empleado->id)->with([
          'flash_message' => 'No se pueden agregar mas documentos a este empleado.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $result = $this->store($request, $empleado, '/Empleado', $carpeta);

      if($result){
        $redirect = $carpeta ? route('carpeta.show', ['carpeta' => $carpeta]) : route('empleados.show', ['empleado' => $empleado->id]);

        return redirect($redirect)->with([
          'flash_message' => 'Documento agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect('empleados/' . $empleado->id)->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function storeContrato(Request $request, Contrato $contrato, Carpeta $carpeta = null)
    {
      if($contrato->documentos()->count() >= 10){
        return redirect('contratos/' . $contrato->id)->with([
          'flash_message' => 'No se pueden agregar mas documentos a este contrato.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $result = $this->store($request, $contrato, '/Contrato', $carpeta);

      if($result){
        $redirect = $carpeta ? route('carpeta.show', ['carpeta' => $carpeta]) : route('contratos.show', ['contrato' => $contrato->id]);

        return redirect($redirect)->with([
          'flash_message' => 'Documento agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect('contratos/' . $contrato->id)->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
      return view('documentos.edit', ['documento'=>$documento]);
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

      $route = $documento->empleado_id ? 'empleado' : 'contrato';

      if($documento->save()){        
        return redirect("{$route}s/" . $documento->{"{$route}_id"})->with([
          'flash_message' => 'Documento editado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect('documentos/' . $documento->id . '/edit')->with([
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

      $response = ['response' => false];

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
      return Storage::download($documento->path);
    }
}
