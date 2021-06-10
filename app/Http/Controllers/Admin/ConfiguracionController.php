<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use App\{User, Contrato};

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
      $users = Auth::user()->empresa->users;
      $contratos = Contrato::all();

      return view('admin.empresa.configuracion', compact('configuracion', 'users', 'contratos'));
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
        'contrato_principal' => 'required',
      ]);

      $contrato = Contrato::find($request->contrato_principal);
      Auth::user()->empresa->configuracion->fill($request->only('jornada', 'dias_vencimiento'));

      if(Auth::user()->empresa->push()){
        $contrato->setAsMain();

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function covid19(Request $request)
    {
      $this->validateWithBag('covid19', $request, [
        'covid19.status' => 'nullable|boolean',
      ]);

      Auth::user()->empresa->configuracion->covid19 = $request->has('covid19') && $request->covid19['status'] == '1';

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
    public function requerimientos(Request $request)
    {
      $this->validateWithBag('firmantes', $request, [
        'usuarios' => 'required|min:1',
        'usuarios.*.usuario' => 'required',
        'usuarios.*.texto' => 'required|string|max:50',
        'usuarios.*.obligatorio' => 'nullable|boolean',
      ]);

      $usuarios = [];

      foreach ($request->usuarios as $usuario) {
        $user = User::find($usuario['usuario']);

        if($user){
          $usuarios[] = [
            'usuario' => $user->id,
            'nombre' => $user->nombre(),
            'texto' => trim($usuario['texto']),
            'obligatorio' => $usuario['obligatorio'] == 1,
          ];
        }
      }

      Auth::user()->empresa->configuracion->requerimientos_firmantes = $usuarios;

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
     * Asociar Usuario de Facturacion Sii
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function siiLogin(Request $request)
    {
      $this->authorize('createSsiAccount', Auth::user()->empresa->configuracion);
      $this->validateWithBag('sii', $request, [
        'email' => 'required|email|max:50',
        'password' => 'required|string|min:6',
      ]);

      try{
        $data = sii(null, false)->checkLogin($request->email, $request->password);

        $configuracion = Auth::user()->empresa->configuracion;
        $configuracion->sii_account = [
          'id' => $data['id'],
          'username' => $data['username'],
          'email' => $request->email,
          'password' => $request->password,
        ];
        $configuracion->save();

        activityLog()
        ->event('created')
        ->on($configuracion)
        ->by(Auth::user())
        ->withProperties([
          'id' => $data['id'],
          'username' => $data['username'],
          'email' => $request->email,
        ])
        ->log('Cuenta enlazada con Facturación Sii.');

        return response()->json(collect($data)->except('password'));
      }catch(\Exception $e){
        Log::error($e->getMessage(), [$e->getCode(), $e->getFile(), $e->getLine()]);

        return response()->json([$e->getMessage()], 400);
      }
    }

    /**
     * Crear cuenta de Usuario de Facturacion Sii
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function siiAccount(Request $request)
    {
      $this->authorize('createSsiAccount', Auth::user()->empresa->configuracion);
      $this->validateWithBag('sii', $request, [
        'username' => 'required|string|min:3|max:25',
        'email' => 'required|email|max:50',
        'password' => 'required|string|min:6|confirmed',
      ]);

      try{
        $data = sii(null, false)->registerUser($request->username, $request->email, $request->password);

        $configuracion = Auth::user()->empresa->configuracion;
        $configuracion->sii_account = $data;
        $configuracion->save();

        activityLog()
        ->event('created')
        ->on($configuracion)
        ->by(Auth::user())
        ->withProperties(collect($data)->except('password'))
        ->log('Cuenta creada en Facturación Sii.');

        return response()->json(collect($data)->except('password'));
      }catch(\Exception $e){
        Log::error($e->getMessage(), [$e->getCode(), $e->getFile(), $e->getLine()]);

        return response()->json([$e->getMessage()], 400);
      }
    }

    /**
     * Formulario para editar la cuenta de Facturacion Sii
     * 
     * @return \Illuminate\Http\Response
     */
    public function editSiiAccount()
    {
      $this->authorize('editSsiAccount', Auth::user()->empresa->configuracion);

      $configuracion = Auth::user()->empresa->configuracion;

      return view('admin.empresa.edit_sii_account', compact('configuracion'));
    }

    /**
     * Acutlizar información de la cuenta de Usuario de Facturacion Sii
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSiiAccount(Request $request)
    {
      $this->authorize('editSsiAccount', Auth::user()->empresa->configuracion);
      $this->validateWithBag('sii', $request, [
        'email' => 'required|email|max:50',
        'password' => 'required|string|min:6|confirmed',
      ]);

      try{
        $data = sii(null, false)->checkLogin($request->email, $request->password);

        $configuracion = Auth::user()->empresa->configuracion;
        $configuracion->sii_account = [
          'id' => $data['id'],
          'username' => $data['username'],
          'email' => $request->email,
          'password' => $request->password,
        ];
        $configuracion->save();

        activityLog()
        ->event('updated')
        ->on($configuracion)
        ->by(Auth::user())
        ->withProperties([
          'id' => $data['id'],
          'username' => $data['username'],
          'email' => $request->email,
        ])
        ->log('Cuenta de Facturación Sii actualizada.');

        return redirect()->route('admin.empresa.configuracion')->with([
          'flash_class'     => 'alert-success',
          'flash_message'   => 'Cuenta de Facturación Sii actualizada exitosamente.',
          'flash_important' => true
        ]);
      }catch(\Exception $e){
        Log::error($e->getMessage(), [$e->getCode(), $e->getFile(), $e->getLine()]);

        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => $e->getMessage(),
          'flash_important' => true
        ]);
      }
    }

    /**
     * Agregar Representante Legal de Facturacion Sii
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function siiRepresentante(Request $request)
    {
      $this->authorize('createSsiRepresentante', Auth::user()->empresa->configuracion);
      $this->validateWithBag('sii', $request, [
        'rut' => 'required|string|min:3|max:25',
        'clave_sii' => 'required|string',
        'clave_certificado_digital' => 'required|string',
        'vencimiento_certificado' => 'nullable|date_format:d-m-Y',
      ]);

      try{
        $data = sii()->registerRut($request->rut, $request->clave_sii, $request->clave_certificado_digital);

        $data['vencimiento_certificado'] = $request->vencimiento_certificado;
        $configuracion = Auth::user()->empresa->configuracion;
        $configuracion->sii_representante = $data;
        $configuracion->save();

        activityLog()
        ->event('created')
        ->on($configuracion)
        ->by(Auth::user())
        ->withProperties(collect($data)->except(['password', 'certificatePassword']))
        ->log('Representante agregado en Facturación Sii.');

        return response()->json(collect($data)->except(['password', 'certificatePassword']));
      }catch(\Exception $e){
        Log::error($e->getMessage(), [$e->getCode(), $e->getFile(), $e->getLine()]);

        return response()->json([$e->getMessage()], 400);
      }
    }

    /**
     * Formulario para editar la informacion del Representante de Facturacion Sii
     * 
     * @return \Illuminate\Http\Response
     */
    public function editSiiRepresentante()
    {
      $this->authorize('editSsiRepresentante', Auth::user()->empresa->configuracion);

      $configuracion = Auth::user()->empresa->configuracion;

      return view('admin.empresa.edit_sii_representante', compact('configuracion'));
    }

    /**
     * Actualizar Representante Legal de Facturacion Sii
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSiiRepresentante(Request $request)
    {
      $this->authorize('editSsiRepresentante', Auth::user()->empresa->configuracion);
      $this->validateWithBag('sii', $request, [
        'rut' => 'required|string|min:3|max:25',
        'clave_sii' => 'required|string',
        'clave_certificado_digital' => 'required|string',
        'vencimiento_certificado' => 'nullable|date_format:d-m-Y',
      ]);

      $configuracion = Auth::user()->empresa->configuracion;      

      try{
        $data = sii()->updateRut(
          $configuracion->sii_representante->id,
          $request->rut,
          $request->clave_sii,
          $request->clave_certificado_digital
        )
;
        $data['vencimiento_certificado'] = $request->vencimiento_certificado;
        $configuracion->sii_representante = $data;
        $configuracion->save();

        activityLog()
        ->event('updated')
        ->on($configuracion)
        ->by(Auth::user())
        ->withProperties(collect($data)->except(['password', 'certificatePassword']))
        ->log('Representante de Facturación Sii modificado.');

        return redirect()->route('admin.empresa.configuracion')->with([
          'flash_class'     => 'alert-success',
          'flash_message'   => 'Representante de Facturación Sii actualizado exitosamente.',
          'flash_important' => true
        ]);
      }catch(\Exception $e){
        Log::error($e->getMessage(), [$e->getCode(), $e->getFile(), $e->getLine()]);

        return redirect()->back()->withInput()->with([
          'flash_class'     => 'alert-danger',
          'flash_message'   => $e->getMessage(),
          'flash_important' => true
        ]);
      }
    }
}
