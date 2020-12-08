<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\{Pago, Facturacion};

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function create(Facturacion $facturacion)
    {
      if($facturacion->isPaga()){
        abort(403);
      }

      return view('admin.pago.create', compact('facturacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Facturacion  $facturacion
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Facturacion $facturacion)
    {
      if($facturacion->isPaga()){
        abort(403);
      }

      $max = number_format($facturacion->pendiente, 2, '.', '');
      $this->validate($request, [
        'metodo' => 'required|string',
        'otro' => 'required_if:metodo,otro|string|max:20',
        'monto' => 'required|numeric|min:1|max:'.$max,
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $pago = new Pago($request->only('metodo', 'monto', 'descripcion'));
      $pago->metodo_otro = $request->metodo == 'otro' ? $request->otro : null;
      $pago->facturacion_id = $facturacion->id;

      if(Auth::user()->empresa->pagos()->save($pago)){
        $directory = $pago->directory;
        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        if($request->hasFile('adjunto')){
          $pago->adjunto = $request->file('adjunto')->store($directory);
          $pago->save();
        }

        return redirect()->route('admin.facturacion.show', ['facturacion' => $facturacion->id])->with([
            'flash_message' => 'Pago agregado exitosamente.',
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
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function edit(Pago $pago)
    {
      return view('admin.pago.edit', compact('pago'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago $pago)
    {
      $max = number_format($pago->facturacion->pendienteWithoutPago($pago, false), 2, '.', '');
      $this->validate($request, [
        'metodo' => 'required|string',
        'otro' => 'required_if:metodo,otro|string|max:20',
        'monto' => 'required|numeric|min:1|max:'.$max,
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'descripcion' => 'nullable|string|max:200',
      ]);

      $pago->fill($request->only('metodo', 'monto', 'descripcion'));
      $pago->metodo_otro = $request->metodo == 'otro' ? $request->otro : null;

      if($pago->save()){
        $directory = $pago->directory;
        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        if($request->hasFile('adjunto')){
          if($pago->adjunto && Storage::exists($pago->adjunto)){
            Storage::delete($pago->adjunto);
          }

          $pago->adjunto = $request->file('adjunto')->store($directory);
          $pago->save();
        }

        return redirect()->route('admin.facturacion.show', ['facturacion' => $pago->facturacion_id])->with([
            'flash_message' => 'Pago modificado exitosamente.',
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
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $pago)
    {
      if($pago->delete()){
        if(Storage::exists($pago->adjunto)){
          Storage::delete($pago->adjunto);
        }

        return redirect()->back()->with([
          'flash_class' => 'alert-success',
          'flash_message' => 'Pago eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class' => 'alert-danger',
        'flash_message' => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Descargar adjunto del Pago especificado
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function download(Pago $pago)
    {
      if(!Storage::exists($pago->adjunto)){
        abort(404);
      }

      return Storage::download($pago->adjunto);
    }
}
