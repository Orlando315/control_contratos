<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};
use App\{Usuario, Empresa, ConfiguracionEmpresa};

class UsuariosController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('usuario.create');
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
        'nombres' => 'required|string',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut',
        'representante' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'telefono' => 'required',
        'jornada' => 'required',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
      ]);

      $empresa = new Empresa($request->all());

      if($empresa->save()){
        $user = new Usuario($request->all());
        $user->tipo = 1;
        $user->usuario = $request->rut;
        $user->password = bcrypt($request->input('password'));
        $empresa->usuario()->save($user);

        $empresa->configuracion()->create($request->all());        

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
        'nombres' => 'required|string',
        'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
        'telefono' => 'required'
      ]);

      // Para Supervisores y Empleados
      if(Auth::user()->tipo > 2){
        $this->validate($request, [
          'apellidos' => 'required|string',
        ]);
      }
      
      // Para Administradores
      if(Auth::user()->tipo <= 2){
        $this->validate($request, [
          'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut,' . Auth::user()->id . ',id',
          'representante' => 'required|string',
          'jornada' => 'required',
          'dias_vencimiento' => 'nullable|integer|min:1|max:255',
          'logo' => 'nullable|file|mimes:jpeg,png|max:3000'
        ]);

        Auth::user()->empresa->fill($request->only('nombres', 'representante'));
        Auth::user()->empresa->configuracion->jornada = $request->jornada;
        Auth::user()->empresa->configuracion->dias_vencimiento = $request->dias_vencimiento;
        Auth::user()->usuario = $request->rut;
        Auth::user()->rut = $request->rut;
      }

      // Solo Empresas
      if(Auth::user()->isEmpresa()){
        $this->validate($request, [
          'sii_clave' => 'nullable|string|max:120',
          'sii_clave_certificado' => 'nullable|string|max:150',
          'firma' => 'nullable|string|max:120',
        ]);

        Auth::user()->empresa->configuracion->sii_clave = $request->sii_clave;
        Auth::user()->empresa->configuracion->sii_clave_certificado = $request->sii_clave_certificado;
        Auth::user()->empresa->configuracion->firma = $request->firma;
      }

      Auth::user()->nombres = $request->nombres;
      Auth::user()->email = $request->email;
      Auth::user()->telefono = $request->telefono;

      if(Auth::user()->push()){
        if(Auth::user()->tipo <= 2 && $request->hasFile('logo')){
          $directory = Auth::user()->empresa->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          if(Auth::user()->empresa->logo){
            Storage::delete(Auth::user()->empresa->logo);
          }

          Auth::user()->empresa->logo = $request->file('logo')->store($directory);
          Auth::user()->empresa->save();
        }

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
     * @param  \App\Usuario|null $usuario
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
