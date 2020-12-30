<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use Illuminate\Support\Str;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmpleadoImport;
use App\{User, Empleado, Contrato, Role};

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Empleado::class);

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
      $this->authorize('view', $contrato);
      $this->authorize('create', Empleado::class);

      $usuarios = Auth::user()->empresa->users()->doesntHave('empleado')->get();

      return view('admin.empleados.create', compact('contrato', 'usuarios'));
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
      $this->authorize('create', Empleado::class);

      if($request->filled('usuario')){
        $usuario = Auth::user()->empresa->users()->doesntHave('empleado')->where('id', $request->usuario)->firstOrFail();
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

      $empleado = new Empleado($request->only(
        'sexo',
        'fecha_nacimiento',
        'direccion',
        'profesion',
        'talla_camisa',
        'talla_pantalon',
        'talla_zapato',
        'nombre_emergencia',
        'telefono_emergencica'
      ));
      $empleado->empresa_id = Auth::user()->empresa->id;

      if($contrato->empleados()->save($empleado)){
        // Si se esta creando un Emplado a partir de un Usuario ya existente
        // solo se agrega el id del Empleado al Usuario
        if(isset($usuario)){
          $usuario->empleado_id = $emplado->id;
          $usuario->save();
        }else{
          $usuario = new User($request->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
          $usuario->usuario  = $request->rut;
          $usuario->password = bcrypt($request->rut);
          $usuario->empresa_id = Auth::user()->empresa->id;
          $empleado->usuario()->save($usuario);

          $role = Role::firstWhere('name', 'empleado');
          $usuario->attachRole($role);
        }
        
        $empleado->contratos()->create($request->only('sueldo', 'inicio', 'fin', 'jornada', 'inicio_jornada', 'descripcion'));
        $empleado->banco()->create($request->only('nombre', 'tipo_cuenta'));

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
      $this->authorize('view', $empleado);

      $empleados = Empleado::select('id')
                            ->with('usuario:empleado_id,nombres,apellidos,rut')
                            ->where('contrato_id', $empleado->contrato_id)
                            ->get();
      $contratos = Contrato::where('id', '!=', $empleado->contrato_id)->get();
      $roles = Role::notAdmins()->get();

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
      $this->authorize('update', $empleado);

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
      $this->authorize('update', $empleado);
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

      $empleado->fill($request->only(
        'sexo',
        'fecha_nacimiento',
        'direccion',
        'profesion',
        'talla_camisa',
        'talla_pantalon',
        'talla_zapato',
        'nombre_emergencia',
        'telefono_emergencica'
      ));
      $empleado->contratos->last()->fill($request->only('sueldo', 'inicio', 'fin', 'jornada', 'inicio_jornada', 'descripcion'));
      $empleado->banco->fill($request->only('nombre', 'tipo_cuenta'));
      $empleado->usuario->fill($request->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
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
      $this->authorize('delete', $empleado);

      // No eliminar el Usuario del Empleado si es Admin,
      // a menos que se indique que se debe eliminar.
      // Menos el Usuario Empresa (Tipo 1) que nunca se elimina
      if($empleado->usuario->isEmpresa() || ($empleado->usuario->isAdministrador() && !$request->filled('eliminar_admin'))){
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
      $this->authorize('view', $empleado);

      $this->exportExcel($empleado->getDataAsArray($request->inicio, $request->fin), $empleado);
    }

    /**
     * Generar el Excel con la informacion proporcionada
     *
     * @param  array  $data
     * @param  \App\Empleado $empleado
     */
    protected function exportExcel($data, Empleado $empleado)
    {
      $this->authorize('view', $empleado);

      $empleadoInfo = [
        ['Empleado', $empleado->nombre()],
        ['RUT', $empleado->usuario->rut],
      ];

      $data = array_merge($empleadoInfo, $data);

      $rows = collect($data)->map(function($cells, $rowKey){
        $headerStyle = (new StyleBuilder())
          ->setBackgroundColor('FF2F4050')
          ->setFontBold()
          ->setFontSize(11)
          ->setFontColor('FFFFFF')
          ->build();

        // Header de fechas
        if($rowKey === 2){
          return WriterEntityFactory::createRowFromArray($cells, $headerStyle);
        }

        $cells = collect($cells)->map(function($cell, $cellKey) use ($rowKey, $headerStyle){
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

          $feriadoCellStyle = (new StyleBuilder())
            ->setBackgroundColor('FFF39C12')
            ->setFontSize(11)
            ->setShouldWrapText()
            ->build();

          // Darle estilos a la primera columna de cada fila
          if($cellKey === 0){
            $style = $headerStyle;
          }

          if($cellKey > 0 && !is_null($cell)){
            // Estilos para los dias de la jornada
            if($rowKey == 3){
              $style = Str::startsWith($cell, 'Trabajo') ? $trabajoCellStyle : $descansoCellStyle; 
            }

            // Estilos para los dias con eventos
            if($rowKey == 4){
              $style = $eventoCellStyle;
            }

            // Estilos para dias feriados
            if($rowKey == 5){
              $style = $feriadoCellStyle;
            }
          }

          return WriterEntityFactory::createCell($cell, $style);
        });

        return WriterEntityFactory::createRow($cells->all());
      }); 

      $writer = WriterEntityFactory::createXLSXWriter();
      $writer->openToBrowser("Empleado-{$empleado->id}.xlsx")
        ->addRows($rows->all())
        ->close();
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
    public function changeRole(Request $request, Empleado $empleado)
    {
      $this->authorize('update', $empleado);
      $requestedRole = $request->role;

      if($empleado->usuario->isEmpresa()){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No se puede cambiar el Role de un Empelado con Usuario de role Empresa.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }


      $role = Role::firstWhere('name', $request->role);
      if(!$role){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'Role no encontrado.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if($request->role == 'empresa'){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'El role Empresa no puede ser asignado.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if((Auth::user()->hasRole('supervisor|empleado') && $request->role == 'administrador') || Auth::user()->hasRole('empleado') && $request->role == 'supervisor'){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No puedes asignar un role superior al suyo.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if(Auth::user()->hasRole('empleado') && $request->role == 'administrador'){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No puedes asignar un role superior al suyo.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $empleado->usuario->syncRoles([$role->id]);

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

    /**
     * Imprimir la informacion del Empleado
     * 
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function print(Empleado $empleado)
    {
      $this->authorize('view', $empleado);

      return view('admin.empleados.print', compact('empleado'));
    }

    /**
     * Mostrar formulario para importar Empleados
     * 
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function importCreate(Contrato $contrato)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', Empelado::class);

      return view('admin.empleados.import', compact('contrato'));
    }

    /**
     * Importar Empleados por excel al Contrato proporcionado
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function importStore(Request $request, Contrato $contrato)
    {
      $this->authorize('view', $contrato);
      $this->authorize('create', Empelado::class);
      $this->validate($request, [
        'archivo' => 'required|file|mimes:xlsx,xls',
      ]);

      try{
        $excel = Excel::import(new EmpleadoImport($contrato), $request->archivo);

        return redirect()->route('admin.contratos.show', ['contrato' => $contrato->id])->with([
          'flash_message' => 'Empleados importados exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }catch(\Exception $e){
        return redirect()->back()->withInput()->with([
            'flash_message' => 'Ha ocurrido un error. Revise el formato utilizado.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
          ]);        
      }
    }
}
