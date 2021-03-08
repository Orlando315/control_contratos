<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PlantillaVariable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantillaVariableController extends Controller
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
      $this->authorize('create', PlantillaVariable::class);

      return view('admin.plantilla.variable.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', PlantillaVariable::class);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'tipo' => 'required|in:text,number,date,email,rut,firma'
      ]);

      $variable = new PlantillaVariable($request->only('nombre', 'tipo'));

      if(Auth::user()->empresa->variables()->save($variable)){
        return redirect()->route('admin.plantilla.documento.index')->with([
          'flash_message' => 'Variable agregada exitosamente.',
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
     * @param  \App\PlantillaVariable  $variable
     * @return \Illuminate\Http\Response
     */
    public function show(PlantillaVariable $variable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PlantillaVariable  $variable
     * @return \Illuminate\Http\Response
     */
    public function edit(PlantillaVariable $variable)
    {
      $this->authorize('update', $variable);

      return view('admin.plantilla.variable.edit', compact('variable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PlantillaVariable  $variable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlantillaVariable $variable)
    {
      $this->authorize('update', $variable);
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'tipo' => 'required|in:text,number,date,email,rut,firma'
      ]);

      $variable->fill($request->only('nombre', 'tipo'));

      if($variable->save()){
        return redirect()->route('admin.plantilla.documento.index')->with([
          'flash_message' => 'Variable modificada exitosamente.',
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
     * @param  \App\PlantillaVariable  $variable
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlantillaVariable $variable)
    {
      $this->authorize('delete', $variable);

      if($variable->delete()){
        return redirect()->back()->with([
          'flash_message' => 'Variable eliminada exitosamente.',
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
     * Generar variables estaticas
     */
    public function generateStatic()
    {
      $this->authorize('create', PlantillaVariable::class);
      $vars = [
        ['variable' => '{{e_nombres}}', 'tipo' => 'empleado', 'nombre' => 'E - Nombres'],
        ['variable' => '{{e_apellidos}}', 'tipo' => 'empleado', 'nombre' => 'E - Apellidos'],
        ['variable' => '{{e_rut}}', 'tipo' => 'empleado', 'nombre' => 'E - RUT'],
        ['variable' => '{{e_fecha_de_nacimiento}}', 'tipo' => 'empleado', 'nombre' => 'E - Fecha de nacimiento'],
        ['variable' => '{{e_telefono}}', 'tipo' => 'empleado', 'nombre' => 'E - Teléfono'],
        ['variable' => '{{e_email}}', 'tipo' => 'empleado', 'nombre' => 'E - Email'],
        ['variable' => '{{e_direccion}}', 'tipo' => 'empleado', 'nombre' => 'E - Dirección'],
        ['variable' => '{{e_profesion}}', 'tipo' => 'empleado', 'nombre' => 'E - Profesión'],
        ['variable' => '{{e_sexo}}', 'tipo' => 'empleado', 'nombre' => 'E - Sexo'],
        ['variable' => '{{e_talla_camisa}}', 'tipo' => 'empleado', 'nombre' => 'E - Talla camisa'],
        ['variable' => '{{e_talla_zapato}}', 'tipo' => 'empleado', 'nombre' => 'E - Talla zapato'],
        ['variable' => '{{e_talla_pantalon}}', 'tipo' => 'empleado', 'nombre' => 'E - Talla pantalón'],
        ['variable' => '{{e_nombre_contacto_de_emergencia}}', 'tipo' => 'empleado', 'nombre' => 'E - Nombre contacto de emergencia'],
        ['variable' => '{{e_telefono_contacto_de_emergencia}}', 'tipo' => 'empleado', 'nombre' => 'E - Teléfono contacto de emergencia'],
        ['variable' => '{{e_nombre_del_banco}}', 'tipo' => 'empleado', 'nombre' => 'E - Nombre del Banco'],
        ['variable' => '{{e_tipo_de_cuenta_del_banco}}', 'tipo' => 'empleado', 'nombre' => 'E - Tipo de cuenta del Banco'],
        ['variable' => '{{e_cuenta_del_banco}}', 'tipo' => 'empleado', 'nombre' => 'E - Cuenta del Banco'],
        ['variable' => '{{e_nombre_del_contrato_principal}}', 'tipo' => 'empleado', 'nombre' => 'E - Nombre del Contrato pincipal'],
        ['variable' => '{{e_valor_del_contrato_principal}}', 'tipo' => 'empleado', 'nombre' => 'E - Valor del Contrato principal'],
        ['variable' => '{{e_fecha_de_inicio_del_contrato_principal}}', 'tipo' => 'empleado', 'nombre' => 'E - Fecha de Inicio del Contrato principal'],
        ['variable' => '{{e_fecha_de_fin_del_contrato_principal}}', 'tipo' => 'empleado', 'nombre' => 'E - Fecha de Fin del Contrato principal'],
        ['variable' => '{{e_faena_del_contrato_principal}}', 'tipo' => 'empleado', 'nombre' => 'E - Faena del Contrato principal'],
        ['variable' => '{{e_descripcion_del_contrato_principal}}', 'tipo' => 'empleado', 'nombre' => 'E - Descripción del Contrato principal'],
        ['variable' => '{{e_sueldo_del_contrato_de_empleado}}', 'tipo' => 'empleado', 'nombre' => 'E - Sueldo del Contrato de empleado'],
        ['variable' => '{{e_fecha_de_inicio_del_contrato_de_empleado}}', 'tipo' => 'empleado', 'nombre' => 'E - Fecha de Inicio del Contrato de empleado'],
        ['variable' => '{{e_fecha_de_fin_del_contrato_de_empleado}}', 'tipo' => 'empleado', 'nombre' => 'E - Fecha de Fin del Contrato de empleado'],
        ['variable' => '{{e_jornada_del_contrato_de_empleado}}', 'tipo' => 'empleado', 'nombre' => 'E - Jornada del Contrato de empleado'],
        ['variable' => '{{e_fecha_de_inicio_de_jornada_del_contrato_de_empleado}}', 'tipo' => 'empleado', 'nombre' => 'E - Fecha de Inicio de jornada del Contrato de empleado'],
        ['variable' => '{{e_descripcion_del_contrato_de_empleado}}', 'tipo' => 'empleado', 'nombre' => 'E - Descripción del Contrato de empleado'],
      ];

      foreach ($vars as $variable) {
        if(!Auth::user()->empresa->variables()->where('variable', $variable['variable'])->exists()){
          Auth::user()->empresa->variables()->create($variable);
        }
      }

      return redirect()->route('admin.plantilla.documento.index')->with([
          'flash_message' => 'Variable generadas exitosamente.',
          'flash_class' => 'alert-success'
          ]);
    }
}
