<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Contacto, Cliente, Proveedor};

class ContactoController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('permission:cliente-create|cliente-update|proveedor-create|proveedor-update');
    }

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

      return view('admin.contacto.create', compact('model', 'type'));
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
        'nombre' => 'required|string|max:100',
        'telefono' => 'required|string|max:20',
        'email' => 'nullable|email|max:50',
        'cargo' => 'nullable|string|max:50',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $contacto = new Contacto($request->only('nombre', 'telefono', 'email', 'cargo', 'descripcion'));

      if($model->contactos()->save($contacto)){
        if($request->ajax()){
          return response()->json(['response' => true, 'contacto' => $contacto]);
        }

        return redirect()->route('admin.'.$type.'.show', [$type => $model->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contacto agregado exitosamente.',
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
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function show(Contacto $contacto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function edit(Contacto $contacto)
    {
      return view('admin.contacto.edit', compact('contacto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contacto $contacto)
    {
      $this->validate($request, [
        'nombre' => 'required|string|max:100',
        'telefono' => 'required|string|max:20',
        'email' => 'nullable|email|max:50',
        'cargo' => 'nullable|string|max:50',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $contacto->fill($request->only('nombre', 'telefono', 'email', 'cargo', 'descripcion'));

      if($contacto->save()){
        return redirect()->route('admin.'.$contacto->type().'.show', [$contacto->type() => $contacto->contactable_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contacto modificado exitosamente.',
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
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contacto $contacto)
    {
      if($contacto->delete()){
        return redirect()->back()->with([
          'flash_message' => 'Contacto eliminado exitosamente.',
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
     * Cambiar status del contacto especificada.
     *
     * @param  \App\Contacto  $contacto
     * @return \Illuminate\Http\Response
     */
    public function status(Contacto $contacto)
    {
      $seleccionado = $contacto->contactable->contactos()->firstWhere('status', true);
      $contacto->status = true;

      if($contacto->save()){
        if($seleccionado){
          $seleccionado->update(['status' => false]);
        }

        return redirect()->back()->with([
          'flash_message' => 'Contacto seleccionado exitosamente.',
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
