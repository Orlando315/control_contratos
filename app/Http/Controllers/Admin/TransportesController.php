<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\{Contrato, Transporte, User, TransporteContrato, Faena};

class TransportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Transporte::class);

      $transportes = Transporte::with('faena')->get();

      return view('admin.transportes.index', compact('transportes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', Transporte::class);

      $contratos = Contrato::all();
      $supervisores = User::supervisores()->get();
      $faenas = Faena::all();

      return view('admin.transportes.create', compact('contratos', 'supervisores', 'faenas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Transporte::class);
      $this->validate($request, [
        'supervisor' => 'required',
        'vehiculo' => 'required|string|max:50',
        'patente' => 'required|string|max:50',
      ]);

      $contrato = Contrato::findOrFail($request->contrato);
      $supervisor = User::findOrFail($request->supervisor);
      $transporte = new Transporte($request->only('vehiculo', 'patente'));
      $transporte->user_id = $supervisor->id;
      $transporte->faena_id = $request->faena;

      if(Auth::user()->empresa->transportes()->save($transporte)){
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
      $this->authorize('view', $transporte);

      $transporte->load('faena');
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
      $this->authorize('update', $transporte);

      $supervisores = User::supervisores()->get();
      $faenas = Faena::all();

      return view('admin.transportes.edit', compact('transporte', 'supervisores', 'faenas'));
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
      $this->authorize('update', $transporte);
      $this->validate($request, [
        'supervisor' => 'required',
        'vehiculo' => 'required|string|max:50',
        'patente' => 'required|string|max:50',
      ]);

      $transporte->fill($request->only('vehiculo', 'patente'));
      $transporte->user_id = $request->supervisor;
      $transporte->faena_id = $request->faena;

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
      $this->authorize('delete', $transporte);

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
      $this->authorize('update', $transporte);

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
      $this->authorize('update', $transporte);

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
