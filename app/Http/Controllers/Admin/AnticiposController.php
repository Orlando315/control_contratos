<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\{Anticipo, Contrato};

class AnticiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $anticipos = Anticipo::all();

      return view('admin.anticipos.index', compact('anticipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $contratos = Contrato::all();

      return view('admin.anticipos.create', compact('contratos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $contrato = Contrato::findOrFail($request->contrato);

      $this->validate($request, [
        'empleado_id' => 'required',
        'fecha' => 'required|date_format:d-m-Y',
        'anticipo' => 'required|numeric|min:1|max:99999999',
        'bono' => 'nullable|numeric|min:1|max:99999999',
        'descripcion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $anticipo = new Anticipo($request->all());
      $anticipo->contrato_id = $contrato->id;

      if(Auth::user()->empresa->anticipos()->save($anticipo)){
        if($request->hasFile('adjunto')){
          $directory = $anticipo->directory;

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $anticipo->adjunto = $request->file('adjunto')->store($directory);
          $anticipo->save();
        }

        return redirect()->route('admin.anticipos.show', ['anticipo' => $anticipo->id])->with([
          'flash_message' => 'Anticipo agregado exitosamente.',
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
      return view('admin.anticipos.show', compact('anticipo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Anticipo $anticipo)
    {
      return view('admin.anticipos.edit', compact('anticipo'));
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
      $this->validate($request, [
        'fecha' => 'required|date_format:d-m-Y',
        'anticipo' => 'required|numeric|min:1|max:99999999',
        'bono' => 'nullable|numeric|min:1|max:99999999',
        'descripcion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $anticipo->fill($request->only('fecha', 'anticipo', 'bono', 'adjunto', 'descripcion'));

      if($anticipo->save()){
        if($request->hasFile('adjunto')){
          $directory = $anticipo->directory;

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          if($anticipo->adjunto){
            Storage::delete($anticipo->adjunto);
          }

          $anticipo->adjunto = $request->file('adjunto')->store($directory);
          $anticipo->save();
        }

        return redirect()->route('admin.anticipos.show', ['anticipo' => $anticipo->id])->with([
          'flash_message' => 'Anticipo modificado exitosamente.',
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
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anticipo $anticipo)
    {
      if($anticipo->delete()){
        if($anticipo->adjunto){
          Storage::delete($anticipo->adjunto);
        }

        return redirect()->route('admin.anticipos.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Anticipo eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     *  Formulario para generar un Anticipo masivo
     *
     * @return \Illuminate\Http\Response
     */
    public function masivo()
    {
      $contratos = Contrato::all();

      return view('admin.anticipos.createMasivo', ['contratos' => $contratos]);
    }

    /**
     *  Obtener los Empleados del Contrato especificado, con
     *  el ultimo Anticipo recibido
     *
     * @param  \App\Contrato  $contrat
     * @return \Illuminate\Http\Response
     */
    public function getEmpleados(Contrato $contrato)
    {
      $empleados = $contrato->empleados()
                            ->select(['id'])
                            ->with([
                              'usuario:id,empleado_id,rut,nombres,apellidos',
                              'latestAnticipo'
                            ])
                            ->get()
                            ->toArray();

      return $empleados;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMasivo(Request $request)
    {
      $contrato = Contrato::findOrFail($request->contrato);
      
      $this->validate($request, [
        'fecha' => 'required|date_format:d-m-Y',
        'empleados.*.anticipo' => 'required|numeric|min:0|max:99999999',
        'empleados.*.bono' => 'nullable|numeric|min:0|max:99999999',
        'empleados.*.descripcion' => 'nullable|string|max:200',
        'empleados.*.adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      if(count($request->empleados) == 0){
        return redirect()->back()
                  ->withErrors('No se encontro información de los empleados.')
                  ->withInput();
      }

      $anticipos = [];
      $files = [];

      foreach ($request->empleados as $id => $anticipo) {
        $data = [
          'contrato_id' => $contrato->id,
          'empleado_id' => $id,
          'fecha' => $request->fecha,
          'anticipo' => $anticipo['anticipo'],
          'bono' => $anticipo['bono'],
          'descripcion' => $anticipo['descripcion'],
        ];

        if($request->hasFile('empleados.'.$id.'.adjunto')){
          $directory = 'Empresa'.$contrato->empresa_id.'/Empleado'.$id.'/Anticipos';

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $path = $request->empleados[$id]['adjunto']->store($directory);
          $files[] = $path;
          $data['adjunto'] = $path;
        }

        $anticipos[] = $data;
      }

      if(Auth::user()->empresa->anticipos()->createMany($anticipos)){
        return redirect()->route('admin.anticipos.index')->with([
          'flash_message' => 'Anticipos agregados exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      // Si ocurre un error, eliminar adjuntos cargados
      Storage::delete($files);

      return redirect()->back()->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Descargar el djunto de Anticipo especificado
     * 
     * @param  \App\Anticipo $anticipo
     * @return \Illuminate\Http\Response
     */
    public function download(Anticipo $anticipo)
    {
      if(!$anticipo->adjunto || !Storage::exists($anticipo->adjunto)){
        abort(404);
      }

      return Storage::download($anticipo->adjunto);
    }
}
