<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{InventarioV2, Unidad, Etiqueta};
use App\Exports\{InventarioV2Export, InventarioV2ImportTemplate};
use App\Imports\InventarioV2Import;
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

      $inventarios = InventarioV2::all();
      $unidades = Unidad::withCount('inventariosV2')->get();

      return view('admin.inventarioV2.index', compact('inventarios', 'unidades'));
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

      return view('admin.inventarioV2.create', compact('unidades', 'categorias'));
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
        'codigo' => 'nullable|string|max:50',
        'stock_minimo' => 'nullable|numeric|min:0|max:9999',
        'categorias' => 'nullable',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $inventario = new InventarioV2($request->only('nombre', 'codigo', 'stock_minimo', 'descripcion'));
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

      $inventario->load('unidad', 'categorias', 'ingresos.proveedor');

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

      $inventario->load('categorias');
      $unidades = Unidad::all();
      $categorias = Etiqueta::all();

      return view('admin.inventarioV2.edit', compact('inventario', 'unidades', 'categorias'));
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
        'codigo' => 'nullable|string|max:50',
        'stock_minimo' => 'nullable|numeric|min:0|max:9999',
        'descripcion' => 'nullable|string|max:250',
        'foto' => 'nullable|file|mimes:jpeg,png|max:3000',
      ]);

      $inventario->fill($request->only('nombre', 'compact', 'stock_minimo', 'descripcion'));
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
}
