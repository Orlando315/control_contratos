<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contrato;

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $contratos = Contrato::all();

      return view('contratos.index', ['contratos' => $contratos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('contratos.create');
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
        'nombre' => 'required|string',
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'required|date_format:d-m-Y',
        'valor' => 'required|numeric'
      ]);

      $contrato = new Contrato($request->all());

      if($contrato = Auth::user()->empresa->contratos()->save($contrato)){
        return redirect('contratos/' . $contrato->id)->with([
          'flash_message' => 'Contrato agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('contratos/create')->with([
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
    public function show(Contrato $contrato)
    {
      return view('contratos.show', ['contrato' => $contrato]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contrato $contrato)
    {
      return view('contratos.edit', ['contrato' => $contrato]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contrato $contrato)
    {
      $this->validate($request, [
        'nombre' => 'required|string',
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'required|date_format:d-m-Y',
        'valor' => 'required|numeric'
      ]);

      $contrato->fill($request->all());

      if($contrato->save()){
        return redirect('contratos/' . $contrato->id)->with([
          'flash_message' => 'Contrato modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('contratos/' . $contrato->id)->with([
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
    public function destroy(Contrato $contrato)
    {
      if($contrato->delete()){
        return redirect('contratos')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contrato eliminado exitosamente.'
        ]);
      }else{
        return redirect('contratos')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
