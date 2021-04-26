<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\CentroCosto;

class CentroCostoController extends Controller
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
      $this->authorize('create', CentroCosto::class);

      return view('admin.centro.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', CentroCosto::class);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $centro = new CentroCosto($request->only('nombre'));

      if(Auth::user()->empresa->centros()->save($centro)){
        if($request->ajax()){
          return response()->json(['response' =>  true, 'centro' => $centro]);
        }

        return redirect()->route('admin.centro.show', ['centro' => $centro->id])->with([
          'flash_message' => 'Centro de costo agregado exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
      }

      return redirect()->route('admin.centro.create')->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CentroCosto  $centro
     * @return \Illuminate\Http\Response
     */
    public function show(CentroCosto $centro)
    {
      $this->authorize('view', $centro);

      $centro->load([
        'inventariosV2Egreso' => function () {
          $query->with([
            'inventario',
            'cliente',
            'user',
          ]);
        },
        'requerimientosMateriales' => function ($query){
          $query->with([
            'faena',
            'contrato',
            'dirigidoA'
          ])
          ->withCount('productos');
        },
        'facturas.contrato',
      ]);

      return view('admin.centro.show', compact('centro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CentroCosto  $centro
     * @return \Illuminate\Http\Response
     */
    public function edit(CentroCosto $centro)
    {
      $this->authorize('update', $centro);

      return view('admin.centro.edit', compact('centro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CentroCosto  $centro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CentroCosto $centro)
    {
      $this->authorize('update', $centro);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $centro->nombre = $request->nombre;

      if($centro->save()){
        return redirect()->route('admin.centro.show', ['centro' => $centro->id])->with([
          'flash_message' => 'Centro de costo modificado exitosamente.',
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
     * @param  \App\CentroCosto  $centro
     * @return \Illuminate\Http\Response
     */
    public function destroy(CentroCosto $centro)
    {
      $this->authorize('delete', $centro);

      if($centro->delete()){
        return redirect()->route('admin.contratos.index')->with([
          'flash_message' => 'Centro de costo eliminado exitosamente.',
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
