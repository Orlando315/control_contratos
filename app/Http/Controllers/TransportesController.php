<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Contrato;
use App\Transporte;
use App\Usuario;

class TransportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $transportes = Transporte::all();

      return view('transportes.index', ['transportes' => $transportes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $contratos = Contrato::all();
      $usuarios = Usuario::supervisores();

      return view('transportes.create', ['contratos' => $contratos, 'usuarios' => $usuarios]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $contrato = Contrato::findOrFail($request->contrato);
      $supervisor = Usuario::findOrFail($request->supervisor);

      $this->validate($request, [
        'vehiculo' => 'required',
        'patente' => 'required',
      ]);

      $transporte = new Transporte($request->all());
      $transporte->contrato_id = $contrato->id;
      $transporte->user_id = $supervisor->id;

      if($transporte = Auth::user()->empresa->transportes()->save($transporte)){
        return redirect('transportes/' . $transporte->id)->with([
          'flash_message' => 'Transporte agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('transportes/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function show(Transporte $transporte)
    {
      return view('transportes.show', ['transporte' => $transporte]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Transporte $transporte)
    {
      return view('transportes.edit', ['transporte' => $transporte]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transporte $transporte)
    {
      $this->validate($request, [
        'vehiculo' => 'required',
        'patente' => 'required',
      ]);

      $transporte->fill($request->all());

      if($transporte->save()){
        return redirect('transportes/' . $transporte->id)->with([
          'flash_message' => 'Transporte modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('transportes/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transporte $transporte)
    {
      if($transporte->delete()){
        $directory = 'Empresa' . Auth::user()->empresa_id . '/Transportes/' . $transporte->id;

        if(Storage::exists($directory)){
          Storage::deleteDirectory($directory);
        }

        return redirect('transportes')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Transporte eliminado exitosamente.'
        ]);
      }else{
        return redirect('transportes')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
