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
      $this->authorize('viewAny', Anticipo::class);

      $actualYear = request()->year ?? date('Y');
      $allYears = Anticipo::allYears()->get()->pluck('year')->toArray();
      $monthlyGroupedSeries = Anticipo::monthlySeriesGroupedByYear($actualYear);
      $monthlyGroupedAprobados = Anticipo::monthlyGroupedByYearAndStatus($actualYear, true);
      $monthlyGroupedPendientes = Anticipo::monthlyGroupedByYearAndStatus($actualYear, null);
      $monthlyGroupedRechazados = Anticipo::monthlyGroupedByYearAndStatus($actualYear, false);

      return view('admin.anticipos.index', compact(
        'actualYear',
        'allYears',
        'monthlyGroupedSeries',
        'monthlyGroupedAprobados',
        'monthlyGroupedPendientes',
        'monthlyGroupedRechazados'
      ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', Anticipo::class);

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
      $this->authorize('create', Anticipo::class);
      $this->validate($request, [
        'empleado_id' => 'required',
        'fecha' => 'required|date_format:d-m-Y',
        'anticipo' => 'required|numeric|min:1|max:99999999',
        'bono' => 'nullable|numeric|min:1|max:99999999',
        'descripcion' => 'nullable|string|max:200',
        'adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $contrato = Contrato::findOrFail($request->contrato);
      $anticipo = new Anticipo($request->all());
      $anticipo->contrato_id = $contrato->id;
      $anticipo->status = true;

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
      $this->authorize('view', $anticipo);

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
      $this->authorize('update', $anticipo);

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
      $this->authorize('update', $anticipo);
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
      $this->authorize('delete', $anticipo);

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
      $this->authorize('create', Anticipo::class);

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
      $this->authorize('create', Anticipo::class);      
      $this->validate($request, [
        'fecha' => 'required|date_format:d-m-Y',
        'empleados.*.anticipo' => 'required|numeric|min:0|max:99999999',
        'empleados.*.bono' => 'nullable|numeric|min:0|max:99999999',
        'empleados.*.descripcion' => 'nullable|string|max:200',
        'empleados.*.adjunto' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      ]);

      $contrato = Contrato::findOrFail($request->contrato);
      if(count($request->empleados) == 0){
        return redirect()->back()
                  ->withErrors('No se encontro información de los empleados.')
                  ->withInput();
      }

      $anticipos = [];
      $files = [];
      $serie = Anticipo::generateSerie($contrato->id);

      foreach ($request->empleados as $id => $anticipo) {
        $data = [
          'contrato_id' => $contrato->id,
          'empleado_id' => $id,
          'serie' => $serie,
          'fecha' => $request->fecha,
          'anticipo' => $anticipo['anticipo'],
          'bono' => $anticipo['bono'],
          'descripcion' => $anticipo['descripcion'],
          'status' => true,
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
        return redirect()->route('admin.anticipos.show.serie', ['serie' => $serie])->with([
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
      $this->authorize('view', $anticipo);

      if(!$anticipo->adjunto || !Storage::exists($anticipo->adjunto)){
        abort(404);
      }

      return Storage::download($anticipo->adjunto);
    }

    /**
     * Descargar el djunto de Anticipo especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Anticipo $anticipo
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Anticipo $anticipo)
    {
      $this->authorize('update', $anticipo);

      $anticipo->status = $request->status == '1';

      if($anticipo->save()){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Estatus modificado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $serie
     * @return \Illuminate\Http\Response
     */
    public function serie($serie)
    {
      $this->authorize('viewAny', Anticipo::class);

      $anticipos = Anticipo::whereSerie($serie)->get();

      if($anticipos->isEmpty()){
        abort(404);
      }

      $contrato = $anticipos->first()->contrato;
      $totalAnticipos = $anticipos->sum('anticipo');
      $totalBonos = $anticipos->sum('bono');
      $serieFecha = $anticipos->first()->fecha;

      return view('admin.anticipos.serie', compact('serie', 'contrato', 'totalAnticipos', 'totalBonos', 'serieFecha', 'anticipos'));
    }

    /**
     * Display print view of the specified resource.
     *
     * @param  string  $serie
     * @return \Illuminate\Http\Response
     */
    public function printSerie($serie)
    {
      $this->authorize('viewAny', Anticipo::class);

      $anticipos = Anticipo::whereSerie($serie)->get();

      if($anticipos->isEmpty()){
        abort(404);
      }

      $contrato = $anticipos->first()->contrato;
      $totalAnticipos = $anticipos->sum('anticipo');
      $totalBonos = $anticipos->sum('bono');
      $serieFecha = $anticipos->first()->fecha;

      return view('admin.anticipos.print.serie', compact('serie', 'contrato', 'totalAnticipos', 'totalBonos', 'serieFecha', 'anticipos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $serie
     * @return \Illuminate\Http\Response
     */
    public function destroySerie($serie)
    {
      $this->authorize('deleteSerie', Anticipo::class);

      $anticipos = Anticipo::select('id', 'adjunto')->whereSerie($serie)->get();
      $adjuntos = $anticipos->pluck('adjunto')->reject(function($adjunto){
        return is_null($adjunto);
      });

      if($anticipos->isEmpty()){
        abort(404);
      }

      try{
        Anticipo::whereSerie($serie)->delete();
        if($adjuntos->count()){
          Storage::delete($adjuntos->toArray());
        }

        return redirect()->route('admin.anticipos.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Serie eliminada exitosamente.'
        ]);
      }catch(\Exception $e){
        return redirect()->back()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
