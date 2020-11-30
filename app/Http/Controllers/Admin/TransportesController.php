<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Contrato;
use App\Transporte;
use App\Usuario;
use App\TransporteContrato;

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

      return view('admin.transportes.index', ['transportes' => $transportes]);
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

      return view('admin.transportes.create', ['contratos' => $contratos, 'usuarios' => $usuarios]);
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

      $transporte = new Transporte($request->only('vehiculo', 'patente'));
      $transporte->user_id = $supervisor->id;

      if($transporte = Auth::user()->empresa->transportes()->save($transporte)){
        $transporte->contratos()->create(['contrato_id' => $contrato->id]);

        return redirect()->route('admin.transportes.show', ['transporte' => $transporte->id])->with([
          'flash_message' => 'Transporte agregado exitosamente.',
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
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function show(Transporte $transporte)
    {
      $contratosIds = $transporte->contratos()->pluck('contrato_id')->toArray();
      $otherContratos = Contrato::select('id', 'nombre')->whereNotIn('id', $contratosIds)->get();

      return view('admin.transportes.show', compact('transporte', 'otherContratos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Transporte $transporte)
    {
      return view('admin.transportes.edit', ['transporte' => $transporte]);
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

      $transporte->fill($request->only('vehiculo', 'patente'));

      if($transporte->save()){
        return redirect()->route('admin.transportes.show', ['transporte' => $transporte->id])->with([
          'flash_message' => 'Transporte modificado exitosamente.',
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

        return redirect()->route('admin.transportes.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Transporte eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    public function storeContratos(Request $request, Transporte $transporte)
    {
      $contrato = Contrato::findOrFail($request->contrato);

      if($transporte->contratos()->create(['contrato_id' => $contrato->id])){
        return redirect()->route('admin.transportes.show', ['transporte' => $transporte->id])->with([
          'flash_message' => 'Contrato agregado exitosamente.',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Transporte  $transporte
     * @return \Illuminate\Http\Response
     */
    public function destroyContratos(Transporte $transporte, TransporteContrato $contrato)
    {
      if($contrato->delete()){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contrato eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
