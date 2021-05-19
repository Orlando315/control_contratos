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

      $transportes = Transporte::all();

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
        'patente' => 'required|string|max:50',
        'contratos' => 'nullable',
        'supervisores' => 'nullable',
        'faenas' => 'nullable',
        'modelo' => 'nullable|string|max:50',
        'marca' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:50',
        'descripcion' => 'nullable|string|max:100',
      ]);

      $transporte = new Transporte($request->only('patente', 'modelo', 'marca', 'color'));
      $transporte->vehiculo = $request->descripcion;

      if(Auth::user()->empresa->transportes()->save($transporte)){
        if($request->has('contratos')){
          $transporte->parentContratos()->attach($request->contratos);
        }

        if($request->has('supervisores')){
          $transporte->supervisores()->attach($request->supervisores);
        }

        if($request->has('faenas')){
          $transporte->faenas()->attach($request->faenas);
        }

        return redirect()->route('admin.transporte.show', ['transporte' => $transporte->id])->with([
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

      $transporte->load([
        'contratos',
        'supervisores',
        'faenas',
      ]);
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

      $transporte->load([
        'contratos',
        'supervisores',
        'faenas',
      ]);

      $contratos = Contrato::all();
      $supervisores = User::supervisores()->get();
      $faenas = Faena::all();

      return view('admin.transportes.edit', compact('transporte', 'contratos', 'supervisores', 'faenas'));
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
        'patente' => 'required|string|max:50',
        'contratos' => 'nullable',
        'supervisores' => 'nullable',
        'faenas' => 'nullable',
        'modelo' => 'nullable|string|max:50',
        'marca' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:50',
        'descripcion' => 'nullable|string|max:100',
      ]);

      $transporte->fill($request->only('patente', 'modelo', 'marca', 'color'));
      $transporte->vehiculo = $request->descripcion;

      if($transporte->save()){
        $transporte->parentContratos()->sync($request->contratos ?? []);
        $transporte->supervisores()->sync($request->supervisores ?? []);
        $transporte->faenas()->sync($request->faenas ?? []);

        return redirect()->route('admin.transporte.show', ['transporte' => $transporte->id])->with([
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

        return redirect()->route('admin.transporte.index')->with([
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
        return redirect()->route('admin.transporte.show', ['transporte' => $transporte->id])->with([
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
     * @param  \App\TransporteContrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroyContratos(TransporteContrato $contrato)
    {
      $this->authorize('update', $contrato->transporte);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function destroySupervisor(Transporte $transporte, User $supervisor)
    {
      $this->authorize('update', $transporte);

      if($transporte->supervisores()->detach($supervisor->id)){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Supervisor eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
