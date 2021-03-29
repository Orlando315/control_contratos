<?php

namespace App\Http\Controllers\Admin\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Auth, Storage};
use App\{Empresa, EmpresaConfiguracion, User, Role};

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $empresas = Empresa::all();

      return view('admin.manage.empresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.manage.empresa.create');
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
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:empresas',
        'razon_social' => 'required|string|max:50',
        'logo' => 'nullable|file|mimes:jpeg,png|max:3000',
        'jornada' => 'required',
        'representante_legal' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:50|unique:empresas',
        'rut_empresa' => 'nullable|boolean',
        'usuario_rut' => 'required_without:rut_empresa|regex:/^(\d{4,9}-[\dkK])$/|unique:users,rut',
        'nombres' => 'required|string|max:50',
        'apellidos' => 'nullable|string|max:50',
        'contraseña' => 'required|min:6|confirmed',
      ]);

      // Empresa
      $empresa = new Empresa($request->only('rut', 'telefono', 'email'));
      $empresa->representante = $request->representante_legal;
      $empresa->nombre = $request->razon_social;

      if($empresa->save()){
        // Configuracion
        $empresa->configuracion()->create($request->only('jornada'));

        // User
        $rut = $request->has('rut_empresa') ? $request->rut : $request->usuario_rut;
        $user = new User([
          'nombres' => $request->nombres,
          'apellidos' => $request->apellidos,
          'rut' => $rut,
          'telefono' => $request->telefono,
          'email' => $request->email,
        ]);
        $user->usuario = $rut;
        $user->password = bcrypt($request->input('contraseña'));
        $empresa->users()->save($user);

        $role = Role::firstWhere('name', 'empresa');
        $user->attachRole($role);

        if($request->hasFile('logo')){
          $directory = $empresa->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          $empresa->logo = $request->file('logo')->store($directory);
          $empresa->save();
        }

        return redirect()->route('admin.manage.empresa.show', ['empresa' => $empresa->id])->with([
          'flash_message' => 'Empresa agregada exitosamente.',
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
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
      $empresa->load('users');
      $empresa->loadCount(['users', 'contratos', 'empleados']);

      return view('admin.manage.empresa.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
      return view('admin.manage.empresa.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
      $this->validate($request, [
        'rut' => 'required|regex:/^(\d{4,9}-[\dkK])$/|unique:empresas,rut,'.$empresa->id.',id',
        'razon_social' => 'required|string|max:50',
        'logo' => 'nullable|file|mimes:jpeg,png|max:3000',
        'jornada' => 'required',
        'dias_vencimiento' => 'nullable|integer|min:1|max:255',
        'representante_legal' => 'required|string|max:50',
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:50|unique:empresas,email,'.$empresa->id.',id',
      ]);

      // Empresa
      $empresa->fill($request->only('rut', 'email', 'telefono'));
      $empresa->representante = $request->representante_legal;
      $empresa->nombre = $request->razon_social;
      // Configuracion
      $empresa->configuracion->fill($request->only('jornada', 'dias_vencimiento'));

      if($empresa->push()){
        if($request->hasFile('logo')){
          $directory = $empresa->directory;
          if(!Storage::exists($directory)){
            Storage::makeDirectory($directory);
          }

          if($empresa->logo && Storage::exists($empresa->logo)){
            Storage::delete($empresa->logo);
          }

          $empresa->logo = $request->file('logo')->store($directory);
          $empresa->save();
        }

        return redirect()->route('admin.manage.empresa.show', ['empresa' => $empresa->id])->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Empresa modificada exitosamente.',
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
     * @param  \App\Models\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Empresa $empresa)
    {
      if(!Hash::check($request->password, Auth::user()->password)){
        return redirect()->back()->with([
          'flash_message' => 'Contraseña incorrecta.',
          'flash_class' => 'alert-danger',
          'flash_important' => true
          ]);
      }

      if($empresa->delete()){
        return redirect()->route('admin.manage.empresa.index')->with([
          'flash_message' => 'Empresa eliminada exitosamente.',
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
