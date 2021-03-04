<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{RequerimientoMaterial, RequerimientoMaterialProducto};
use PDF;

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
      ->requerimientosMateriales()
      ->with(['contrato', 'faena', 'centroCosto', 'dirigidoA'])
      ->withCount('productos')
      ->latest()
      ->get();

      return view('requerimiento-material.index', compact('requerimientosMateriales'));
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

      return view('requerimiento-material.create', compact('contratos', 'faenas', 'centrosCostos', 'usuarios', 'inventariosV2'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', RequerimientoMaterial::class);
      $this->validate($request, [
        'contrato' => 'required',
        'faena' => 'nullable',
        'centro_costo' => 'nullable',
        'dirigido' => 'required',
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

        return redirect()->route('requerimiento.material.show', ['requerimiento' => $requerimiento->id])->with([
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
        'contrato',
        'faena',
        'centroCosto',
        'dirigidoA',
        'firmantes',
        'productos.inventario',
      ]);

      return view('requerimiento-material.show', compact('requerimiento'));
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

      return view('requerimiento-material.edit', compact('requerimiento', 'contratos', 'faenas', 'centrosCostos', 'usuarios', 'inventariosV2'));
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
        'productos' => 'required|min:1',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
      ]);

      $requerimiento->contrato_id = $request->contrato;
      $requerimiento->faena_id = $request->faena;
      $requerimiento->centro_costo_id = $request->centro_costo;
      $requerimiento->dirigido = $request->dirigido;
      $productos = [];

      foreach(($request->productos ?? []) as $producto){
        $productos[] = [
          'inventario_id' => $producto['inventario'],
          'nombre' => $producto['nombre'],
          'cantidad' => $producto['cantidad'],
        ];
      }

      if($requerimiento->save()){
        if(count($productos) > 0){
          $requerimiento->productos()->createMany($productos);
        }

        return redirect()->route('requerimiento.material.show', ['requerimiento' => $requerimiento->id])->with([
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
        return redirect()->back()->with([
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

    /**
     * Aprobar/Rechazar RequermientoMaterial por el User en sesion
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequerimientoMaterial $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, RequerimientoMaterial $requerimiento)
    {
      $this->authorize('approve', $requerimiento);

      $firmante = $requerimiento->sessionFirmante;
      $firmante->status = $request->status == 1;

      if($firmante->save()){
        $requerimiento->updateStatus();

        return redirect()->back()->with([
          'flash_message' => 'Estatus de aprobación actualizado exitosamente.',
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
     * Display the specified resource.
     *
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function pdf(RequerimientoMaterial $requerimiento)
    {
      $this->authorize('view', $requerimiento);

      $requerimiento->load([
        'contrato',
        'faena',
        'centroCosto',
        'dirigidoA',
        'firmantes',
        'productos.inventario',
      ]);

      PDF::setOptions(['dpi' => 150]);
      $pdf = PDF::loadView('requerimiento-material.pdf', compact('requerimiento'));

      return $pdf->download('Requerimiento-de-materiales.pdf');
    }
}
