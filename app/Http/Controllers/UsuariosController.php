<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Usuario;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $usuarios = Usuario::usuarios();

      return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('usuarios.create');
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
        'apellidos' => 'required|string',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:users,rut',
        'email' => 'required|email|unique:users,email',
        'telefono' => 'required'
      ]);

      $usuario = new Usuario($request->all());
      $usuario->usuario = $request->rut;
      $usuario->tipo = 2; // Administrador
      $usuario->password = bcrypt($request->rut);

      if($usuario = Auth::user()->empresa->usuario()->save($usuario)){
        return redirect('usuarios/' . $usuario->id)->with([
          'flash_message' => 'Usuario agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('usuarios/create')->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
      return view('usuarios.show', ['usuario' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
      return view('usuarios.edit', ['usuario' => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
      $this->validate($request, [
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:users,rut,' . $usuario->id . ',id',
        'email' => 'required|email|unique:users,email,' . $usuario->id . ',id',
        'telefono' => 'required'
      ]);

      $usuario->fill($request->all());
      $usuario->usuario = $request->rut;

      if($usuario->save()){
        return redirect('usuarios/' . $usuario->id)->with([
          'flash_message' => 'Usuario modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('usuarios/' . $usuario->id)->with([
          'flash_message' => 'Ha ocurrido un error.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
      if($usuario->delete()){
        return redirect('usuarios')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Usuario eliminado exitosamente.'
        ]);
      }else{
        return redirect('usuarios')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    public function perfil()
    {
      return view('usuarios.perfil');
    }

    public function editPerfil()
    {
      return view('usuarios.editPerfil');
    }

    public function updatePerfil(Request $request)
    {
      $this->validate($request, [
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
        'telefono' => 'required'
      ]);

      $usuario = Usuario::find(Auth::user()->id);
      $usuario->fill($request->all());

      if($usuario->save()){
        return redirect('perfil')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Perfil modificado exitosamente.',
        ]);
      }else{
        return redirect('perfil')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }

    public function password(Request $request)
    {
      $this->validate($request, [
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
      ]);

      $usuario = Usuario::find(Auth::user()->id);
      $usuario->password = bcrypt($request->password);

      if($usuario->save()){
        return redirect('perfil')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Contraseña cambiada exitosamente.'
        ]);
      }else{
        return redirect('perfil')->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => 'Ha ocurrido un error.',
          'flash_important' => true
        ]);
      }
    }
}
