<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{RequerimientoMaterial, RequerimientoMaterialProducto};

class RequerimientoMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', RequerimientoMaterial::class);

      $requerimientosMateriales = Auth::user()
      ->empresa
      ->requerimientosMateriales()
      ->with(['contrato', 'faena', 'centroCosto', 'dirigidoA'])
      ->withCount('productos')
      ->latest()
      ->get();

      return view('admin.requerimiento-material.index', compact('requerimientosMateriales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', RequerimientoMaterial::class);

      $contratos = Auth::user()->empresa->contratos;
      $faenas = Auth::user()->empresa->faenas;
      $centrosCostos = Auth::user()->empresa->centros;
      $usuarios = Auth::user()->empresa->users;
      $inventariosV2 = Auth::user()->empresa->inventariosV2;

      return view('admin.requerimiento-material.create', compact('contratos', 'faenas', 'centrosCostos', 'usuarios', 'inventariosV2'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', RequermientoMaterial::class);
      $this->validate($request, [
        'contrato' => 'required',
        'faena' => 'nullable',
        'centro_costo' => 'nullable',
        'dirigido' => 'required',
        'fecha' => 'nullable|date_format:d-m-Y',
        'urgencia' => 'nullable|in:normal,urgente',
        'productos' => 'required|min:1',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
      ]);

      if(!Auth::user()->empresa->configuracion->hasFirmantes()){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No hay Usuarios firmantes configurados.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      $requerimiento = new RequerimientoMaterial([
        'contrato_id' => $request->contrato,
        'faena_id' => $request->faena,
        'centro_costo_id' => $request->centro_costo,
        'solicitante' => Auth::id(),
        'dirigido' => $request->dirigido,
        'fecha' => $request->fecha,
        'urgencia' => $request->urgencia,
      ]);
      $requerimiento->empresa_id = Auth::user()->empresa->id;
      $productos = [];

      foreach ($request->productos as $producto){
        $productos[] = [
          'inventario_id' => $producto['inventario'],
          'nombre' => $producto['nombre'],
          'cantidad' => $producto['cantidad'],
        ];
      }

      if(Auth::user()->requerimientosMateriales()->save($requerimiento)){
        $requerimiento->productos()->createMany($productos);
        $requerimiento->createFirmantes();

        return redirect()->route('admin.requerimiento.material.show', ['requerimiento' => $requerimiento->id])->with([
          'flash_message' => 'Requerimiento de Materiales agregado exitosamente.',
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
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(RequerimientoMaterial $requerimiento)
    {
      $this->authorize('view', $requerimiento);

      $requerimiento->load([
        'userSolicitante',
        'contrato',
        'faena',
        'centroCosto',
        'dirigidoA',
        'firmantes',
        'productos.inventario',
        'logs' => function ($query) {
          $query->ofType('firmante');
        },
        'compras' => function ($query) {
          $query->with('proveedor');
        },
      ]);

      return view('admin.requerimiento-material.show', compact('requerimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(RequerimientoMaterial $requerimiento)
    {
      $this->authorize('update', $requerimiento);

      $requerimiento->load([
        'productos',
      ]);
      $contratos = Auth::user()->empresa->contratos;
      $faenas = Auth::user()->empresa->faenas;
      $centrosCostos = Auth::user()->empresa->centros;
      $usuarios = Auth::user()->empresa->users;
      $inventariosV2 = Auth::user()->empresa->inventariosV2;

      return view('admin.requerimiento-material.edit', compact('requerimiento', 'contratos', 'faenas', 'centrosCostos', 'usuarios', 'inventariosV2'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequerimientoMaterial $requerimiento)
    {
      $this->authorize('update', $requerimiento);
      $this->validate($request, [
        'contrato' => 'required',
        'faena' => 'nullable',
        'centro_costo' => 'nullable',
        'dirigido' => 'required',
        'fecha' => 'nullable|date_format:d-m-Y',
        'urgencia' => 'nullable|in:normal,urgente',
        'productos' => 'nullable|min:0',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
      ]);

      $requerimiento->load([
        'contrato',
        'faena',
        'centroCosto',
        'dirigidoA',
      ]);
      $cloneRequerimiento = $requerimiento->replicate();

      $requerimiento->contrato_id = $request->contrato;
      $requerimiento->faena_id = $request->faena;
      $requerimiento->centro_costo_id = $request->centro_costo;
      $requerimiento->dirigido = $request->dirigido;
      $requerimiento->fecha = $request->fecha;
      $requerimiento->urgencia = $request->urgencia;
      $productos = [];
      $wasAdded = $requerimiento->userIsFirmante();

      foreach(($request->productos ?? []) as $producto){
        $productos[] = [
          'inventario_id' => $producto['inventario'],
          'nombre' => $producto['nombre'],
          'cantidad' => $producto['cantidad'],
          'added' => $wasAdded,
        ];
      }

      if($requerimiento->save()){
        $requerimiento->refresh();
        $requerimiento->logAction('update', $cloneRequerimiento);

        if(count($productos) > 0){
          $requerimiento->productos()->createMany($productos);
          $requerimiento->logAction('add', $productos);
        }

        return redirect()->route('admin.requerimiento.material.show', ['requerimiento' => $requerimiento->id])->with([
          'flash_message' => 'Requerimiento de Materiales modificado exitosamente.',
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
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequerimientoMaterial $requerimiento)
    {
      $this->authorize('delete', $requerimiento);

      if($requerimiento->delete()){
        return redirect()->route('admin.requerimiento.material.index')->with([
          'flash_message' => 'Requerimiento de Materiales eliminado exitosamente.',
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
     * @param  \App\RequerimientoMaterialProducto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroyProducto(RequerimientoMaterialProducto $producto)
    {
      $this->authorize('update', $producto->requerimiento);


      if($producto->delete()){
        $producto->requerimiento->logAction('delete', $producto);

        return redirect()->back()->with([
          'flash_message' => 'Producto eliminado exitosamente.',
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
