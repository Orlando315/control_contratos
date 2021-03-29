<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Http\Request;
use App\{EmpleadosSueldo, Contrato};

class EmpleadosSueldosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contrato|null  $contrato
     * @return \Illuminate\Http\Response
     */
    public function index(Contrato $contrato = null)
    {
      if($contrato){
        $this->authorize('view', $contrato); 
      }
      $this->authorize('viewAny', EmpleadosSueldo::class);

      $actualYear = request()->year ?? date('Y');
      $allYears = EmpleadosSueldo::allYears(optional($contrato)->id)->get()->pluck('year')->toArray();
      $monthlyGroupedSueldos = EmpleadosSueldo::monthlyGroupedByYear(optional($contrato)->id, $actualYear);

      return view('admin.sueldos.index', compact('contrato', 'actualYear', 'allYears', 'monthlyGroupedSueldos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', EmpleadosSueldo::class);

      $paymentMonth = $contrato->getPaymentMonth();
      $empleados = $contrato->empleados()->get();

      return view('admin.sueldos.create', compact('contrato', 'paymentMonth', 'empleados'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contrato $contrato)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', EmpleadosSueldo::class);

      $payments = [];
      $month = $contrato->getPaymentMonth(true);

      foreach ($contrato->empleados()->get() as $empleado) {
        $alcanceLiquido = $empleado->getAlcanceLiquido();
        $asistencias = $empleado->getAsistenciasByMonth($month);
        $anticipo = $empleado->calculateAnticiposByMonth($month);
        $bonoReemplazo = $empleado->calculateBonoReemplazoByMonth($month);
        $sueldoLiquido = $empleado->calculateSueldoLiquido($alcanceLiquido, $asistencias, $anticipo, $bonoReemplazo);

        $adjunto = null;

        if($request->hasFile('empleado.'.$empleado->id)){
          $directory = 'Empresa' . Auth::user()->empresa_id . '/Sueldos/Empleado'.$empleado->id;

          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $adjunto = $request->file('empleado.'.$empleado->id)->store($directory);
        }

        $payments[] = [
          'contrato_id' => $contrato->id,
          'empleado_id' => $empleado->id,
          'alcance_liquido' => $alcanceLiquido,
          'asistencias' => $asistencias,
          'anticipo' => $anticipo,
          'bono_reemplazo' => $bonoReemplazo,
          'sueldo_liquido' => $sueldoLiquido,
          'adjunto' => $adjunto,
          'mes_pago' => $month,
        ];
      }

      if(Auth::user()->empresa->sueldos()->createMany($payments)){
        return redirect()->route('admin.sueldos.index', ['contrato' => $contrato->id])->with([
          'flash_message' => 'Sueldos agregados exitosamente.',
          'flash_class' => 'alert-success',
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
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function show(EmpleadosSueldo $sueldo)
    {
      $this->authorize('view', $sueldo);

      return view('admin.sueldos.show', compact('sueldo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpleadosSueldo $sueldo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpleadosSueldo $sueldo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpleadosSueldo $sueldo)
    {
        //
    }

    /**
     * Descargar el adjunto del Sueldo especificado
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function download(EmpleadosSueldo $sueldo)
    {
      return Storage::download($sueldo->adjunto);
    }
}
