<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Direccion, Cliente, Proveedor};

class DireccionController extends Controller
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
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function create($id, $type)
    {
      if(!in_array($type, ['cliente', 'proveedor'])){
        abort(404);
      }
      $model = $type == 'cliente' ? Cliente::findOrFail($id) : Proveedor::findOrFail($id);

      return view('admin.direccion.create', compact('model', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id, $type)
    {
      if(!in_array($type, ['cliente', 'proveedor'])){
        abort(404);
      }
      $model = $type == 'cliente' ? Cliente::findOrFail($id) : Proveedor::findOrFail($id);

      $this->validate($request, [
        'ciudad' => 'nullable|string|max:50',
        'comuna' => 'nullable|string|max:50',
        'direccion' => 'required|string|max:200',
      ]);

      $direccion = new Direccion($request->only('ciudad', 'comuna', 'direccion'));

      if($model->direcciones()->save($direccion)){
        if($request->ajax()){
          return response()->json(['response' => true, 'direccion' => $direccion]);
        }

        return redirect()->route('admin.'.$type.'.show', [$type => $model->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Dirección agregada exitosamente.',
        ]);
      }

      if($request->ajax()){
        return response()->json(['response' => false]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function show(Direccion $direccion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function edit(Direccion $direccion)
    {
      return view('admin.direccion.edit', compact('direccion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Direccion $direccion)
    {
      $this->validate($request, [
        'ciudad' => 'nullable|string|max:50',
        'comuna' => 'nullable|string|max:50',
        'direccion' => 'required|string|max:200',
      ]);

      $direccion->fill($request->only('ciudad', 'comuna', 'direccion'));

      if($direccion->save()){
        return redirect()->route('admin.'.$direccion->type().'.show', [$direccion->type() => $direccion->direccionable_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Dirección modificada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Direccion $direccion)
    {
      if($direccion->delete()){
        return redirect()->back()->with([
          'flash_message' => 'Dirección eliminada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->back()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Cambiar status de la direccion especificada.
     *
     * @param  \App\Direccion  $direccion
     * @return \Illuminate\Http\Response
     */
    public function status(Direccion $direccion)
    {
      $seleccionada = $direccion->direccionable->direcciones()->firstWhere('status', true);
      $direccion->status = true;

      if($direccion->save()){
        if($seleccionada){
          $seleccionada->update(['status' => false]);
        }

        return redirect()->back()->with([
          'flash_message' => 'Dirección seleccionada exitosamente.',
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
