<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Partida, Contrato};

class PartidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contrato  $contrato
     * @param  string  $tipo
     * @return \Illuminate\Http\Response
     */
    public function tipo(Contrato $contrato, $tipo)
    {
      $this->authorize('view', $contrato);
      $this->authorize('viewAny', Partida::class);

      $partidas = $contrato->partidas()->where('tipo', $tipo)->get();
      $monto = $partidas->sum('monto');

      return view('admin.partida.tipo', compact('contrato', 'tipo', 'monto', 'partidas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', Partida::class);

      $tipos = Partida::getTipos();

      return view('admin.partida.create', compact('contrato', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contrato $contrato)
    {
      $this->authorize('create', Partida::class);
      $this->validate($request, [
        'tipo' => 'required|in:'.Partida::getTipos(true),
        'codigo' => 'required|max:50',
        'monto' => 'required|min:0|max:999999999999',
        'descripcion' => 'nullable|max:100',
      ]);

      $partida = new Partida($request->only('tipo', 'codigo', 'descripcion', 'monto'));
      $partida->empresa_id = Auth::user()->empresa->id;

      if($contrato->partidas()->save($partida)){
        if($request->ajax()){
          return response()->json(['response' =>  true, 'partida' => $partida]);
        }

        return redirect()->route('admin.partida.show', ['partida' => $partida->id])->with([
          'flash_message' => 'Partida agregada exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
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
     * @param  \App\Partida  $partida
     * @return \Illuminate\Http\Response
     */
    public function show(Partida $partida)
    {
      $this->authorize('view', $partida);
      
      $partida->load([
        'contrato',
        'requerimientosMateriales',
        'compras',
        'facturas',
      ]);

      return view('admin.partida.show', compact('partida'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partida  $partida
     * @return \Illuminate\Http\Response
     */
    public function edit(Partida $partida)
    {
      $this->authorize('update', $partida);

      $tipos = Partida::getTipos();

      return view('admin.partida.edit', compact('partida', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partida  $partida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partida $partida)
    {
      $this->authorize('update', $partida);
      $this->validate($request, [
        'tipo' => 'required|in:'.Partida::getTipos(true),
        'codigo' => 'required|max:50',
        'descripcion' => 'nullable|max:100',
        'monto' => 'required|min:0|max:999999999999',
      ]);

      $partida->fill($request->only('tipo', 'codigo', 'descripcion', 'monto'));

      if($partida->save()){
        return redirect()->route('admin.partida.show', ['partida' => $partida->id])->with([
          'flash_message' => 'Partida modificada exitosamente.',
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
     * @param  \App\Partida  $partida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partida $partida)
    {
      $this->authorize('delete', $partida);

      if($partida->delete()){
        return redirect()->route('admin.contratos.show', ['contrato' => $partida->contrato_id])->with([
          'flash_message' => 'Partida eliminada exitosamente.',
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
