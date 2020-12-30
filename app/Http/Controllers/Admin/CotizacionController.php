<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\{Cotizacion, Cliente, Inventario, Direccion, Contacto};

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Cotizacion::class);

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
      $this->authorize('create', Cotizacion::class);

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
      $this->authorize('create', Cotizacion::class);
      $this->validate($request, [
        'cliente' => 'required',
        'direccion' => 'required',
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

      $cliente = Cliente::findOrFail($request->cliente);

      if($cliente->isEmpresa()){
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

      $direccion = Direccion::findOrFail($request->direccion);

      $cotizacion = new Cotizacion([
        'user_id' => Auth::id(),
        'cliente_id' => $cliente->id,
        'direccion' => $direccion->only('id', 'ciudad', 'comuna', 'direccion'),
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
      $this->authorize('view', $cotizacion);

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
      $this->authorize('update', $cotizacion);

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
      $this->authorize('update', $cotizacion);
      $this->validate($request, [
        'cliente' => 'required',
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


      $cliente = Cliente::findOrFail($request->cliente);

      if($cliente->isEmpresa()){
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

      $direccion = Direccion::findOrFail($request->direccion);

      $cotizacion->cliente_id = $request->cliente;
      $cotizacion->direccion = $direccion->only('id', 'ciudad', 'comuna', 'direccion');
      $cotizacion->contacto = $contacto;
      $cotizacion->notas = $request->notas;
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

      if($cotizacion->save()){
        if(count($productos) > 0){
          $cotizacion->productos()->createMany($productos);
        }

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
      $this->authorize('delete', $cotizacion);

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
