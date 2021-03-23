<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Postulante;

class PostulanteController extends Controller
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
    public function create()
    {
      $this->authorize('create', Postulante::class);

      return view('admin.postulante.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Postulante::class);
      $this->validate($request, [
        'nombres' => 'required|string|max:50',
        'apellidos' => 'nullable|string|max:50',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:postulantes,rut',
        'email' => 'nullable|email|unique:users,email',
        'telefono' => 'nullable|string'
      ]);

      $postulante = new Postulante($request->only('nombres', 'apellidos', 'rut', 'email', 'telefono'));

      if(Auth::user()->empresa->postulantes()->save($postulante)){
        return redirect()->route('admin.postulante.show', ['postulante' => $postulante->id])->with([
          'flash_message' => 'Postulante agregado exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }
      
      return redirect()->back()->withInput()>with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Postulante  $postulante
     * @return \Illuminate\Http\Response
     */
    public function show(Postulante $postulante)
    {
      $this->authorize('view', $postulante);

      $postulante->load([
        'plantillaDocumentos',
      ]);

      return view('admin.postulante.show', compact('postulante'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Postulante  $postulante
     * @return \Illuminate\Http\Response
     */
    public function edit(Postulante $postulante)
    {
      $this->authorize('update', $postulante);

      return view('admin.postulante.edit', compact('postulante'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Postulante  $postulante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Postulante $postulante)
    {
      $this->authorize('update', $postulante);
      $this->validate($request, [
        'nombres' => 'required|string|max:50',
        'apellidos' => 'nullable|string|max:50',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:postulantes,rut,' . $postulante->id . ',id',
        'email' => 'nullable|email|unique:postulantes,email,' . $postulante->id . ',id',
        'telefono' => 'nullable|string',
      ]);

      $postulante->fill($request->only('nombres', 'apellidos', 'rut', 'email', 'telefono'));

      if($postulante->save()){
        return redirect()->route('admin.postulante.show', ['postulante' => $postulante->id])->with([
          'flash_message' => 'Postulante modificado exitosamente.',
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
     * @param  \App\Postulante  $postulante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Postulante $postulante)
    {
      $this->authorize('delete', $postulante);

      if($postulante->delete()){
        return redirect()->route('admin.empleados.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Postulante eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Obtener la informacion del Postulante especificado
     * 
     * @param  \App\Postulante $postulante
     * @return \Illuminate\Http\Response 
     */
    public function get(Postulante $postulante)
    {
      return response()->json($postulante->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
    }
}
