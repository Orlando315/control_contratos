<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Usuario;
use App\Empresa;
use App\ConfiguracionEmpresa;

class EmpresasController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('empresas.create');
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
        'usuario' => 'required|alpha_num|unique:users,usuario',
        'nombres' => 'required|string',
        'rut' => 'required|string|unique:users,rut',
        'representante' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'telefono' => 'required',
        'jornada' => 'required',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required'
      ]);

      $empresa = new Empresa($request->all());

      if($empresa->save()){

        $user = new User($request->all());
        $user->tipo = 1;
        $user->password = bcrypt($request->input('password'));
        $empresa->usuario()->save($user);

        $empresa->configuracion()->create($request->all());        

        return redirect('login')->with([
          'flash_message' => 'Registro completado con exito.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('login')->with([
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
      return view('empresas.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $this->validate($request, [
        'usuario' => 'required|alpha_num|unique:users,usuario,' . Auth::user()->id . ',id',
        'nombres' => 'required|string',
        'rut' => 'required|string|unique:users,rut,' . Auth::user()->id . ',id',
        'representante' => 'required|string',
        'email' => 'required|email|unique:users,email,' . Auth::user()->id . ',id',
        'telefono' => 'required',
        'jornada' => 'required'
      ]);

      $empresa = Empresa::find(Auth::user()->empresa->id);
      $empresa->fill($request->all());
      $usuario = Usuario::find(Auth()->user()->id);
      $usuario->fill($request->all());

      if($empresa->save()){
        $usuario->save();

        return redirect('perfil')->with([
          'flash_message' => 'Perfil modificado exitosamente.',
          'flash_class' => 'alert-success'
          ]);
      }else{
        return redirect('perfil')->with([
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
    public function destroy($id)
    {
        //
    }
}
