<?php

namespace App\Http\Controllers\Admin\Development;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth};
use App\{Modulo, Permission};

class PermissionController extends Controller
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
      $permissions = Permission::with('modulo')->withCount('roles')->get();

      return view('admin.development.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $modulos = Modulo::all();

      return view('admin.development.permission.create', compact('modulos'));
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
        'modulo' => 'required',
        'name' => 'required|string|max:50|unique:permissions',
        'display_name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:100',
      ]);

      $permission = new Permission($request->only('name', 'display_name', 'description'));
      $permission->modulo_id = $request->modulo;

      if($permission->save()){
        return redirect()->route('admin.development.permission.show', ['permission' => $permission->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Permission agregado exitosamente.',
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
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
      $permission->load('roles', 'users');

      return view('admin.development.permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
      $modulos = Modulo::all();

      return view('admin.development.permission.edit', compact('permission', 'modulos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
      $this->validateWithBag('perfil', $request, [
        'modulo' => 'required',
        'name' => 'required|string|max:50|unique:permissions,name,'.$permission->id.',id',
        'display_name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:100',
      ]);

      $permission->fill($request->only('name', 'display_name', 'description'));
      $permission->modulo_id = $request->modulo;

      if($permission->save()){
        return redirect()->route('admin.development.permission.show', ['permission' => $permission->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Permission modificado exitosamente.',
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
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Permission $permission)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if($permission->delete()){
        return redirect()->route('admin.development.permission.index')->with([
          'flash_message' => 'Permission eliminado exitosamente.',
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
