<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use App\Contrato;

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

      return view('admin.contratos.index', compact('contratos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.contratos.create');
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
        'descripcion' => 'nullable|string|max:150'
      ]);

      $contrato = new Contrato($request->all());

      if($contrato = Auth::user()->empresa->contratos()->save($contrato)){
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
      return view('admin.contratos.edit', compact('contrato'));
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
        'descripcion' => 'nullable|string|max:150'
      ]);

      $contrato->fill($request->all());

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
      $writer = WriterFactory::create(Type::XLSX);
      $writer->openToBrowser("{$nombre}.xlsx");
      $writer->addRows($data);

      $writer->close(); 
    }
}
