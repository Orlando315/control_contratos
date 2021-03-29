<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{InventarioEntrega, Inventario, Contrato};

class InventariosEntregasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function create(Inventario $inventario)
    {
      $this->authorize('view', $inventario);
      $this->authorize('create', InventarioEntrega::class);

      $empleados = $inventario->contrato->empleados()->with('usuario:id,empleado_id,nombres,apellidos')->get();

      return view('admin.inventarios.entregas.create', compact('inventario', 'empleados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Inventario $inventario)
    {
      $this->authorize('view', $inventario);
      $this->authorize('create', InventarioEntrega::class);
      $this->validate($request, [
        'usuario' => 'required',
        'cantidad' => 'required|numeric',        
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      if(($inventario->cantidad - $request->cantidad) < 0){
        return redirect()->back()
                  ->withErrors('La cantidad supera lo disponible en inventario.')
                  ->withInput();
      }

      $entrega = new InventarioEntrega;
      $entrega->realizado = Auth::user()->id;
      $entrega->entregado = $request->usuario;
      $entrega->cantidad  = $request->cantidad;

      if($inventario->entregas()->save($entrega)){
        $inventario->cantidad -= $request->cantidad;

        if($request->hasFile('adjunto')){
          $directory = $entrega->directory;

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $entrega->adjunto = $request->file('adjunto')->store($directory);
          $entrega->save();
        }

        $inventario->save();

        return redirect()->route('admin.inventarios.show', ['inventario' => $inventario->id])->with([
          'flash_message' => 'Entrega agregada exitosamente.',
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
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function show(InventarioEntrega $entrega)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function edit(InventarioEntrega $entrega)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventarioEntrega $entrega)
    {
      $this->authorize('update', $entrega);

      if(Auth::user()->id == $entrega->entregado){
        $entrega->recibido = true;

        if($entrega->save()){
          $response = ['response' => true];
        }else{
          $response = ['response' => false, 'message' => 'Ha ocurrido un error.'];
        }
      }else{
        $response = ['response' => false, 'message' => 'No estas autorizado a confirmar esta entrega.'];
      }


      return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventarioEntrega $entrega)
    {
      $this->authorize('delete', $entrega);

      $inventario = $entrega->inventario;
      $inventario->cantidad += $entrega->cantidad;

      if($entrega->delete()){
        $inventario->save();

        return redirect()->route('admin.inventarios.show', ['inventario' => $inventario->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Entrega eliminada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Descargar el ajunto de la Entrega.
     *
     * @param  \App\InventarioEntrega  $entrega
     * @return \Illuminate\Http\Response
     */
    public function download(InventarioEntrega $entrega)
    {
      $this->authorize('view', $entrega);

      return Storage::exists($entrega->adjunto) ? Storage::download($entrega->adjunto) : abort(404);
    }
}
