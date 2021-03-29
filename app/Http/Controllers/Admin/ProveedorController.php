<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Proveedor, Cliente};

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Proveedor::class);

      $proveedores = Auth::user()->empresa->proveedores;

      return view('admin.proveedor.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
      $this->authorize('create', Proveedor::class);

      if($type != 'persona' && $type != 'empresa'){
        abort(404);
      }

      return view('admin.proveedor.create.'.$type);
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
      $this->authorize('create', Proveedor::class);

      if($type != 'persona' && $type != 'empresa'){
        abort(404);
      }

      $method = 'store'.ucfirst($type);

      return $this->{$method}($request);
    }

    /**
     * Almacenar un nuevo Proveedor de tipo Persona.
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

      if(Proveedor::where('rut', $request->rut)->exists()){
        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ya existe un proveedor registrado con ese RUT.',
          'flash_important' => true
        ]);
      }

      $createCliente = $request->has('cliente') && $request->cliente == '1';
      $cliente = $createCliente ? Cliente::where('rut', $request->rut)->first() : null;

      $proveedor = new Proveedor($request->only('type', 'nombre', 'rut', 'telefono', 'email', 'descripcion'));
      $proveedor->type = 'persona';

      if(Auth::user()->empresa->proveedores()->save($proveedor)){
        if($createCliente){
          // Si no existe un cliente, se crea
          if(is_null($cliente)){
            $cliente = new Cliente($proveedor->toArray());
            $cliente->proveedor_id = $proveedor->id;
            Auth::user()->empresa->clientes()->save($cliente); 
          }

          $proveedor->cliente_id = optional($cliente)->id;
          $proveedor->save();
        }

        if($request->has('direccion') && $request->direccion){
          $direccion = [
            'ciudad' => $request->ciudad,
            'comuna' => $request->comuna,
            'direccion' => $request->direccion,
            'status' => true,
          ];

          $proveedor->direcciones()->create($direccion);

          if($createCliente){
            $cliente->direcciones()->create($direccion);
          }
        }

        if($request->ajax()){
          return response()->json(['response' =>  true, 'proveedor' => $proveedor]);
        }

        return redirect()->route('admin.proveedor.show', ['proveedor' => $proveedor->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Proveedor agregado exitosamente.',
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
     * Almacenar un nuevo Proveedor tipo Emprsa.
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
      ]);

      if(Auth::user()->empresa->configuracion->isIntegrationIncomplete('sii')){
        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => '!Error! Integración incompleta.',
          'flash_important' => true
        ]);
      }

      $createCliente = $request->has('cliente') && $request->cliente == '1';
      $rut = $request->rut.'-'.$request->digito_validador;
      $cliente = $createCliente ? Cliente::where('rut', $rut)->first() : null;

      if(Proveedor::where('rut', $rut)->exists()){
        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ya existe un proveedor registrado con ese RUT.',
          'flash_important' => true
        ]);
      }

      [$response, $data] = Auth::user()->empresa->configuracion->getEmpresaFromSii($request->rut, $request->digito_validador);

      if(!$response){
        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => $data,
          'flash_important' => true
        ]);
      }

      $proveedor = new Proveedor;
      $proveedor->rut = $rut;
      $proveedor->type = 'empresa';
      $proveedor->nombre = $data['razon_social'];

      if(Auth::user()->empresa->proveedores()->save($proveedor)){
        if($createCliente){
          // Si no existe un cliente, se crea
          if(is_null($cliente)){
            $cliente = new Cliente($proveedor->toArray());
            Auth::user()->empresa->clientee()->save($cliente);
          }

          $proveedor->cliente_id = optional($cliente)->id;
          $proveedor->save();
        }

        if($request->has('contactos')){
          $proveedor->contactos()->createMany($request->contactos);

          if($createCliente){
            $cliente->contactos()->createMany($request->contactos);
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

        $proveedor->direcciones()->createMany($direcciones);

        if($createCliente){
          $cliente->direcciones()->createMany($cliente);
        }

        return redirect()->route('admin.proveedor.show', ['proveedor' => $proveedor->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Proveedor agregado exitosamente.',
        ]);
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
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
      $this->authorize('view', $proveedor);

      $proveedor->load([
        'direcciones',
        'contactos',
        'compras',
        'inventariosV2Ingreso.inventario',
        'productos',
      ]);

      return view('admin.proveedor.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
      $this->authorize('update', $proveedor);

      if($proveedor->isEmpresa()){
        abort(403);
      }

      $type = $proveedor->type;

      return view('admin.proveedor.edit.'.$type, compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedor)
    {
      $this->authorize('update', $proveedor);

      if($proveedor->isEmpresa()){
        abort(403);
      }

      $method = 'update'.ucfirst($proveedor->type);

      return $this->$method($request, $proveedor);
    }

    /**
     * Actualizar el Proveedor tipo persona especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    private function updatePersona(Request $request, Proveedor $proveedor)
    {
      $this->validate($request, [
        'nombre' => 'required|string|max:100',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:50',
        'descripcion' => 'nullable|string|max:200',
      ]);

      if(Proveedor::where([['rut', $request->rut], ['id', '!=', $proveedor->id]])->exists()){
        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ya existe un proveedor registrado con ese RUT.',
          'flash_important' => true
        ]);
      }

      $proveedor = $proveedor->fill($request->only('nombre', 'rut', 'telefono', 'email', 'descripcion'));

      if($proveedor->save()){
        return redirect()->route('admin.proveedor.show', ['proveedor' => $proveedor->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Proveedor modificado exitosamente.',
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
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
      $this->authorize('delete', $proveedor);

      if($proveedor->delete()){
        return redirect()->route('admin.proveedor.index')->with([
          'flash_message' => 'Proveedor eliminado exitosamente.',
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
     * Obtener los contactos de un Proveedor especificado
     * 
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function contactos(Proveedor $proveedor)
    {
      return response()->json(['contactos' => $proveedor->contactos]);
    }
}
