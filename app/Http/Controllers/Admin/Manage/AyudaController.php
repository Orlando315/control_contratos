<?php

namespace App\Http\Controllers\Admin\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Ayuda, Role};

class AyudaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $ayudas = Ayuda::all();

      return view('admin.manage.ayuda.index', compact('ayudas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $roles = Role::notSuper()->get();

      return view('admin.manage.ayuda.create', compact('roles'));
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
        'roles' => 'nullable',
        'titulo' => 'required|string|max:250',
        'contenido' => 'required|string',
        'video' => 'nullable|string|size:11',
        'status' => 'nullable|boolean',
      ]);

      $ayuda = new Ayuda($request->only('titulo', 'contenido', 'video', 'status'));
      $ayuda->status = $request->status == '1';

      if($ayuda->save()){
        $ayuda->roles()->attach($request->roles);

        return redirect()->route('admin.manage.ayuda.show', ['ayuda' => $ayuda->id])->with([
          'flash_message' => 'Ayuda agregada exitosamente.',
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
     * Display the specified resource.
     *
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function show(Ayuda $ayuda)
    {
      return view('admin.manage.ayuda.show', compact('ayuda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function edit(Ayuda $ayuda)
    {
      $roles = Role::notSuper()->get();

      return view('admin.manage.ayuda.edit', compact('ayuda', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ayuda $ayuda)
    {
      $this->validate($request, [
        'roles' => 'required',
        'titulo' => 'required|string|max:250',
        'contenido' => 'required|string',
        'video' => 'nullable|string|size:11',
        'status' => 'nullable|boolean',
      ]);

      $ayuda->fill($request->only('titulo', 'contenido', 'video'));
      $ayuda->status = $request->status == '1';

      if($ayuda->save()){
        $ayuda->roles()->sync($request->roles);
        return redirect()->route('admin.manage.ayuda.show', ['ayuda' => $ayuda->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Ayuda modificada exitosamente.',
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
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ayuda $ayuda)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
        ]);
      }

      if($ayuda->delete()){
        return redirect()->route('admin.manage.ayuda.index')->with([
          'flash_message' => 'Ayuda eliminada exitosamente.',
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
