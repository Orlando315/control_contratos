<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{Cotizacion, Cliente, Inventario};

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $cotizaciones = Cotizacion::all();

      return view('admin.cotizacion.index', compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function create(Cliente $cliente = null)
    {
      $clientes = Cliente::all();
      $inventarios = Inventario::all();
      $selectedCliente = $cliente;

      return view('admin.cotizacion.create', compact('clientes', 'inventarios', 'selectedCliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'cliente' => 'required',
        'productos' => 'required|min:1',
        'productos.*.tipo_codigo' => 'required|string|max:20',
        'productos.*.codigo' => 'required|string|max:50',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
        'productos.*.precio' => 'required|numeric|min:1|max:99999999',
        'productos.*.impuesto' => 'nullable|numeric|min:0|max:99999999',
        'productos.*.descripcion' => 'nullable|string|max:200',
      ]);

      $cotizacion = new Cotizacion([
        'user_id' => Auth::id(),
        'cliente_id' => $request->cliente,
      ]);
      $productos = [];

      foreach ($request->productos as $producto){
        $data = collect($producto)->except('inventario', 'impuesto', 'total')->toArray();
        $data['impuesto_adicional'] = $producto['impuesto'];
        $data['total'] = ($producto['cantidad'] * $producto['precio']) + $producto['impuesto'];
        $data['inventario_id'] = $producto['inventario'];
        $productos[] = $data;
      }

      if(Auth::user()->empresa->cotizaciones()->save($cotizacion)){
        $cotizacion->productos()->createMany($productos);

        return redirect()->route('admin.cotizacion.show', ['cotizacion' => $cotizacion->id])->with([
            'flash_message' => 'Cotización agregada exitosamente.',
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
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function show(Cotizacion $cotizacion)
    {
      $cotizacion->load(['productos', 'facturacion']);

      return view('admin.cotizacion.show', compact('cotizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Cotizacion $cotizacion)
    {
      $cotizacion->load('productos');
      $clientes = Cliente::all();
      $inventarios = Inventario::all();

      return view('admin.cotizacion.edit', compact('cotizacion', 'clientes', 'inventarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
      $this->validate($request, [
        'cliente' => 'required',
        'productos' => 'required|min:1',
        'productos.*.tipo_codigo' => 'required|string|max:20',
        'productos.*.codigo' => 'required|string|max:50',
        'productos.*.nombre' => 'required|max:100',
        'productos.*.cantidad' =>  'required|integer|min:1|max:99999',
        'productos.*.precio' => 'required|numeric|min:1|max:99999999',
        'productos.*.impuesto' => 'nullable|numeric|min:0|max:99999999',
        'productos.*.descripcion' => 'nullable|string|max:200',
      ]);

      $cotizacion->cliente_id = $request->cliente;
      $productos = [];

      foreach ($request->productos as $producto){
        $data = collect($producto)->except('inventario', 'impuesto', 'total')->toArray();
        $data['impuesto_adicional'] = $producto['impuesto'];
        $data['total'] = ($producto['cantidad'] * $producto['precio']) + $producto['impuesto'];
        $data['inventario_id'] = $producto['inventario'];
        $productos[] = $data;
      }

      if($cotizacion->save()){
        $cotizacion->productos()->createMany($productos);

        return redirect()->route('admin.cotizacion.show', ['cotizacion' => $cotizacion->id])->with([
            'flash_message' => 'Cotización modificada exitosamente.',
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
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotizacion $cotizacion)
    {
      if($cotizacion->delete()){
        return redirect()->route('admin.cotizacion.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Cotización eliminada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Obtener los productos de una Cotizacion especificada
     * 
     * @param  \App\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function productos(Cotizacion $cotizacion)
    {
      return response()->json(['productos' => $cotizacion->productos]);
    }
}
