<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{User, Empresa, ConfiguracionEmpresa};

class PerfilController extends Controller
{
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
     * Vista del perfil del Usuario
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function perfil()
    {
      return view('usuario.perfil');
    }

    /**
     * Formulario para editar el perfil del Usuario
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
      return view('usuario.edit');
    }
    /**
     * Actualizar informacion del Usuario en sesion
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $this->validate($request, [
        'nombres' => 'required|string|max:50',
        'apellidos' => 'required|string|max:50',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut,'.Auth::id().',id',
        'email' => 'required|email|max:50|unique:users,email,'.Auth::id().',id',
        'telefono' => 'required|string|max:20',
      ]);

      Auth::user()->fill($request->only('rut', 'nombres', 'apellidos', 'email', 'telefono'));
      Auth::user()->usuario = $request->rut;

      if(Auth::user()->save()){
        return redirect()->route('perfil')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Perfil modificado exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Cambiar la contraseña del Usuario en sesion
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request)
    {
      $this->validate($request, [
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
      ]);

      Auth::user()->password = bcrypt($request->password);

      if(Auth::user()->save()){
        return redirect()->route('perfil')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contraseña cambiada exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
