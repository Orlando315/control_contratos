<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmpleadosEvento;
use App\Empleado;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Carbon\Carbon;

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Empleado $empleado)
    {
      $this->validate($request, [
        'tipo' => 'required',
        'inicio' => 'required|date_format:Y-m-d',
        'fin' => 'nullable|date_format:Y-m-d',
      ]);

      // Si el evento es Despido o Renuncia la fecha del evento se coloca como la fecha del ultimo contrato
      if($request->tipo == 5 || $request->tipo == 6){
        $request->merge(['fin' => null]);
        
        $lastContrato = $empleado->contratos->last();
        $lastContrato->fin = $request->inicio;
        $lastContrato->save();
      }

      if($evento = $empleado->eventos()->create($request->all())){

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
        $response = ['response' => true, 'evento' => $evento];
      }else{
        $response = ['response' => false];
      }

      return $response;
    }
    
    public function exportEventsTotal(Request $request)
    {
      $this->exportExcel(EmpleadosEvento::exportAll($request->inicio, $request->fin), 'TotalEventos');
    }

    protected function exportExcel($data, $nombre)
    {
      $writer = WriterFactory::create(Type::XLSX);
      $writer->openToBrowser("{$nombre}.xlsx");
      $writer->addRows($data);

      $writer->close(); 
    }

    public function events()
    {
      return view('eventos.events');
    }

    public function getEvents(Request $request)
    {
      $eventos = EmpleadosEvento::exportAll($request->inicio, $request->fin);

      return $eventos;
    }
}
