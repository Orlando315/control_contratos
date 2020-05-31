<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Inventario;
use App\Contrato;

class InventariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $inventarios = Inventario::all();

      return view('inventarios.index', ['inventarios' => $inventarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $contratos = Contrato::all();

      return view('inventarios.create', ['contratos' => $contratos]);
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
        'contrato_id' => 'required',
        'tipo' => 'required',
        'nombre' => 'required|string',
        'valor' => 'required|numeric',
        'fecha' => 'required|date_format:d-m-Y',
        'cantidad' => 'required|integer|min:1|max:999999',
        'stock_critico' => 'nullable|integer|min:0|max:999',
        'descripcion' => 'nullable|string|max:200',
        'observacion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $inventario = new Inventario($request->all());
      $inventario->low_stock = $request->stock_critico;

      if(Auth::user()->tipo >= 3){
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

        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Inventario agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('inventarios/create')->with([
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
    public function show(Inventario $inventario)
    {
      return view('inventarios.show', ['inventario' => $inventario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
      // Los usuarios Supervisor solo pueden editar Inventarios tipo 3
      if(Auth::user()->tipo >= 3 && $inventario->tipo < 3){
        abort(404);
      }

      return view('inventarios.edit', ['inventario' => $inventario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventario $inventario)
    {
      // Los usuarios Supervisor solo pueden editar Inventarios tipo 3
      if(Auth::user()->tipo >= 3 && $inventario->tipo < 3){
        abort(404);
      }

      $this->validate($request, [
        'tipo' => 'required',
        'nombre' => 'required|string',
        'valor' => 'required|numeric',
        'fecha' => 'required|date_format:d-m-Y',
        'cantidad' => 'required|integer|min:1|max:999999',
        'stock_critico' => 'nullable|integer|min:0|max:999',
        'descripcion' => 'nullable|string|max:200',
        'observacion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $inventario->fill($request->all());
      $inventario->low_stock = $request->stock_critico;

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

        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Inventario modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('inventarios/' . $inventario->id)->with([
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
    public function destroy(Inventario $inventario)
    {

      // Los usuarios Supervisor solo pueden editar Inventarios tipo 3
      if(Auth::user()->tipo >= 3 && $inventario->tipo < 3){
        abort(404);
      }

      if($inventario->delete()){

        if($inventario->adjunto){
          Storage::deleteDirectory($inventario->directory());
        }

        return redirect('inventarios')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Inventario eliminado exitosamente.'
        ]);
      }else{
        return redirect('inventarios')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    public function download(Inventario $inventario)
    {
      return Storage::download($inventario->adjunto);
    }
}
