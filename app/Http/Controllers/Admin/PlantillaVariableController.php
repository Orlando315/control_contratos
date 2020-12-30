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
      ];

      $i = 0;
      foreach ($vars as $variable) {
        if(!PlantillaVariable::where('variable', $variable['variable'])->exists()){
          Auth::user()->empresa->variables()->create($vars[$i]);
        }
        $i++;
      }

      return redirect()->route('admin.plantilla.documento.index')->with([
          'flash_message' => 'Variable generadas exitosamente.',
          'flash_class' => 'alert-success'
          ]);
    }
}
