<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
      $usuarios  = Usuario::adminsYSupervisores();

      return view('admin.usuarios.index', ['usuarios' => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.usuarios.create');
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
        'email' => 'nullable|email|unique:users,email',
        'telefono' => 'nullable|string'
      ]);

      $usuario = new Usuario($request->all());
      $usuario->usuario = $request->rut;
      $usuario->tipo = 2; // Administrador
      $usuario->password = bcrypt($request->rut);

      if($usuario = Auth::user()->empresa->usuario()->save($usuario)){
        return redirect()->route('admin.usuarios.show', ['usuario' => $usuario->id])->with([
          'flash_message' => 'Usuario agregado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }
      
      return redirect()->back()->withInput()>with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
  }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ususario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
      return view('admin.usuarios.show', ['usuario' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ususario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
      return view('admin.usuarios.edit', ['usuario' => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
      $this->validate($request, [
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:users,rut,' . $usuario->id . ',id',
        'email' => 'nullable|email|unique:users,email,' . $usuario->id . ',id',
        'telefono' => 'nullable|string'
      ]);

      $usuario->fill($request->all());
      $usuario->usuario = $request->rut;

      if($usuario->save()){
        return redirect()->route('admin.usuarios.show', ['usuario' => $usuario->id])->with([
          'flash_message' => 'Usuario modificado exitosamente.',
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
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
      if($usuario->delete()){
        return redirect()->route('admin.usuarios.index')->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Usuario eliminado exitosamente.'
        ]);
      }

      return redirect()->back()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Cambiar la contraseña del Usuario especificado
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario $usuario
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request, Usuario $usuario)
    {
      $this->validate($request, [
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
      ]);

      $usuario->password = bcrypt($request->password);

      if($usuario->save()){
        return redirect()->route('admin.usuarios.show', ['usuario' => $usuario->id])->with([
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

    /**
     * Obtener la informacion del Usuario especificado
     * 
     * @param  \App\Usuario $usuario
     * @return \Illuminate\Http\Response 
     */
    public function get(Usuario $usuario)
    {
      return response()->json($usuario->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
    }
}
