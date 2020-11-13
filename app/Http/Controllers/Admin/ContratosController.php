<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use App\{Contrato, Faena};

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $contratos = Contrato::all();
      $faenas = Faena::all();

      return view('admin.contratos.index', compact('contratos', 'faenas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $faenas = Faena::all();

      return view('admin.contratos.create', compact('faenas'));
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
        'nombre' => 'required|string',
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'required|date_format:d-m-Y',
        'valor' => 'required|numeric',
        'faena' => 'nullable',
        'descripcion' => 'nullable|string|max:150'
      ]);

      $contrato = new Contrato($request->only('nombre', 'inicio', 'fin', 'valor', 'descripcion'));
      $contrato->faena_id = $request->faena;

      if(Auth::user()->empresa->contratos()->save($contrato)){

        if($request->has('requisitos')){
          foreach ($request->requisitos as $type => $requisitos) {
            $data = [];
            foreach ($requisitos as $requisito) {
              $data[] = [
                'nombre' => $requisito,
                'empresa_id' => Auth::user()->empresa->id,
                'type' => $type,
              ];
            }

            $contrato->requisitos()->createMany($data);
          }
        }

        return redirect()->route('admin.contratos.show', ['contrato' => $contrato->id])->with([
          'flash_message' => 'Contrato agregado exitosamente.',
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
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show(Contrato $contrato)
    {
      return view('admin.contratos.show', compact('contrato'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function edit(Contrato $contrato)
    {
      $faenas = Faena::all();

      return view('admin.contratos.edit', compact('contrato', 'faenas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contrato $contrato)
    {
      $this->validate($request, [
        'nombre' => 'required|string',
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'required|date_format:d-m-Y',
        'valor' => 'required|numeric',
        'faena' => 'nullable',
        'descripcion' => 'nullable|string|max:150'
      ]);

      $contrato->fill($request->only('nombre', 'inicio', 'fin', 'valor', 'descripcion'));
      $contrato->faena_id = $request->faena;

      if($contrato->save()){
        return redirect()->route('admin.contratos.show', ['contrato' => $contrato->id])->with([
          'flash_message' => 'Contrato modificado exitosamente.',
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
     * @param  Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contrato $contrato)
    {
      if($contrato->delete()){
        return redirect()->route('admin.contratos.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contrato eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Mostrar la vista del calendario del Contrato especificado
     * con todos los Enventos y Jornadas de los Empleados.
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function calendar(Contrato $contrato)
    {
      $empleados = $contrato->empleados()->with('usuario:empleado_id,nombres,apellidos,rut')->get();
      $eventos   = $contrato->eventsToCalendar(true, '!=', 1, false);
      $jornadas  = $contrato->jornadasToCalendar();

      return view('admin.contratos.calendar', compact('contrato', 'empleados', 'eventos', 'jornadas'));
    }

    /**
     * Exportar toda la informacion de las Jornadas del Contrato especificado
     *
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function exportJornadas(Request $request, Contrato $contrato)
    {
      $this->exportExcel($contrato->exportJornadas($request->inicio, $request->fin), 'Jornadas');
    }

    /**
     * Crear el excel con la informacion a exportar
     *
     * @param  array  $data
     * @param  string  $nombre
     * @return \Illuminate\Http\Response
     */
    protected function exportExcel($data, $nombre)
    {
      $rows = [];
      foreach ($data as $row) {
        $rows[] = WriterEntityFactory::createRowFromArray($row);
      }

      $writer = WriterEntityFactory::createXLSXWriter();
      $writer->openToBrowser("{$nombre}.xlsx")
        ->addRows($rows)
        ->close();
    }
}
