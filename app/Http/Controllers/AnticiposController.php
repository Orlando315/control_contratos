<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Anticipo;
use App\Contrato;

class AnticiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $anticipos = Anticipo::all();

      return view('anticipos.index', ['anticipos' => $anticipos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $contratos = Contrato::all();

      return view('anticipos.create', ['contratos' => $contratos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $contrato = Contrato::findOrFail($request->contrato);

      $this->validate($request, [
        'empleado_id' => 'required',
        'fecha' => 'required|date_format:d-m-Y',
        'anticipo' => 'required|numeric',
      ]);

      $anticipo = new Anticipo($request->all());
      $anticipo->contrato_id = $contrato->id;

      if($anticipo = Auth::user()->empresa->anticipos()->save($anticipo)){
        return redirect('anticipos/' . $anticipo->id)->with([
          'flash_message' => 'Anticipo agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('anticipos/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function show(Anticipo $anticipo)
    {
      return view('anticipos.show', ['anticipo' => $anticipo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Anticipo $anticipo)
    {
      return view('anticipos.edit', ['anticipo' => $anticipo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anticipo $anticipo)
    {
      $this->validate($request, [
        'fecha' => 'required|date_format:d-m-Y',
        'anticipo' => 'required|numeric',
      ]);

      $contrato = $anticipo->contrato_id;
      $anticipo->fill($request->all());
      $anticipo->contrato_id = $contrato;

      if($anticipo->save()){
        return redirect('anticipos/' . $anticipo->id)->with([
          'flash_message' => 'Anticipo modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('anticipos/edit')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Anticipo  $anticipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anticipo $anticipo)
    {
      if($anticipo->delete()){
        return redirect('anticipos')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Anticipo eliminado exitosamente.'
        ]);
      }else{
        return redirect('anticipos')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function masivo()
    {
      $contratos = Contrato::all();

      return view('anticipos.createMasivo', ['contratos' => $contratos]);
    }

    public function getEmpleados(Contrato $contrato)
    {
      $empleados = $contrato->empleados()
                            ->select(['id'])
                            ->with([
                              'usuario:id,empleado_id,rut,nombres,apellidos',
                              'latestAnticipo'
                            ])
                            ->get()
                            ->toArray();

      return $empleados;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMasivo(Request $request)
    {
      $contrato = Contrato::findOrFail($request->contrato);
      
      $this->validate($request, [
        'fecha' => 'required|date_format:d-m-Y',
      ]);

      $data = json_decode($request->empleados, true);

      if(count($data) == 0){
        return redirect('anticipos/create/masivo')
                  ->withErrors('No se encontro información de los empleados.')
                  ->withInput();
      }

      $anticipos = [];

      foreach ($data as $id => $anticipo) {
        $anticipos[] = [
          'contrato_id' => $contrato->id,
          'empleado_id' => $id,
          'fecha' => $request->fecha,
          'anticipo' => $anticipo
        ];
      }

      if(Auth::user()->empresa->anticipos()->createMany($anticipos)){
        return redirect('anticipos/')->with([
          'flash_message' => 'Anticipos agregados exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('anticipos/create/masivo')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

}
