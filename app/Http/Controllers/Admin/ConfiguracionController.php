<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Empresa, EmpresaConfiguracion, User};

class ConfiguracionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('role:developer|superadmin|empresa');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function configuracion()
    {
      $configuracion = Auth::user()->empresa->configuracion;

      return view('admin.empresa.configuracion', compact('configuracion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function general(Request $request)
    {
      $this->validateWithBag('general', $request, [
        'jornada' => 'required',
        'dias_vencimiento' => 'nullable|integer|min:1|max:255',
      ]);

      Auth::user()->empresa->configuracion->fill($request->only('jornada', 'dias_vencimiento'));

      if(Auth::user()->empresa->push()){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Configuracion modificada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sii(Request $request)
    {
      $this->validateWithBag('sii', $request, [
        'sii_clave' => 'nullable|string|max:120',
        'sii_clave_certificado' => 'nullable|string|max:150',
        'sii_firma' => 'nullable|string|max:120',
      ]);

      $empresa = Auth::user()->empresa;
      $empresa->configuracion->fill($request->only('sii_clave', 'sii_clave_certificado'));
      $empresa->configuracion->firma = $request->sii_firma;

      if($empresa->push()){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Configuracion modificada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function terminos(Request $request)
    {
      $this->validateWithBag('terminos', $request, [
        'terminos.status' => 'nullable|boolean',
        'terminos.terminos' => 'nullable|string',
      ]);

      Auth::user()->empresa->configuracion->terminos = $request->terminos;

      if(Auth::user()->empresa->push()){
        return redirect()->back()->with([
          'flash_class'   => 'alert-success',
          'flash_message' => 'Configuracion modificada exitosamente.',
        ]);
      }

      return redirect()->back()->withInput()->with([
        'flash_class'     => 'alert-danger',
        'flash_message'   => 'Ha ocurrido un error.',
        'flash_important' => true
      ]);
    }
}
