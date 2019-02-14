<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\EmpleadosSueldo;
use App\Contrato;

class EmpleadosSueldosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Contrato $contrato)
    {
      $sueldos = $contrato->sueldos()->latest()->get();

      return view('sueldos.index', ['contrato' => $contrato,'sueldos' => $sueldos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato)
    {
      $paymentMonth = $contrato->getPaymentMonth();
      $empleados = $contrato->empleados()->get();

      return view('sueldos.create', ['contrato' => $contrato, 'paymentMonth' => $paymentMonth, 'empleados' => $empleados]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contrato $contrato)
    {
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
          'adjunto' => $adjunto
        ];
      }

      if(Auth::user()->empresa->sueldos()->createMany($payments)){
        return redirect('sueldos/' . $contrato->id)->with([
          'flash_message' => 'Sueldos agregados exitosamente.',
          'flash_class' => 'alert-success',
          ]);
      }else{
        return redirect('sueldos/' . $contrato->id . '/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return \Illuminate\Http\Response
     */
    public function show(EmpleadosSueldo $sueldo)
    {
      // Los usuarios Supervisores (3) y Empleados (4), solo pueden ver sus propios sueldos
      if(Auth::user()->tipo >= 3 && Auth::user()->empleado_id != $sueldo->empleado_id){
        abort(404);
      }

      return view('sueldos.show', ['sueldo' => $sueldo]);
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

    public function recibido(EmpleadosSueldo $sueldo)
    {
      if(Auth::user()->empleado_id === $sueldo->empleado_id){
        $sueldo->recibido = true;

        if($sueldo->save()){
          $response = ['response' => true];
        }else{
          $response = ['response' => false, 'message' => 'Ha ocurrido un error.'];
        }
      }else{
        $response = ['response' => false, 'message' => 'No estas autorizado a confirmar esta sueldo.'];
      }

      return $response;
    }

    public function download(EmpleadosSueldo $sueldo)
    {
      return Storage::download($sueldo->adjunto);
    }
}
