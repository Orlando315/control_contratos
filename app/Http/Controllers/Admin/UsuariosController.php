<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{User, Role, Modulo};

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', User::class);

      $usuarios = Auth::user()->empresa->users()->staff()->get();

      return view('admin.usuarios.index', ['usuarios' => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create', User::class);

      $roles = Role::notAdmins()->where('name', '!=', 'empleado')->with('permissions:id,name,display_name')->get();
      $modulos = Modulo::all();

      return view('admin.usuarios.create', compact('roles', 'modulos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->authorize('create', User::class);
      $this->validate($request, [
        'role' => 'required',
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:users,rut',
        'email' => 'nullable|email|unique:users,email',
        'telefono' => 'nullable|string'
      ]);

      $role = Role::where('name', $request->role)->firstOrFail();
      $usuario = new User($request->only('nombres', 'apellidos', 'rut', 'email', 'telefono'));
      $usuario->usuario = $request->rut;
      $usuario->password = bcrypt($request->rut);

      if(Auth::user()->empresa->users()->save($usuario)){
        $usuario->attachRole($role);
        $usuario->attachPermissions($request->permissions ?? []);

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
    public function show(User $usuario)
    {
      $this->authorize('view', $usuario);

      return view('admin.usuarios.show', ['usuario' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ususario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
      $this->authorize('update', $usuario);

      $roles = Role::notAdmins()->where('name', '!=', 'empleado')->with('permissions:id,name,display_name')->get();
      $modulos = Modulo::all();

      return view('admin.usuarios.edit', compact('usuario', 'roles', 'modulos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
      $this->authorize('update', $usuario);
      $this->validate($request, [
        'role' => 'required',
        'nombres' => 'required|string',
        'apellidos' => 'required|string',
        'rut' => 'required|regex:/^(\d{4,9}-[\dk])$/|unique:users,rut,' . $usuario->id . ',id',
        'email' => 'nullable|email|unique:users,email,' . $usuario->id . ',id',
        'telefono' => 'nullable|string'
      ]);

      $role = Role::where('name', $request->role)->firstOrFail();
      $usuario->fill($request->only('nombres', 'apellidos', 'rut', 'email', 'telefono'));
      $usuario->usuario = $request->rut;

      if($usuario->isEmpresa() && $role->name != 'empresa'){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No se puede cambiar el role de un Usuario con role Empresa.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      if(!$usuario->isEmpresa() && $role->name == 'empresa'){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'El role Empresa no puede ser asignado.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      if(
        (!Auth::user()->hasActiveOrInactiveRole('empresa|administrador') && $request->role == 'administrador')
        || (!Auth::user()->hasActiveOrInactiveRole('empresa|administrador|supervisor') && $request->role == 'supervisor')
      ){
        return redirect()->back()->withInput()->with([
          'flash_message' => 'No puedes asignar un role superior al tuyo.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      if($usuario->save()){
        $usuario->assignRole($role);
        $usuario->syncPermissions($request->permissions ?? []);

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
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
      $this->authorize('delete', $usuario);

      $empleado = $usuario->empleado;

      if($usuario->delete()){
        if(!is_null($empleado)){
          $empleado->delete();
        }

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
     * Cambiar la contraseña del User especificado
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $usuario
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request, User $usuario)
    {
      $this->authorize('update', $usuario);
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
     * Obtener la informacion del User especificado
     * 
     * @param  \App\User $usuario
     * @return \Illuminate\Http\Response 
     */
    public function get(User $usuario)
    {
      return response()->json($usuario->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
    }
}
