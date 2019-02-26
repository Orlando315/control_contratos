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
      $empleados = $inventario->contrato->empleados()->with('usuario:id,empleado_id,nombres,apellidos')->get();

      return view('inventarios.entregas.create', ['inventario' => $inventario, 'empleados' => $empleados]);
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
        'usuario' => 'required',
        'cantidad' => 'required|numeric'
      ]);

      if(($inventario->cantidad - $request->cantidad) < 0){
        return redirect('inventarios/entregas/'. $inventario->id)
                  ->withErrors('La cantidad supera lo disponible en inventario.')
                  ->withInput();
      }

      $entrega = new InventarioEntrega;
      $entrega->realizado = Auth::user()->id;
      $entrega->entregado = $request->usuario;
      $entrega->cantidad  = $request->cantidad;

      if($inventario->entregas()->save($entrega)){
        $inventario->cantidad -= $request->cantidad;
        $inventario->save();

        return redirect('inventarios/' . $inventario->id)->with([
          'flash_message' => 'Entrega agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('inventarios/entregas/' . $inventario->id)->with([
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
    public function show(InventarioEntrega $inventario)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InventarioEntrega $inventario)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventarioEntrega $entrega)
    {
      if(Auth::user()->id === $entrega->entregado){
        $entrega->recibido = true;

        if($entrega->save()){
          $response = ['response' => true];
        }else{
          $response = ['response' => false, 'message' => 'Ha ocurrido un error.'];
        }
      }else{
        $response = ['response' => false, 'message' => 'No estas autorizado a confirmar esta entrega.'];
      }


        return $response;
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
