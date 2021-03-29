<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{OrdenCompra, Proveedor, InventarioV2, Contacto, RequerimientoMaterial};
use PDF;

class OrdenCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', OrdenCompra::class);

      $compras = OrdenCompra::all();

      return view('admin.compra.index', compact('compras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function create(Proveedor $proveedor = null)
    {
      $this->authorize('create', OrdenCompra::class);

      $proveedores = Proveedor::all();
      $inventarios = InventarioV2::with('unidad')->get();
      $selectedProveedor = $proveedor;

      return view('admin.compra.create', compact('proveedores', 'inventarios', 'selectedProveedor'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', OrdenCompra::class);
      $this->validate($request, [
        'proveedor' => 'required',
        'notas' => 'nullable|string|max:350',
        'productos' => 'required|min:1',
        'productos.*.tipo_codigo' => 'nullable|string|max:20',
        'productos.*.codigo' => 'nullable|string|max:50',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
        'productos.*.precio' => 'required|numeric|min:1|max:99999999',
        'productos.*.descripcion' => 'nullable|string|max:200',
      ]);

      $proveedor = Proveedor::findOrFail($request->proveedor);

      if($proveedor->isEmpresa()){
        $this->validate($request, [
          'contacto' => 'required',
        ]);

        $contacto = Contacto::findOrFail($request->contacto)->only('id', 'nombre', 'telefono', 'email', 'cargo', 'descripcion');
      }else{
        $this->validate($request, [
          'nombre' => 'required|string|max:100',
          'telefono' => 'required|string|max:20',
          'email' => 'nullable|email|max:50',
        ]);
        $contacto = $request->only('nombre', 'telefono', 'email');
      }

      $compra = new OrdenCompra([
        'user_id' => Auth::id(),
        'proveedor_id' => $proveedor->id,
        'contacto' => $contacto,
        'notas' => $request->notas,
      ]);
      $productos = [];

      foreach ($request->productos as $producto){
        $data = collect($producto)->except('inventario', 'precio', 'iva')->toArray();
        $data['precio'] = round($producto['precio_total'] / $producto['cantidad'], 2);
        $data['impuesto_adicional'] = $producto['iva'];
        $data['inventario_id'] = $producto['inventario'];
        $productos[] = $data;
      }

      if(Auth::user()->empresa->compras()->save($compra)){
        $compra->productos()->createMany($productos);

        return redirect()->route('admin.compra.show', ['compra' => $compra->id])->with([
            'flash_message' => 'Orden de compra agregada exitosamente.',
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
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenCompra $compra)
    {
      $this->authorize('view', $compra);

      $compra->load([
        'productos',
        'requerimiento' => function ($query) {
          $query->with([
            'userSolicitante',
            'dirigidoA',
          ]);
        },
      ]);

      return view('admin.compra.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenCompra $compra)
    {
      $this->authorize('update', $compra);

      $compra->load('productos');
      $proveedores = Proveedor::all();
      $inventarios = InventarioV2::with('unidad')->get();

      return view('admin.compra.edit', compact('compra', 'proveedores', 'inventarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdenCompra $compra)
    {
      $this->authorize('update', $compra);
      $this->validate($request, [
        'proveedor' => 'required',
        'notas' => 'nullable|string|max:350',
        'productos' => 'nullable',
        'productos.*.tipo_codigo' => 'nullable|string|max:20',
        'productos.*.codigo' => 'nullable|string|max:50',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
        'productos.*.precio' => 'required|numeric|min:1|max:99999999',
        'productos.*.descripcion' => 'nullable|string|max:200',
      ]);


      $proveedor = Proveedor::findOrFail($request->proveedor);

      if($proveedor->isEmpresa()){
        $this->validate($request, [
          'contacto' => 'required',
        ]);

        $contacto = Contacto::findOrFail($request->contacto)->only('id', 'nombre', 'telefono', 'email', 'cargo', 'descripcion');
      }else{
        $this->validate($request, [
          'nombre' => 'required|string|max:100',
          'telefono' => 'required|string|max:20',
          'email' => 'nullable|email|max:50',
        ]);
        $contacto = $request->only('nombre', 'telefono', 'email');
      }

      $compra->proveedor_id = $request->proveedor;
      $compra->contacto = $contacto;
      $compra->notas = $request->notas;
      $productos = [];

      if($request->has('productos')){
        foreach ($request->productos as $producto){
          $data = collect($producto)->except('inventario', 'precio', 'iva')->toArray();
          $data['precio'] = round($producto['precio_total'] / $producto['cantidad'], 2);
          $data['impuesto_adicional'] = $producto['iva'];
          $data['inventario_id'] = $producto['inventario'];
          $productos[] = $data;
        }
      }

      if($compra->save()){
        if(count($productos) > 0){
          $compra->productos()->createMany($productos);
        }

        return redirect()->route('admin.compra.show', ['compra' => $compra->id])->with([
            'flash_message' => 'Orden de compra modificada exitosamente.',
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
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdenCompra $compra)
    {
      $this->authorize('delete', $compra);

      if($compra->delete()){
        return redirect()->route('admin.compra.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Orden de compra eliminada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Obtener los productos de una OrdenCompra especificada
     * 
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function productos(OrdenCompra $compra)
    {
      return response()->json(['productos' => $compra->productos]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrdenCompra  $compra
     * @return \Illuminate\Http\Response
     */
    public function pdf(OrdenCompra $compra)
    {
      $this->authorize('view', $compra);

      $compra->load(['productos']);
      PDF::setOptions(['dpi' => 150]);
      $pdf = PDF::loadView('admin.compra.pdf', compact('compra'));

      return $pdf->download('Orden de compra '.$compra->id.'.pdf');
    }

    /**
     * Formulario para generar una Orden de Compra en base al
     * Reuqerimiento de Materiales especificado
     * 
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function requerimiento(RequerimientoMaterial $requerimiento)
    {
      $this->authorize('compra', $requerimiento);
      $this->authorize('create', OrdenCompra::class);

      $requerimiento->load('productos');
      $proveedores = Proveedor::all();

      return view('admin.compra.requerimiento', compact('requerimiento', 'proveedores'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequerimientoMaterial  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function storeRequerimiento(Request $request, RequerimientoMaterial $requerimiento)
    {
      $this->authorize('compra', $requerimiento);
      $this->authorize('create', OrdenCompra::class);
      $this->validate($request, [
        'productos.*.proveedor' => 'required',
        'productos.*.tipo_codigo' => 'nullable|string|max:20',
        'productos.*.codigo' => 'nullable|string|max:50',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
        'productos.*.precio' => 'required|numeric|min:1|max:99999999',
        'productos.*.descripcion' => 'nullable|string|max:200',
      ]);

      $proveedoresIds = collect($request->productos)->pluck('proveedor')->unique()->toArray();

      foreach ($proveedoresIds as $proveedorId) {
        $proveedor = Proveedor::find($proveedorId);

        if($proveedor->isEmpresa()){
          $seleccionado = $proveedor->contactos()->selected()->first();
          $contacto = $seleccionado ? $seleccionado->only('id', 'nombre', 'telefono', 'email', 'cargo', 'descripcion') : [];
        }else{
          $contacto = $proveedor->only('nombre', 'telefono', 'email');
        }

        $compra = new OrdenCompra([
          'user_id' => Auth::id(),
          'proveedor_id' => $proveedor->id,
          'requerimiento_id' => $requerimiento->id,
          'contacto' => $contacto,
        ]);
        $productos = [];
        $filteredProductos = collect($request->productos)->where('proveedor', $proveedorId);

        foreach ($filteredProductos as $producto){
          $data = collect($producto)->except('inventario', 'precio', 'iva')->toArray();
          $data['precio'] = round($producto['precio_total'] / $producto['cantidad'], 2);
          $data['impuesto_adicional'] = $producto['iva'];
          $productos[] = $data;
        }

        if(Auth::user()->empresa->compras()->save($compra)){
          $compra->productos()->createMany($productos);
        }
      }

      return redirect()->route('admin.requerimiento.material.show', ['requerimiento' => $requerimiento->id])->with([
        'flash_message' => 'Ordenes de compra generadas exitosamente.',
        'flash_class' => 'alert-success'
      ]);
    }

}
