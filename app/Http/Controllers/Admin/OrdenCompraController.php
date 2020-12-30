<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{OrdenCompra, Proveedor, Inventario, Contacto};

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
      $inventarios = Inventario::all();
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
        'productos.*.impuesto' => 'nullable|numeric|min:0|max:99999999',
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
        $data = collect($producto)->except('inventario', 'impuesto', 'total')->toArray();
        $data['impuesto_adicional'] = $producto['impuesto'];
        $data['total'] = ($producto['cantidad'] * $producto['precio']) + $producto['impuesto'];
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

      $compra->load(['productos']);

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
      $inventarios = Inventario::all();

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
        'productos.*.impuesto' => 'nullable|numeric|min:0|max:99999999',
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
          $data = collect($producto)->except('inventario', 'impuesto', 'total')->toArray();
          $data['impuesto_adicional'] = $producto['impuesto'];
          $data['total'] = ($producto['cantidad'] * $producto['precio']) + $producto['impuesto'];
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
}
