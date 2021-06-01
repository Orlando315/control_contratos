<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{InventarioV2, Unidad, Etiqueta, Bodega};
use App\Exports\{InventarioV2Export, InventarioV2ImportTemplate, InventarioV2UpdateTemplate};
use App\Imports\{InventarioV2Import, InventarioV2Update};
use Excel;

class InventarioV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', InventarioV2::class);

      $inventarios = InventarioV2::with([
        'bodegas',
        'ubicaciones',
      ])->get();
      $unidades = Unidad::withCount('inventariosV2')->get();
      $bodegas = Bodega::withCount([
        'inventariosV2',
        'ubicaciones',
      ])->get();

      return view('admin.inventarioV2.index', compact('inventarios', 'unidades', 'bodegas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', InventarioV2::class);

      $unidades = Unidad::all();
      $categorias = Etiqueta::all();
      $bodegas = Bodega::select('id', 'nombre')->has('ubicaciones')->get();

      return view('admin.inventarioV2.create', compact('unidades', 'categorias', 'bodegas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', InventarioV2::class);
      $this->validate($request, [
        'unidad' => 'required',
        'nombre' => 'required|string|max:50',
        'tipo_codigo' => 'nullable|string|max:6',
        'codigo' => 'nullable|string|max:8',
        'stock_minimo' => 'nullable|numeric|min:0|max:9999',
        'categorias' => 'nullable',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $inventario = new InventarioV2($request->only('nombre', 'tipo_codigo', 'codigo', 'stock_minimo', 'descripcion'));
      $inventario->unidad_id = $request->unidad;

      if(Auth::user()->empresa->inventariosV2()->save($inventario)){
        if($request->hasFile('foto')){
          $directory = $inventario->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $inventario->foto = $request->file('foto')->store($directory);
          $inventario->save();
        }

        if($request->has('categorias')){
          $inventario->categorias()->attach($request->categorias);
        }

        if(!empty(array_filter($request->bodegas))){
          $inventario->bodegas()->attach(array_unique($request->bodegas));
        }

        if($request->has('ubicaciones')){
          $inventario->ubicaciones()->attach(array_unique($request->ubicaciones));
        }

        return redirect()->route('admin.inventario.v2.show', ['inventario' => $inventario->id])->with([
          'flash_message' => 'Inventario agregado exitosamente.',
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
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(InventarioV2 $inventario)
    {
      $this->authorize('view', $inventario);

      $inventario->load([
        'unidad',
        'bodegas',
        'ubicaciones' => function ($query) {
          $query->with([
            'bodega'
          ]);
        },
        'categorias',
        'ingresos.proveedor',
        'egresos' => function ($query) {
          $query->with([
            'cliente',
            'user',
          ]);
        },
      ]);

      return view('admin.inventarioV2.show', compact('inventario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(InventarioV2 $inventario)
    {
      $this->authorize('update', $inventario);

      $inventario->load([
        'categorias',
        'bodegas',
      ]);
      $unidades = Unidad::all();
      $categorias = Etiqueta::all();
      $bodegas = Bodega::select('id', 'nombre')->has('ubicaciones')->get();
      $inventarioUbicaciones = $inventario
      ->ubicaciones()
      ->select('ubicacion_id')
      ->get()
      ->pluck('ubicacion_id')
      ->toArray();

      return view('admin.inventarioV2.edit', compact('inventario', 'unidades', 'categorias', 'bodegas', 'inventarioUbicaciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventarioV2 $inventario)
    {
      $this->authorize('update', $inventario);
      $this->validate($request, [
        'unidad' => 'required',
        'nombre' => 'required|string|max:50',
        'tipo_codigo' => 'nullable|string|max:6',
        'codigo' => 'nullable|string|max:8',
        'stock_minimo' => 'nullable|numeric|min:0|max:9999',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $inventario->fill($request->only('nombre', 'tipo_codigo', 'codigo', 'stock_minimo', 'descripcion'));
      $inventario->unidad_id = $request->unidad;

      if($inventario->save()){
        if($request->hasFile('foto')){
          $directory = $inventario->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          // Si ya tine una foto, eliminarlo
          if($inventario->foto){
            Storage::delete($inventario->foto);
          }

          $inventario->foto = $request->file('foto')->store($directory);
          $inventario->save();
        }

        $inventario->categorias()->sync($request->categorias ?? []);
        $inventario->bodegas()->sync(array_unique($request->bodegas ?? []));
        $inventario->ubicaciones()->sync(array_unique($request->ubicaciones ?? []));

        return redirect()->route('admin.inventario.v2.show', ['inventario' => $inventario->id])->with([
          'flash_message' => 'Inventario modificado exitosamente.',
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function ajustarStock(Request $request, InventarioV2 $inventario)
    {
      $this->authorize('update', $inventario);
      $this->validate($request, [
        'cantidad' => 'required|numeric|min:0|max:9999',
      ]);

      $inventario->stock = $request->cantidad;

      if($inventario->save()){
        return redirect()->route('admin.inventario.v2.show', ['inventario' => $inventario->id])->with([
          'flash_message' => 'Stock ajustado exitosamente.',
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
     * @param  \App\InventarioV2  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventarioV2 $inventario)
    {
      $this->authorize('delete', $inventario);

      if($inventario->delete()){
        if($inventario->foto){
          Storage::deleteDirectory($inventario->directory);
        }

        return redirect()->route('admin.inventario.v2.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Inventario eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Mostrar formulario para importar InventarioV2
     * 
     * @return \Illuminate\Http\Response
     */
    public function importCreate()
    {
      $this->authorize('create', InventarioV2::class);

      return view('admin.inventarioV2.import');
    }

    /**
     * Importar InventarioV2 por excel
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importStore(Request $request)
    {
      $this->authorize('create', InventarioV2::class);
      $this->validate($request, [
        'archivo' => 'required|file|mimes:xlsx,xls',
      ]);

      try{
        $excel = Excel::import(new InventarioV2Import, $request->archivo);

        return redirect()->route('admin.inventario.v2.index')->with([
          'flash_message' => 'Inventario importado exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }catch(\Exception $e){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'Ha ocurrido un error. Revise el formato utilizado o los datos suministrados.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }
    }

    /**
     * Descargar plantilla para importar InventarioV2
     * 
     * @return \Illuminate\Http\Response
     */
    public function importTemplate()
    {
      $this->authorize('create', InventarioV2::class);

      return (new InventarioV2ImportTemplate)->download('importar_inventario.xlsx');
    }

    /**
     * Exportar InventariosV2
     * 
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
      return (new InventarioV2Export)->download('inventario.xlsx');
    }

    /**
     * Mostrar formulario para editar masivamente InventarioV2
     * 
     * @return \Illuminate\Http\Response
     */
    public function massEdit()
    {
      $this->authorize('create', InventarioV2::class);

      return view('admin.inventarioV2.massEdit');
    }

    /**
     * Actualizar InventarioV2 con el excel proporcionado
     * 
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(Request $request)
    {
      $this->authorize('create', InventarioV2::class);
      $this->validate($request, [
        'archivo' => 'required|file|mimes:xlsx,xls',
      ]);

      try{
        $excel = Excel::import(new InventarioV2Update, $request->archivo);

        return redirect()->route('admin.inventario.v2.index')->with([
          'flash_message' => 'Inventario actualizado exitosamente.',
          'flash_class' => 'alert-success'
        ]);
      }catch(\Exception $e){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'Ha ocurrido un error. Revise el formato utilizado o los datos suministrados.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }
    }

    /**
     * Descargar plantilla para actualizar InventarioV2 de forma masiva
     * 
     * @return \Illuminate\Http\Response
     */
    public function massTemplate()
    {
      $this->authorize('create', InventarioV2::class);

      return (new InventarioV2UpdateTemplate)->download('actualizar_inventario.xlsx');
    }
}
