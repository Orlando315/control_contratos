<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{Inventario, Contrato};

class InventariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Inventario::class);

      $inventarios = Inventario::all();

      return view('admin.inventarios.index', compact('inventarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      abort(404);
      $this->authorize('create', Inventario::class);

      $contratos = Contrato::all();

      return view('admin.inventarios.create', compact('contratos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      abort(404);
      $this->authorize('create', Inventario::class);
      $this->validate($request, [
        'contrato_id' => 'required',
        'tipo' => 'required',
        'otro' => 'required_if:tipo,otro|string|max:50',
        'nombre' => 'required|string',
        'valor' => 'required|numeric',
        'fecha' => 'required|date_format:d-m-Y',
        'cantidad' => 'required|integer|min:1|max:999999',
        'stock_critico' => 'nullable|integer|min:0|max:999',
        'descripcion' => 'nullable|string|max:200',
        'observacion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'calibracion' => 'nullable|boolean',
        'certificado' => 'nullable|boolean',
      ]);

      $inventario = new Inventario($request->all());
      $inventario->low_stock = $request->stock_critico;
      $inventario->calibracion = $request->has('calibracion');
      $inventario->certificado = $request->has('certificado');

      if(Auth::user()->hasRole('supervisor')){
        $inventario->tipo = 3;
        $inventario->contrato_id = Auth::user()->empleado->contrato_id;
      }

      if($inventario = Auth::user()->empresa->inventarios()->save($inventario)){

        if($request->hasFile('adjunto')){
          $directory = $inventario->directory();
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $inventario->adjunto = $request->file('adjunto')->store($directory);
          $inventario->save();
        }

        return redirect()->route('admin.inventarios.show', ['inventario' => $inventario->id])->with([
          'flash_message' => 'Inventario agregado exitosamente.',
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
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
      $this->authorize('view', $inventario);

      return view('admin.inventarios.show', compact('inventario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
      abort(404);
      $this->authorize('update', $inventario);
      // Los usuarios Supervisor solo pueden editar Inventarios tipo 3
      if(Auth::user()->hasRole('supervisor') && $inventario->tipo < 3){
        abort(404);
      }

      return view('admin.inventarios.edit', ['inventario' => $inventario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventario $inventario)
    {
      abort(404);
      $this->authorize('update', $inventario);
      // Los usuarios Supervisor solo pueden editar Inventarios tipo 3
      if(Auth::user()->hasRole('supervisor') && $inventario->tipo < 3){
        abort(404);
      }

      $this->validate($request, [
        'tipo' => 'required',
        'otro' => 'required_if:tipo,otro|string|max:50',
        'nombre' => 'required|string',
        'valor' => 'required|numeric',
        'fecha' => 'required|date_format:d-m-Y',
        'cantidad' => 'required|integer|min:1|max:999999',
        'stock_critico' => 'nullable|integer|min:0|max:999',
        'descripcion' => 'nullable|string|max:200',
        'observacion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'calibracion' => 'nullable|boolean',
        'certificado' => 'nullable|boolean',
      ]);

      $inventario->fill($request->all());
      $inventario->low_stock = $request->stock_critico;
      $inventario->calibracion = $request->has('calibracion');
      $inventario->certificado = $request->has('certificado');

      if($inventario->save()){
        if($request->hasFile('adjunto')){

          // Si ya tine un archivo adjunto, eliminarlo
          if($inventario->adjunto){
            Storage::delete($inventario->adjunto);
          }

          $directory = $inventario->directory();

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $inventario->adjunto = $request->file('adjunto')->store($directory);
          $inventario->save();
        }

        return redirect()->route('admin.inventarios.show', ['inventario' => $inventario->id])->with([
          'flash_message' => 'Inventario modificado exitosamente.',
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
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
      abort(404);
      $this->authorize('delete', $inventario);
      // Los usuarios Supervisor solo pueden editar Inventarios tipo 3
      if(Auth::user()->hasRole('supervisor') && $inventario->tipo < 3){
        abort(404);
      }

      if($inventario->delete()){
        if($inventario->adjunto){
          Storage::deleteDirectory($inventario->directory());
        }

        return redirect()->route('admin.inventarios.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Inventario eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Descargar el adjunto del Recurso especficado
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function download(Inventario $inventario)
    {
      $this->authorize('view', $inventario);

      if(!Storage::exists($inventario->adjunto)){
        abort(404);
      }

      return Storage::download($inventario->adjunto);
    }

    /**
     * Clonar el registro del Inventario especificado
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function clone(Inventario $inventario)
    {
      abort(404);
      $this->authorize('update', $inventario);

      $copy = $inventario->replicate();
      $copy->adjunto = null;
      $copy->nombre = $inventario->nombre.' (copia)';

      if($copy->save()){
        return redirect()->route('admin.inventarios.show', ['inventario' => $copy->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Inventario clonado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
