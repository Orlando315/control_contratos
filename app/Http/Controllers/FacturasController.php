<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Factura;

class FacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $facturas = Factura::all();

      return view('facturas.index', ['facturas' => $facturas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('facturas.create');
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
        'tipo' => 'required|in:1,2',
        'nombre' => 'required|string',
        'realizada_para' => 'required|string',
        'realizada_por' => 'required|string',
        'fecha' => 'required|date_format:d-m-Y',
        'valor' => 'required|numeric',
        'pago_fecha' => 'required|date_format:d-m-Y', 
        'pago_estado' => 'required|in:0,1',
        'adjunto1' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'adjunto2' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $factura = new Factura($request->all());
      $factura->user_id = Auth::user()->id;

      if($factura = Auth::user()->empresa->facturas()->save($factura)){
        $directory = $factura->directory();

        if(!Storage::exists($directory)){
          Storage::makeDirectory($directory);
        }

        if($request->hasFile('adjunto1')){
          $factura->adjunto1 = $request->file('adjunto1')->store($directory);
        }

        if($request->hasFile('adjunto2')){
          $factura->adjunto2 = $request->file('adjunto2')->store($directory);
        }

        $factura->save();

        return redirect('facturas/' . $factura->id)->with([
          'flash_message' => 'Factura agregada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('facturas/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
      return view('facturas.show', ['factura' => $factura]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
      return view('facturas.edit', ['factura' => $factura]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
      $this->validate($request, [
        'tipo' => 'required|in:1,2',
        'nombre' => 'required|string',
        'realizada_para' => 'required|string',
        'realizada_por' => 'required|string',
        'fecha' => 'required|date_format:d-m-Y',
        'valor' => 'required|numeric',
        'pago_fecha' => 'required|date_format:d-m-Y', 
        'pago_estado' => 'required|in:0,1',
        'adjunto1' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'adjunto2' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $factura->fill($request->all());

      if($factura->save()){

        $directory = $factura->directory();

        if($request->hasFile('adjunto1')){
          // Si ya tine un archivo adjunto1, eliminarlo
          if($factura->adjunto1){
            Storage::delete($factura->adjunto1);
          }

          $factura->adjunto1 = $request->file('adjunto1')->store($directory);
        }

        if($request->hasFile('adjunto2')){
          // Si ya tine un archivo adjunto2, eliminarlo
          if($factura->adjunto2){
            Storage::delete($factura->adjunto2);
          }

          $factura->adjunto2 = $request->file('adjunto2')->store($directory);
        }

        $factura->save();

        return redirect('facturas/' . $factura->id)->with([
          'flash_message' => 'Factura modificada exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('facturas/' . $factura->id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
      if($factura->delete()){

        Storage::deleteDirectory($factura->directory());

        return redirect('facturas')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Factura eliminada exitosamente.'
        ]);
      }else{
        return redirect('facturas')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    public function download(Factura $factura, $adjunto)
    {
      if(($adjunto < 1 || $adjunto > 2) || $factura->{"adjunto{$adjunto}"} == null){
        abort(404);
      }

      return Storage::download($factura->{"adjunto{$adjunto}"});
    }
}
