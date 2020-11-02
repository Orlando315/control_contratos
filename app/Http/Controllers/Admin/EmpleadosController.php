<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

      return view('admin.empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Contrato $contrato)
    {
      $usuarios = Usuario::doesntHave('empleado')->get();

      return view('admin.empleados.create', compact('contrato', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contrato $contrato)
    {
      if($request->filled('usuario')){
        $usuario = Usuario::doesntHave('empleado')->where('id', $request->usuario)->firstOrFail();
      }else{
        // Aplica cuando no se esta creando el Empleado a partir de un Usuario ya existente
        $this->validate($request, [
          'nombres' => 'required|string',
          'apellidos' => 'required|string',
          'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut',
          'telefono' => 'nullable|string',
          'email' => 'nullable|email|unique:users,email',
        ]);
      }

      $this->validate($request, [
        'sexo' => 'required',
        'fecha_nacimiento' => 'required|date_format:d-m-Y',
        'direccion' => 'required|string|max:100',
        'profesion' => 'nullable|string|max:100',
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
        // Si se esta creando un Emplado a partir de un Usuario ya existente
        // solo se agrega el id del Empleado al Usuario
        if(isset($usuario)){
          $usuario->empleado_id = $emplado->id;
          $usuario->save();
        }else{
          $usuario = new Usuario($request->all());
          $usuario->usuario  = $request->rut;
          $usuario->password = bcrypt($request->rut);
          $usuario->tipo = 4; // Tipo 4 = Empleado
          $usuario->empresa_id = Auth::user()->empresa->id;
          $empleado->usuario()->save($usuario);
        }
        
        $empleado->contratos()->create($request->all());
        $empleado->banco()->create($request->all());

        return redirect()->route('admin.empleados.show', ['empleado' => $emplado->id])->with([
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

      return view('admin.empleados.show', compact('empleado', 'empleados', 'contratos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
      return view('admin.empleados.edit', compact('empleado'));
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
          return redirect()
                    ->back()
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
        return redirect()->route('admin.empleados.show', ['empleado' => $empleado->id])->with([
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Empleado $empleado)
    {
      // No eliminar el Usuario del Empleado si es Admin,
      // a menos que se indique que se debe eliminar.
      // Menos el Usuario Empresa (Tipo 1) que nunca se elimina
      if($empleado->usuario->tipo == 1 || ($empleado->usuario->tipo == 2 && !$request->filled('eliminar_admin'))){
        $empleado->usuario->empleado_id = null;
        $empleado->push();
      }

      if($empleado->delete()){
        Storage::deleteDirectory('Empleado' . $empleado->id);

        return redirect()->route('admin.contratos.show', ['contrato' => $empleado->contrato_id])->with([
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
     * Cambiar el role de un Empleado
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function toggleTipo(Request $request, Empleado $empleado)
    {
      if($empleado->usuario->tipo < 2){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No se puede cambiar el Role de un Empelado con Usuario Empresa.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      // No se puede asignar un tipo 1 (Empresa) a un Empleado
      // Solo un Admin puede cambiar el role de un Empleado a Admin
      $empleado->usuario->tipo = $request->role > 1 && (Auth::user()->tipo <= $request->role) ? $request->role : 4;

      if($empleado->push()){
        return redirect()->route('admin.empleados.show', ['empleado' => $empleado->id])->with([
          'flash_message' => 'Empleado actualizado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }
}
