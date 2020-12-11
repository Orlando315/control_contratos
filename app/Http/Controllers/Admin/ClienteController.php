<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Cliente, Proveedor};

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $clientes = Auth::user()->empresa->clientes;

      return view('admin.cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
      if($type != 'persona' && $type != 'empresa'){
        abort(404);
      }

      return view('admin.cliente.create.'.$type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type)
    {
      if($type != 'persona' && $type != 'empresa'){
        abort(404);
      }

      $method = 'store'.ucfirst($type);

      return $this->{$method}($request);
    }

    /**
     * Almacenar un nuevo Cliente de tipo Persona.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function storePersona(Request $request)
    {
      $this->validate($request, [
        'nombre' => 'required|string|max:100',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/',
        'telefono' => 'required|string|max:20',
        'email' => 'nullable|email|max:50',
        'ciudad' => 'nullable|string|max:50',
        'comuna' => 'nullable|string|max:50',
        'direccion' => 'nullable|string|max:200',
        'descripcion' => 'nullable|string|max:200',
        'proveedor' => 'nullable|boolean',
      ]);

      if(Cliente::where('rut', $request->rut)->exists()){
        if($request->ajax()){
          return response()->json(['errors' => ['Ya existe un cliente registrado con ese RUT.']], 422);
        }

        return redirect()->back()->withInput()->withErrors('Ya existe un cliente registrado con ese RUT.');
      }

      $createProveedor = $request->has('proveedor') && $request->proveedor == '1';
      $proveedor = $createProveedor ? Proveedor::where('rut', $request->rut)->first() : null;

      $cliente = new Cliente($request->only('nombre', 'rut', 'telefono', 'email', 'descripcion'));
      $cliente->type = 'persona';

      if(Auth::user()->empresa->clientes()->save($cliente)){
        if($createProveedor){
          // Si no existe un proveedor, se crea
          if(is_null($proveedor)){
            $proveedor = new Proveedor($cliente->toArray());
            $proveedor->cliente_id = $cliente->id;
            Auth::user()->empresa->proveedores()->save($proveedor); 
          }

          $cliente->proveedor_id = optional($proveedor)->id;
          $cliente->save();
        }

        if($request->has('direccion') && $request->direccion){
          $direccion = [
            'ciudad' => $request->ciudad,
            'comuna' => $request->comuna,
            'direccion' => $request->direccion,
            'status' => true,
          ];

          $cliente->direcciones()->create($direccion);

          if($createProveedor){
            $proveedor->direcciones()->create($direccion);
          }
        }

        if($request->ajax()){
          return response()->json(['response' =>  true, 'cliente' => $cliente]);
        }

        return redirect()->route('admin.cliente.show', ['cliente' => $cliente->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Cliente agregado exitosamente.',
        ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Almacenar un nuevo Cliente tipo Emprsa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function storeEmpresa(Request $request)
    {
      $this->validate($request, [
        'rut' => 'required|regex:/^(\d{4,9})$/',
        'digito_validador' => 'required|regex:/^([\dkK])$/',
        'contactos.*.nombre' => 'sometimes|required|string|max:100',
        'contactos.*.telefono' => 'sometimes|required|string|max:20',
        'contactos.*.email' => 'sometimes|nullable|email|max:50',
        'contactos.*.cargo' => 'sometimes|nullable|string|max:50',
        'contactos.*.descripcion' => 'sometimes|nullable|string|max:100',
        'proveedor' => 'nullable|boolean',
      ]);

      if(Auth::user()->empresa->configuracion->isIntegrationIncomplete('sii')){
        return redirect()->back()->withInput()->withErrors('!Error! Integración incompleta.');
      }

      $createProveedor = $request->has('proveedor') && $request->proveedor == '1';
      $rut = $request->rut.'-'.$request->digito_validador;
      $proveedor = $createProveedor ? Proveedor::where('rut', $rut)->first() : null;

      if(Cliente::where('rut', $rut)->exists()){
        if($request->ajax()){
          return response()->json(['errors' => ['Ya existe un cliente registrado con ese RUT.']], 422);
        }

        return redirect()->back()->withInput()->withErrors('Ya existe un cliente registrado con ese RUT.');
      }

      [$response, $data] = Auth::user()->empresa->configuracion->getEmpresaFromSii($request->rut, $request->digito_validador);

      if(!$response){
        return redirect()->back()->withInput()->withErrors($data);
      }

      $cliente = new Cliente;
      $cliente->rut = $rut;
      $cliente->type = 'empresa';
      $cliente->nombre = $data['razon_social'];

      if(Auth::user()->empresa->clientes()->save($cliente)){
        if($createProveedor){
          // Si no existe un proveedor, se crea
          if(is_null($proveedor)){
            $proveedor = new Proveedor($cliente->toArray());
            $proveedor->cliente_id = $cliente->id;
            Auth::user()->empresa->proveedores()->save($proveedor);
          }

          $cliente->proveedor_id = optional($proveedor)->id;
          $cliente->save();
        }

        if($request->has('contactos')){
          $cliente->contactos()->createMany($request->contactos);

          if($createProveedor){
            $proveedor->contactos()->createMany($request->contactos);
          }
        }

        $direcciones = [];
        $direcciones[] = [
          'ciudad' => $data['ciudad_seleccionada'],
          'comuna' => $data['comuna_seleccionada'],
          'direccion' => $data['direccion_seleccionada'],
          'status' => true,
        ];

        if(isset($data['direcciones']) && count($data['direcciones']) > 0){
          foreach ($data['direcciones'] as $direccion){
            $value = array_values($direccion)[0];

            if($value == $data['direccion_seleccionada']){
              continue;
            }

            $direcciones[] = [
              'direccion' => $value,
            ];
          }
        }

        $cliente->direcciones()->createMany($direcciones);

        if($createProveedor){
          $proveedor->direcciones()->createMany($direcciones);
        }


        if($request->ajax()){
          return response()->json(['response' =>  true, 'cliente' => $cliente]);
        }

        return redirect()->route('admin.cliente.show', ['cliente' => $cliente->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Cliente agregado exitosamente.',
        ]);
      }

      if($request->ajax()){
        return response()->json(['response' =>  false]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
      $cliente->load(['direcciones', 'contactos', 'cotizaciones', 'facturaciones']);

      return view('admin.cliente.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
      if($cliente->isEmpresa()){
        abort(403);
      }

      $type = $cliente->type;

      return view('admin.cliente.edit.'.$type, compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
      if($cliente->isEmpresa()){
        abort(403);
      }

      $method = 'update'.ucfirst($cliente->type);

      return $this->$method($request, $cliente);
    }

    /**
     * Actualizar el Cliente tipo persona especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    private function updatePersona(Request $request, Cliente $cliente)
    {
      $this->validate($request, [
        'nombre' => 'required|string|max:100',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:50',
        'descripcion' => 'nullable|string|max:200',
      ]);

      if(Cliente::where([['rut', $request->rut], ['id', '!=', $cliente->id]])->exists()){
        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ya existe un cliente registrado con ese RUT.',
          'flash_important' => true
        ]);
      }

      $cliente = $cliente->fill($request->only('nombre', 'rut', 'telefono', 'email', 'descripcion'));

      if($cliente->save()){
        return redirect()->route('admin.cliente.show', ['cliente' => $cliente->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Cliente modificado exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
      if($cliente->delete()){
        return redirect()->route('admin.cliente.index')->with([
          'flash_message' => 'Cliente eliminado exitosamente.',
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
     * Buscar informacion de la empresa porporcionada con la api de Facturacion Sii
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function busquedaSii(Request $request)
    {
      [$response, $data] = Auth::user()->empresa->configuracion->getEmpresaFromSii($request->rut, $request->dv);

      if(!$response){
        return response()->json(['response' => false, 'data' => $data]);
      }

      return response()->json([
        'response' => true,
        'data' => [
          'razon_social' => $data['razon_social'],
          'direccion' => $data['direccion_seleccionada'],
          'comuna' => $data['comuna_seleccionada'],
          'ciudad' => $data['ciudad_seleccionada'],
        ]
      ]);
    }

    /**
     * Obtener los contactos de un Cliente especificado
     * 
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function contactos(Cliente $cliente)
    {
      return response()->json(['contactos' => $cliente->contactos]);
    }

    /**
     * Obtener las direcciones de un Cliente especificado
     * 
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function direcciones(Cliente $cliente)
    {
      return response()->json(['direcciones' => $cliente->direcciones]);
    }
}
