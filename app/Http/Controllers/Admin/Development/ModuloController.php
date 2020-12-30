<?php

namespace App\Http\Controllers\Admin\Development;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth};
use App\{Modulo, Permission};

class ModuloController extends Controller
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
      $modulos = Modulo::all();

      return view('admin.development.modulo.index', compact('modulos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.development.modulo.create');
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
        'name' => 'required|string|max:50|unique:modulos',
        'display_name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:100',
        'crud' => 'nullable|boolean',
      ]);

      $modulo = new Modulo($request->only('name', 'display_name', 'description'));

      if($modulo->save()){
        if($request->crud){
          $modulo->permissions()->createMany(Permission::createCrud($modulo->name));
        }

        return redirect()->route('admin.development.modulo.show', ['modulo' => $modulo->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Modulo agregado exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(Modulo $modulo)
    {
      $modulo->load('permissions');

      return view('admin.development.modulo.show', compact('modulo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modulo $modulo)
    {
      $modulo->load('permissions');

      return view('admin.development.modulo.edit', compact('modulo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modulo $modulo)
    {
      $this->validateWithBag('perfil', $request, [
        'name' => 'required|string|max:50|unique:roles,name,'.$modulo->id.',id',
        'display_name' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:100',
      ]);

      $modulo->fill($request->only('name', 'display_name', 'description'));

      if($modulo->save()){
        return redirect()->route('admin.development.modulo.show', ['modulo' => $modulo->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Modulo modificado exitosamente.',
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
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Modulo $modulo)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if($modulo->delete()){
        return redirect()->route('admin.development.modulo.index')->with([
          'flash_message' => 'Modulo eliminado exitosamente.',
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
