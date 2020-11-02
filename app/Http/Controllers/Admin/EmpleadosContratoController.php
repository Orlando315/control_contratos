<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\{Empleado, Contrato};

class EmpleadosContratoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function create(Empleado $empleado)
    {
      return view('admin.empleados.contrato.create', compact('empleado'));
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
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'nullable|date_format:d-m-Y',
        'jornada' => 'required',
        'descripcion' => 'nullable|string|max:200',
      ]);

      if($empleado->despidoORenuncia()){
        $evento = $empleado->eventos()->where('tipo', 6)->orWhere('tipo', 7)->first();
        $eventoDate = new Carbon($evento->inicio);

        $inicio = new Carbon($request->inicio);
        if($eventoDate->lessThanOrEqualTo($inicio)){
          return redirect()
                    ->back()
                    ->withErrors('La fecha de inicio del contrato no puede ser mayor o igual a la fecha de Renuncia/Despido: '. $evento->inicio)
                    ->withInput();  
        }

        if($request->fin){
          $fin = new Carbon($request->fin);  
          if($eventoDate->lessThan($fin)){
            return redirect()
                      ->back()
                      ->withErrors('La fecha de fin del contrato no puede ser mayor a la fecha de Renuncia/Despido: '. $evento->inicio)
                      ->withInput();  
          }
        }else{
          $request->merge(['fin' => $evento->inicio]);
        }
      }
      
      $lastContrato = $empleado->contratos->last();
      $request->merge(['inicio_jornada' => $request->inicio]);
      $request->merge(['sueldo' => $lastContrato->sueldo]);

      if($empleado->contratos()->create($request->only('sueldo', 'inicio', 'inicio_jornada', 'fin', 'jornada', 'descripcion'))){
        $lastContrato->fin = $request->inicio;
        $lastContrato->save();

        return redirect()->route('admin.empleados.show', ['empleado' => $empleado->id])->with([
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
      return view('admin.empleados.contrato.edit', compact('empleado'));
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
        'sueldo' => 'required|numeric',
        'inicio' => 'required|date_format:d-m-Y',
        'fin' => 'nullable|date_format:d-m-Y',
        'inicio_jornada' => 'required|date_format:d-m-Y',
        'jornada' => 'required',
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

      $contrato = $empleado->lastContrato;
      $contrato->fill($request->only('sueldo', 'inicio', 'fin', 'inicio_jornada', 'jornada', 'descripcion'));

      if($contrato->save()){
        return redirect()->route('admin.empleados.show', ['empleado' => $empleado->id])->with([
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
     * Cambiar el Empleado de Contrato
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function cambio(Request $request, Empleado $empleado){
      $contrato = Contrato::findOrFail($request->contrato);
      $empleado->contrato_id = $contrato->id;

      if($empleado->save()){
        return redirect()->route('admin.empleados.show', ['empleado' => $empleado->id])->with([
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
}
