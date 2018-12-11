<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Empleado;
use App\EmpleadosDocumento;

class EmpleadosDocumentosController extends Controller
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
    public function create($empleado)
    {
      return view('documentos.create', ['empleado'=> $empleado]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Empleado $empleado)
    {

      if($empleado->documentos()->count() >= 10){
        return redirect('empleados/' . $empleado->id)->with([
          'flash_message' => 'No se pueden agregar mas documentos a este empleado.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $this->validate($request, [
        'nombre' => 'required|string',
        'documento' => 'required|file|mimetypes:image/jpeg,image/png,application/postscript,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'vencimiento' => 'nullable|date_format:d-m-Y'
      ]);

      $documento = new EmpleadosDocumento;

      $documento->nombre = $request->nombre;
      $documento->mime   = $request->documento->getMimeType();
      $documento->vencimiento = $request->vencimiento;

      if($documento = $empleado->documentos()->save($documento)){

        $directory = 'Empresa' . Auth::user()->id . '/Empleado' . $empleado->id;

        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        $documento->path = $request->file('documento')->store($directory);

        $documento->save();
        
        return redirect('empleados/' . $empleado->id)->with([
          'flash_message' => 'Documento agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('empleados/' . $empleado->id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);        
      }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpleadosDocumento $documento)
    {
      return view('documentos.edit', ['documento'=>$documento]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpleadosDocumento $documento)
    {
      $this->validate($request, [
        'nombre' => 'required|string',
        'vencimiento' => 'nullable|date_format:d-m-Y'
      ]);

      $documento->nombre = $request->nombre;
      $documento->vencimiento = $request->vencimiento;

      if($documento->save()){        
        return redirect('empleados/' . $documento->empleado_id)->with([
          'flash_message' => 'Documento editado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('empleados/' . $documento->empleado_id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpleadosDocumento $documento)
    {
      if($documento->delete()){
        Storage::delete($documento->path);

        $response = ['response' => true, 'id' => $documento->id];
      }else{
        $response = ['response' => false];
      }

      return $response;
    }

    public function download(EmpleadosDocumento $documento)
    {
      return Storage::download($documento->path);
    }
}
