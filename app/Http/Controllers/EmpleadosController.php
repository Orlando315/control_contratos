<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Carbon\Carbon;
use App\{Usuario, Empleado, Contrato};

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $empleados = Empleado::all();

      return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato)
    {
      return view('empleados.create', compact('contrato'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contrato $contrato)
    {
      $this->validate($request, [
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'sexo' => 'required',
        'fecha_nacimiento' => 'required|date_format:d-m-Y',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut',
        'direccion' => 'required|string|max:100',
        'profesion' => 'nullable|string|max:100',
        'telefono' => 'nullable|string',
        'email' => 'nullable|email|unique:users,email',
        'nombre_emergencia' => 'nullable|string|max:50',
        'telefono_emergencica' => 'nullable|string|max:20',
        'talla_camisa' => 'nullable|string',
        'talla_zapato' => 'nullable|numeric',
        'talla_pantalon' => 'nullable|string',
        'nombre' => 'required|string',
        'tipo_cuenta' => 'required',
        'cuenta' => 'required|string',
        'sueldo' => 'required|numeric',
        'inicio' => 'required|date_format:d-m-Y',
        'inicio_jornada' => 'required|date_format:d-m-Y',
        'fin' => 'nullable|date_format:d-m-Y',
        'jornada' => 'nullable',
        'descripcion' => 'nullable|string|max:200',
      ]);

      if(!$request->jornada){
        $request->merge(['jornada' => Auth::user()->empresa->configuracion->jornada]);
      }

      $empleado = new Empleado($request->all());
      $empleado->empresa_id = Auth::user()->empresa->id;

      if($emplado = $contrato->empleados()->save($empleado)){

        $usuario = new Usuario($request->all());
        $usuario->usuario  = $request->rut;
        $usuario->password = bcrypt($request->rut);
        $usuario->tipo = 4; // Tipo 4 = Empleado
        $usuario->empresa_id = Auth::user()->empresa->id;
        $empleado->usuario()->save($usuario);
        
        $empleado->contratos()->create($request->all());
        $empleado->banco()->create($request->all());

        return redirect()->route('empleados.show', ['empleado' => $emplado->id])->with([
          'flash_message' => 'Empleado registrado exitosamente.',
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
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
      $empleados = Empleado::select('id')
                            ->with('usuario:empleado_id,nombres,apellidos,rut')
                            ->where('contrato_id', $empleado->contrato_id)
                            ->get();

      $contratos = Contrato::all();

      return view('empleados.show', compact('empleado', 'empleados', 'contratos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
      return view('empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empleado $empleado)
    {
      $this->validate($request, [
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'sexo' => 'required',
        'fecha_nacimiento' => 'required|date_format:d-m-Y',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut,' . $empleado->usuario->id . ',id',
        'direccion' => 'required|string|max:100',
        'profesion' => 'nullable|string|max:100',
        'telefono' => 'nullable|string',
        'email' => 'nullable|email|unique:users,email,' . $empleado->usuario->id . ',id',
        'nombre_emergencia' => 'nullable|string|max:50',
        'telefono_emergencica' => 'nullable|string|max:20',
        'talla_camisa' => 'nullable|string',
        'talla_zapato' => 'nullable|numeric',
        'talla_pantalon' => 'nullable|string',
        'nombre' => 'required|string',
        'tipo_cuenta' => 'required',
        'cuenta' => 'required|string',
        'sueldo' => 'required|numeric',
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'nullable|date_format:d-m-Y',
        'inicio_jornada' => 'required|date_format:d-m-Y',
        'jornada' => 'nullable',
        'descripcion' => 'nullable|string|max:200',
      ]);

      if($empleado->despidoORenuncia() && $request->fin){
        $evento = $empleado->eventos()->where('tipo', 6)->orWhere('tipo', 7)->first();
        $eventoDate = new Carbon($evento->inicio);
        $fin = new Carbon($request->fin);
        if($eventoDate->lessThan($fin)){
          return redirect('empleados/'. $empleado->id .'/edit')
                    ->withErrors('La fecha de fin del contrato no puede ser mayor a la fecha de Renuncia/Despido: '. $evento->inicio)
                    ->withInput();  
        }
      }

      if(!$request->jornada){
        $request->merge(['jornada' => Auth::user()->configuracion->jornada]);
      }

      $empleado->fill($request->all());
      $empleado->contratos->last()->fill($request->all());
      $empleado->banco->fill($request->all());
      $empleado->usuario->fill($request->all());
      $empleado->usuario->usuario = $request->rut;

      if($empleado->push()){
        return redirect()->route('empleados.show', ['empleado' => $empleado->id])->with([
          'flash_message' => 'Empleado modificado exitosamente.',
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
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empleado $empleado)
    {
      if($empleado->delete()){
        Storage::deleteDirectory('Empleado' . $empleado->id);

        return redirect()->route('contratos.show', ['contrato' => $empleado->contrato_id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Empleado eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Mostrar el formulario para cambiar de Jornada
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function cambio($empleado)
    {
      return view('empleados.cambio', compact('empleado'));
    }

    /**
     * Cambiar la Jornada del Empleado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function cambioStore(Request $request, Empleado $empleado)
    {
      $this->validate($request, [
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'nullable|date_format:d-m-Y',
        'jornada' => 'nullable',
        'descripcion' => 'nullable|string|max:200',
      ]);

      if($empleado->despidoORenuncia()){
        $evento = $empleado->eventos()->where('tipo', 6)->orWhere('tipo', 7)->first();
        $eventoDate = new Carbon($evento->inicio);

        $inicio = new Carbon($request->inicio);
        if($eventoDate->lessThanOrEqualTo($inicio)){
          return redirect()->back()
                    ->withErrors('La fecha de inicio del contrato no puede ser mayor o igual a la fecha de Renuncia/Despido: '. $evento->inicio)
                    ->withInput();  
        }

        if($request->fin){
          $fin = new Carbon($request->fin);  
          if($eventoDate->lessThan($fin)){
            return redirect()->back()
                      ->withErrors('La fecha de fin del contrato no puede ser mayor a la fecha de Renuncia/Despido: '. $evento->inicio)
                      ->withInput();  
          }
        }else{
          $request->merge(['fin' => $evento->inicio]);
        }
      }
      
      $request->merge(['inicio_jornada' => $request->inicio]);

      $lastContrato = $empleado->contratos->last();

      if(!$request->jornada){
        $request->merge(['jornada' => Auth::user()->configuracion->jornada]);
      }

      $request->merge(['sueldo' => $lastContrato->sueldo]);

      if($empleado->contratos()->create($request->all())){
        $lastContrato->fin = $request->inicio;
        $lastContrato->save();

        return redirect('empleados/' . $empleado->id)->with([
          'flash_message' => 'Cambio de jornada exitoso.',
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
     * Exportar toda la informacion de Jornadas y Eventos del Empleado especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, Empleado $empleado)
    {
      $this->exportExcel($empleado->getDataAsArray($request->inicio, $request->fin), 'Empleado' . $empleado->id);
    }

    /**
     * Generar el Excel con la informacion proporcionada
     *
     * @param  array  $data
     * @param  string  $empleado
     * @return \Illuminate\Http\Response
     */
    protected function exportExcel($data, $nombre)
    {
      $writer = WriterFactory::create(Type::XLSX);
      $writer->openToBrowser("{$nombre}.xlsx");
      $writer->addRows($data);

      $writer->close(); 
    }

    /**
     * Obtener los Empleados del Contratos especificado
     *
     * @param  \App\Contrato  $contrato
     * @return array
     */
    public function getByContrato(Contrato $contrato)
    {
      return $contrato->empleados()->select(['id'])->with(['usuario:id,empleado_id,rut,nombres,apellidos'])->get()->toArray();
    }

    /**
     * Cambiar el tipo del Empleado, si es Supervisor se cambia a Empleado
     * Y viceversa
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function toggleTipo(Empleado $empleado)
    {
      $empleado->usuario->tipo = $empleado->usuario->tipo == 3 ? 4 : 3;

      if($empleado->push()){
        return redirect('empleados/' . $empleado->id)->with([
          'flash_message' => 'Empleado actualizado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->bacl()->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Cambiar el Empleado de Contrato
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function cambioContrato(Request $request, Empleado $empleado){
      $contrato = Contrato::findOrFail($request->contrato);
      $empleado->contrato_id = $contrato->id;

      if($empleado->save()){
        return redirect()->route('empleados.show', ['empleado' => $empleado->id])->with([
          'flash_message' => 'Empleado actualizado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->back()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Cronjob para generar las asistencias de los Empleados
     *
     * @param  \App\Empleado  $empleado
     */
    public function cronjobAsistencias(){
      $empleados = Empleado::withoutGlobalScope(EmpresaScope::class)->get();
      $today = date('Y-m-d');

      foreach($empleados as $empleado){
        if($empleado->isWorkDay()){
          $eventosExists = $empleado->eventsToday()->exists();          
          
          $empleado->eventos()->firstOrCreate([
            'inicio' => $today,
            'tipo' =>  1,
            'jornada' => $empleado->contratos->last()->jornada
          ],[
            'comida' => !$eventosExists,
            'pago' => !$eventosExists
          ]);
        }
      }
    }
}
