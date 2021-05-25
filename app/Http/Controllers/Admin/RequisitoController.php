<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\{Requisito, Contrato};

class RequisitoController extends Controller
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
     * @param  \App\Contrato  $contrato
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato, $type)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', Requisito::class);

      $type = Requisito::allowedTypes($type);

      return view('admin.requisito.create', compact('contrato', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contrato $contrato, $type)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', Requisito::class);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'carpeta' => 'nullable|boolean',
      ]);

      $type = Requisito::allowedTypes($type);
      $requisito = new requisito($request->only('nombre'));
      $requisito->empresa_id = Auth::user()->empresa->id;
      $requisito->type = $type;
      $requisito->folder = $request->has('carpeta') && $request->carpeta == '1';

      if($contrato->requisitos()->save($requisito)){
        return redirect()->route('admin.contrato.show', ['contrato' => $contrato->id])->with([
          'flash_message' => 'Requisito agregado exitosamente.',
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
     * @param  \App\Requisito  $requisito
     * @return \Illuminate\Http\Response
     */
    public function show(Requisito $requisito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requisito  $requisito
     * @return \Illuminate\Http\Response
     */
    public function edit(Requisito $requisito)
    {
      $this->authorize('update', $requisito);

      return view('admin.requisito.edit', compact('requisito'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requisito  $requisito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requisito $requisito)
    {
      $this->authorize('update', $requisito);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
      ]);

      $requisito->nombre = $request->nombre;

      if($requisito->save()){
        return redirect()->route('admin.contrato.show', ['contrato' => $requisito->contrato_id])->with([
          'flash_message' => 'Requisito modificado exitosamente.',
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
     * @param  \App\Requisito  $requisito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requisito $requisito)
    {
      $this->authorize('delete', $requisito);

      if($requisito->delete()){
        return redirect()->route('admin.contrato.show', ['contrato' => $requisito->contrato_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Requisito eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
