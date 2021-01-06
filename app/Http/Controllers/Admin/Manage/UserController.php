<?php

namespace App\Http\Controllers\Admin\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use App\{User, Role, Empresa};

class UserController extends Controller
{
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
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function create(Empresa $empresa)
    {
      $roles = Role::notSuper()->get();

      return view('admin.manage.user.create', compact('roles', 'empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Empresa $empresa)
    {
      $this->validate($request, [
        'role' => 'required',
        'nombres' => 'required|string|max:50',
        'apellidos' => 'nullable|string|max:50',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut',
        'telefono' => 'nullable|string',
        'email' => 'nullable|email|unique:users',
        'password' => 'required|min:6|confirmed',
      ]);

      $role = Role::findOrFail($request->role);
      $user = new User($request->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
      $user->usuario = $request->rut;
      $user->password = bcrypt($request->password);

      if($empresa->users()->save($user)){
        $user->attachRole($role);

        return redirect()->route('admin.manage.user.show', ['user' => $user->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Usuario agregado exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      return view('admin.manage.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
      $roles = Role::notSuper()->get();

      return view('admin.manage.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
      $this->validate($request, [
        'role' => 'required',
        'nombres' => 'required|string|max:50',
        'apellidos' => 'nullable|string|max:50',
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut,'.$user->id.',id',
        'telefono' => 'nullable|string',
        'email' => 'nullable|email|unique:users,email,'.$user->id.',id',
      ]);

      $role = Role::where('name', $request->role)->firstOrFail();
      $user = $user->fill($request->only('nombres', 'apellidos', 'rut', 'telefono', 'email'));
      $user->usuario = $request->rut;
      $user->password = bcrypt($request->password);

      if($user->save()){
        $user->assignRole($role);

        return redirect()->route('admin.manage.user.show', ['user' => $user->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Usuario modificado exitosamente.',
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      $empresa = $user->empresa;

      if($user->delete()){
        return redirect()->route('admin.manage.empresa.show', ['user' => $empresa->id])->with([
          'flash_message' => 'Usuario eliminado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }

      return redirect()->back()->with([
        'flash_message' => 'Ha ocurrido un error.',
        'flash_class' => 'alert-danger',
        'flash_important' => true
        ]);
    }

    /**
     * Cambiar la contraseña del User especificado
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request, User $user)
    {
      $this->validate($request, [
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
      ]);

      $user->password = bcrypt($request->password);

      if($user->save()){
        return redirect()->route('admin.manage.user.show', ['user' => $user->id])->with([
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
