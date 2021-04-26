<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Faena;

class FaenaController extends Controller
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
      $this->authorize('create', Faena::class);

      return view('admin.faena.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Faena::class);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $faena = new Faena([
        'nombre' => $request->nombre
      ]);

      if(Auth::user()->empresa->faenas()->save($faena)){
        if($request->ajax()){
          return response()->json(['response' =>  true, 'faena' => $faena]);
        }

        return redirect()->route('admin.faena.show', ['faena' => $faena->id])->with([
          'flash_message' => 'Faena agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
      }

      return redirect()->route('admin.faena.create')->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faena  $faena
     * @return \Illuminate\Http\Response
     */
    public function show(Faena $faena)
    {
      $this->authorize('view', $faena);
      
      $faena->load([
        'contratos' => function ($query){
          $query->withCount('empleados');
        },
        'transportes',
        'inventariosV2Egreso' => function ($query) {
          $query->with([
            'inventario',
            'user',
            'cliente'
          ]);
        },
        'requerimientosMateriales' => function ($query){
          $query->with([
            'contrato',
            'centroCosto',
            'dirigidoA'
          ])
          ->withCount('productos');
        },
        'facturas',
      ]);

      return view('admin.faena.show', compact('faena'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faena  $faena
     * @return \Illuminate\Http\Response
     */
    public function edit(Faena $faena)
    {
      $this->authorize('update', $faena);

      return view('admin.faena.edit', compact('faena'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faena  $faena
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faena $faena)
    {
      $this->authorize('update', $faena);
      $this->validate($request, [
        'nombre' => 'required|max:50',
      ]);

      $faena->nombre = $request->nombre;

      if($faena->save()){
        return redirect()->route('admin.faena.show', ['faena' => $faena->id])->with([
          'flash_message' => 'Faena modificada exitosamente.',
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
     * @param  \App\Faena  $faena
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faena $faena)
    {
      $this->authorize('delete', $faena);

      if($faena->delete()){
        return redirect()->route('admin.contratos.index')->with([
          'flash_message' => 'Faena eliminada exitosamente.',
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
