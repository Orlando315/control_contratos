<?php

namespace App\Http\Controllers\Admin\Development;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth};
use App\{Role, Modulo};

class RoleController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('permission:god');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $roles = Role::with('permissions')->get();

      return view('admin.development.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $modulos = Modulo::all();

      return view('admin.development.role.create', compact('modulos'));
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
        'name' => 'required|string|max:50|unique:roles',
        'display_name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:100',
        'permissions.*' => 'nullable|integer',
      ]);

      $role = new Role($request->only('name', 'display_name', 'description'));

      if($role->save()){
        $role->attachPermissions($request->permissions);

        return redirect()->route('admin.development.role.show', ['role' => $role->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Role agregado exitosamente.',
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
      $role->load('permissions.modulo', 'users');

      return view('admin.development.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
      $modulos = Modulo::all();
      $role->load('permissions');

      return view('admin.development.role.edit', compact('role', 'modulos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
      $this->validateWithBag('perfil', $request, [
        'name' => 'required|string|max:50|unique:roles,name,'.$role->id.',id',
        'display_name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:100',
        'permissions.*' => 'nullable|integer',
      ]);

      $role->fill($request->only('name', 'display_name', 'description'));

      if($role->save()){
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.development.role.show', ['role' => $role->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Role modificado exitosamente.',
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Role $role)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if($role->delete()){
        return redirect()->route('admin.development.role.index')->with([
          'flash_message' => 'Role eliminado exitosamente.',
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
