<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\InventarioEntrega;
use App\Inventario;
use App\Contrato;

class InventariosEntregasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Inventario $inventario)
    {
      $inventarios = Inventario::all();
      $contratos = Contrato::all();

      return view('entregas.create', ['inventario' => $inventario, 'inventarios' => $inventarios, 'contratos' => $contratos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Inventario $inventario)
    {
      $this->validate($request, [
        'empleado_id' => 'required',
        'cantidad' => 'required|numeric'
      ]);

      if(($inventario->cantidad - $request->cantidad) < 0){
        return redirect('entregas/'. $inventario->id)
                  ->withErrors('La cantidad supera lo disponible en inventario.')
                  ->withInput();
      }

      $entrega = new InventarioEntrega;
      $entrega->realizado = Auth::user()->id;
      $entrega->entregado = $request->empleado_id;
      $entrega->cantidad  = $request->cantidad;

      if($inventario->entregas()->save($entrega)){
        $inventario->cantidad -= $request->cantidad;
        $inventario->save();

        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Entrega agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('entregas/' . $inventario->id)->with([
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
      return view('inventarios.edit', ['inventario' => $inventario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario, InventarioEntrega $entrega)
    {
      $inventario->cantidad += $entrega->cantidad;

      if($entrega->delete()){
        $inventario->save();

        return redirect('inventarios/' . $inventario->id)->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Entrega eliminada exitosamente.'
        ]);
      }else{
        return redirect('inventarios/' . $inventario->id)->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
