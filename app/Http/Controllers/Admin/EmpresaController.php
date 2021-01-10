<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth, Storage};
use App\Models\{Empresa, EmpresaConfiguracion, User, Role};

class EmpresaController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('role:developer|superadmin|empresa');
    }

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
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function perfil()
    {
      $empresa = Auth::user()->empresa;

      return view('admin.empresa.perfil', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
      $empresa = Auth::user()->empresa;

      return view('admin.empresa.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $empresa = Auth::user()->empresa;

      $this->validate($request, [
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:empresas,rut,'.$empresa->id.',id',
        'razon_social' => 'required|string|max:50',
        'logo' => 'nullable|file|mimes:jpeg,png|max:3000',
        'representante_nombre' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:50|unique:empresas,email,'.$empresa->id.',id',
      ]);

      // Empresa
      $empresa->fill($request->only('rut', 'email', 'telefono'));
      $empresa->representante = $request->representante_nombre;
      $empresa->nombre = $request->razon_social;

      if($empresa->push()){
        if($request->hasFile('logo')){
          $directory = $empresa->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          if($empresa->logo && Storage::exists($empresa->logo)){
            Storage::delete($empresa->logo);
          }

          $empresa->logo = $request->file('logo')->store($directory);
          $empresa->save();
        }

        return redirect()->route('admin.empresa.perfil')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Empresa modificada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Empresa $empresa)
    {
      //
    }
}
