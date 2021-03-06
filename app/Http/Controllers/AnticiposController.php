<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\Anticipo;

class AnticiposController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('role:supervisor|empleado');
    }

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('anticipos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Anticipo::class);
      $this->validate($request, [
        'solicitud' => 'nullable|boolean',
        'anticipo' => [
          function ($attribute, $value, $fail) use ($request) {
            if($request->solicitud == '1' && is_null($value)){
              $fail('El elemento anticipo es requerido.');
            }
          },
          'numeric',
          'min:1',
          'max:99999999'
        ],
        'bono' => 'nullable|numeric|min:1|max:99999999',
        'descripcion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $anticipo = new Anticipo($request->only('bono', 'descripcion', 'adjunto', 'solicitud'));
      $anticipo->anticipo = $request->anticipo ?? 0;
      $anticipo->fecha = date('Y-m-d H:i:s');
      $anticipo->empresa_id = Auth::user()->empresa->id;
      $anticipo->contrato_id = Auth::user()->empleado->contrato_id;
      $anticipo->solicitud = $request->solicitud == '1';
      $anticipo->status = $request->solicitud == '1' ? null : true;

      if(Auth::user()->empleado->anticipos()->save($anticipo)){
        if($request->hasFile('adjunto')){
          $directory = $anticipo->directory;

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $anticipo->adjunto = $request->file('adjunto')->store($directory);
          $anticipo->save();
        }

        return redirect()->route('dashboard')->with([
          'flash_message' => 'Solicitud de Anticipo agregada exitosamente.',
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
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function show(Anticipo $anticipo)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Anticipo $anticipo)
    {
      //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anticipo $anticipo)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anticipo $anticipo)
    {
      //
    }

    /**
     * Descargar el djunto de Anticipo especificado
     * 
     * @param  \App\Anticipo $anticipo
     * @return \Illuminate\Http\Response
     */
    public function download(Anticipo $anticipo)
    {
      $this->authorize('view', $anticipo);

      if(!$anticipo->adjunto || !Storage::exists($anticipo->adjunto)){
        abort(404);
      }

      return Storage::download($anticipo->adjunto);
    }
}
