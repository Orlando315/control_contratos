<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\{User, Empresa, ConfiguracionEmpresa, Role};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('empresa.create');
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
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:empresas',
        'razon_social' => 'required|string|max:50',
        'logo' => 'nullable|file|mimes:jpeg,png|max:3000',
        'jornada' => 'required',
        'representante_legal' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:50|unique:empresas',
        'rut_empresa' => 'nullable|boolean',
        'nombres' => 'required|string|max:50',
        'apellidos' => 'nullable|string|max:50',
        'usuario_rut' => 'required_without:rut_empresa|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut',
        'contraseña' => 'required|min:6|confirmed',
      ]);

      $empresa = new Empresa($request->only('rut', 'telefono', 'email'));
      $empresa->representante = $request->representante_legal;
      $empresa->nombre = $request->razon_social;

      if($empresa->save()){
        // Configuracion
        $empresa->configuracion()->create($request->only('jornada'));

        // User
        $rut = $request->has('rut_empresa') ? $request->rut : $request->usuario_rut;
        $user = new User([
          'nombres' => $request->nombres,
          'apellidos' => $request->apellidos,
          'rut' => $rut,
          'telefono' => $request->telefono,
          'email' => $request->email,
        ]);
        $user->usuario = $rut;
        $user->password = bcrypt($request->input('contraseña'));
        $empresa->users()->save($user);

        $role = Role::firstWhere('name', 'empresa');
        $user->attachRole($role);

        if($request->hasFile('logo')){
          $directory = $empresa->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $empresa->logo = $request->file('logo')->store($directory);
          $empresa->save();
        }

        // Contrato
        $inicio = Carbon::now();
        $fin = $inicio->copy()->addYears(1);
        $empresa->contratos()->create([
          'nombre' => 'Casa matriz',
          'valor' => 0,
          'incio' => $inicio->format('Y-m-d'),
          'fin' => $fin->format('Y-m-d'),
          'main' => true,
        ]);

        return redirect()->route('login.view')->with([
          'flash_message' => 'Registro completado con exito.',
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
