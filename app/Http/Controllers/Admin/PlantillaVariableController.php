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
      abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'tipo' => 'required|in:text,number,date,email,rut,firma'
      ]);

      $variable = new PlantillaVariable($request->only('nombre', 'tipo'));

      if(Auth::user()->empresa->variables()->save($variable)){
        return redirect()->route('admin.plantilla.index')->with([
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
      $this->validate($request, [
        'nombre' => 'required|string|max:50',
        'tipo' => 'required|in:text,number,date,email,rut,firma'
      ]);

      $variable->fill($request->only('nombre', 'tipo'));

      if($variable->save()){
        return redirect()->route('admin.plantilla.index')->with([
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
}
