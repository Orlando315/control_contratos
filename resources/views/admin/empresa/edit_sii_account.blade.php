@extends('layouts.app')

@section('title', 'Empresa')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresa</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empresa.perfil') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empresa.configuracion') }}">Configuración</a></li>
        <li class="breadcrumb-item">Usuario Sii</li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-random"></i> Editar usuario</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.empresa.configuracion.sii.account.update') }}" method="POST">
            @method('PATCH')
            @csrf

            <p class="text-center">Debe iniciar sesión con una cuenta de Facturación Sii ya existente. El resto de la información se obtendrá automáticamente.</p>

            <fieldset>
              <legend class="form-legend">Datos del Usuario</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group"{{ $errors->has('email') ? ' has-error' : '' }}>
                    <label for="sii_email">Email: *</label>
                    <input id="sii_email" class="form-control" type="email" name="email" value="{{ old('email', $configuracion->sii_account->email) }}" maxlength="150" placeholder="Email" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group"{{ $errors->has('password') ? ' has-error' : '' }}>
                    <label for="sii_password">Contraseña: *</label>
                    <input id="sii_password" class="form-control" type="password" name="password" minlength="6" placeholder="Contraseña" required>
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="alert alert-danger alert-important"{!! $errors->any() ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.empresa.configuracion') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

