<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Carbon\Carbon;
use App\{EmpleadosEvento, Empleado};

class EmpleadosEventosController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Empleado $empleado)
    {
      $this->validate($request, [
        'tipo' => 'required',
        'reemplazo' => 'required_if:tipo,9',
        'valor'=> 'required_if:tipo,9',
        'inicio' => 'required|date_format:Y-m-d',
        'fin' => 'nullable|date_format:Y-m-d',
      ]);

      $lastContrato = $empleado->contratos->last();
      $request->merge([
        'jornada' => $lastContrato->jornada,
        'comida' => false,
        'pago' => ($request->tipo == 3) // Las vacaciones (Evento tipo 3) son pagas. Todo lo demas es false
      ]);

      // Si el evento es Despido o Renuncia la fecha del evento se coloca como la fecha del ultimo contrato
      if($request->tipo == 6 || $request->tipo == 7){
        $request->merge(['fin' => null]);
        
        $lastContrato->fin = $request->inicio;
        $lastContrato->save();
      }

      // Evaluar si ya hay un evento registrado en el rango de fechas
      $eventosRepetidos = $empleado->findEvents($request->inicio, $request->fin, false, '!=', 1)->count();

      if($eventosRepetidos > 0){
        return ['response' => false, 'message' => 'Ya se encuentra un uno o más eventos registrado en las fechas seleciconadas.'];
      }

      if($evento = $empleado->eventos()->create($request->all())){

        // Cualquier otro evento que no sea Vacaciones. Ya que las vacaciones son pagas.
        if($evento->tipo != 3){
          // Buscar si hay un evento de Asistencia registrado en la fecha, o el rango de fecha
          // Si existe, cambiar la comida y el Pago a False.
          $asistenciasIds = $empleado->findEvents($evento->inicio, $evento->fin)->pluck('id')->toArray();

          if(count($asistenciasIds) > 0){
            EmpleadosEvento::whereIn('id', $asistenciasIds)->update(['comida' => false, 'pago' => false]);
          }
        }

        $response = ['response' => true, 'evento' => $evento, 'data'=> $evento->eventoData()];
      }else{
        $response = ['response' => false];
      }

      return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmpleadosEvento  $empleadosEvento
     * @return \Illuminate\Http\Response
     */
    public function show(EmpleadosEvento $empleadosEvento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmpleadosEvento  $empleadosEvento
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpleadosEvento $empleadosEvento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmpleadosEvento  $empleadosEvento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpleadosEvento $empleadosEvento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmpleadosEvento  $empleadosEvento
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpleadosEvento $evento)
    {
      if($evento->delete()){
        // Buscar si hay un evento de Asistencia registrado en la fecha, o el rango de fecha del evento eliminado
        // Si existe, cambiar la comida y el Pago a True.
        $asistenciasIds = Empleado::find($evento->empleado_id)->findEvents($evento->inicio, $evento->fin, false)->pluck('id')->toArray();

        if(count($asistenciasIds) > 0){
          EmpleadosEvento::whereIn('id', $asistenciasIds)->update(['comida' => true, 'pago' => true]);
        }

        $response = ['response' => true, 'evento' => $evento];
      }else{
        $response = ['response' => false];
      }

      return $response;
    }
}
