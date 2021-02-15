<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\Color;
use App\{Contrato, Faena, CentroCosto};

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Contrato::class);

      $contratos = Contrato::all();
      $faenas = Faena::all();
      $centros = CentroCosto::all();

      return view('admin.contratos.index', compact('contratos', 'faenas', 'centros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', Contrato::class);
      $faenas = Faena::all();
      $contratosWithRequisitos = Auth::user()->hasPermission('requisito-create') ? Contrato::has('requisitos')->with('requisitos')->get() : [];

      return view('admin.contratos.create', compact('faenas', 'contratosWithRequisitos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', Contrato::class);
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
              if(!$requisito['requisito']){
                continue;
              }

              $data[] = [
                'nombre' => $requisito['requisito'],
                'empresa_id' => Auth::user()->empresa->id,
                'type' => $type,
                'folder' => isset($requisito['carpeta']),
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
      $this->authorize('view', $contrato);

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
      $this->authorize('update', $contrato);

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
      $this->authorize('update', $contrato);
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
      $this->authorize('delete', $contrato);

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
      $this->authorize('view', $contrato);

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
      $this->authorize('view', $contrato);

      $this->exportExcel($contrato->exportJornadas($request->inicio, $request->fin), 'Jornadas');
    }

    /**
     * Crear el excel con la informacion a exportar
     *
     * @param  array  $data
     * @param  string  $nombre
     */
    protected function exportExcel($data, $nombre)
    {
      $this->authorize('view', $contrato);

      $rows = collect($data)->map(function($cells, $rowKey){

        // Estilos para la cabecera
        if($rowKey === 0){
          $headerStyle = (new StyleBuilder())
            ->setBackgroundColor('FF2F4050')
            ->setFontBold()
            ->setFontSize(11)
            ->setFontColor('FFFFFF')
            ->build();

          return WriterEntityFactory::createRowFromArray($cells, $headerStyle);
        }

        // Las columnas impares (en el excel) son para los eventos de los Empleados
        // aqui se toman las pares ya que el index de map inicia con 0, no 1 como excel
        $isEventRow = $rowKey % 2 == 0;

        $cells = collect($cells)->map(function($cell, $cellKey) use ($isEventRow){
          $style = null;
          $trabajoCellStyle = (new StyleBuilder())
            ->setBackgroundColor('FF40BD84')
            ->setFontSize(11)
            ->setShouldWrapText()
            ->build();

          $descansoCellStyle = (new StyleBuilder())
            ->setBackgroundColor('FFB5B5B5')
            ->setFontSize(11)
            ->setShouldWrapText()
            ->build();

          $eventoCellStyle = (new StyleBuilder())
            ->setBackgroundColor('FFD1ECF1')
            ->setFontSize(11)
            ->setShouldWrapText()
            ->build();

          // Eliminar el nombre repetido del empleado para en la 2da fila donde se muestran los eventos
          // para evitar que aparezca el nombre duplicado en 2 filas
          if($cellKey === 0){
            $cell = $isEventRow ? null : $cell;
          }

          if($cellKey > 0 && !is_null($cell)){

            // Si el row es de evento, se aplica el estilo para eventos
            // sino, se aplica el estilo para jornadas
            if($isEventRow){
              $style = $eventoCellStyle;
            }else{
              $style = Str::startsWith($cell, 'Trabajo') ? $trabajoCellStyle : $descansoCellStyle; 
            }
          }

          return WriterEntityFactory::createCell($cell, $style);
        });

        return WriterEntityFactory::createRow($cells->all());
      }); 

      $writer = WriterEntityFactory::createXLSXWriter();
      $writer->openToBrowser("{$nombre}.xlsx")
        ->addRows($rows->all())
        ->close();
    }
}
