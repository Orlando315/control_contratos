@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.empresa.show', ['empresa' => $user->empresa->id]) }}">Usuarios</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar usuario</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.manage.user.update', ['user' => $user->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
              <label>Role: *</label>
              <div class="row">
                @foreach($roles as $role)
                  @continue($role->name == 'empleado')

                  <div class="col-md-6">
                    <div class="custom-control custom-radio">
                      <input id="role-{{ $role->name }}" class="custom-control-input" type="radio" name="role" value="{{ $role->name }}"{{ $user->hasActiveOrInactiveRole($role->name) ? ' checked' : '' }} required>
                      <label for="role-{{ $role->name }}" class="custom-control-label">{{ $role->name() }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" value="{{ old('rut', $user->rut) }}" placeholder="RUT" required>
                  <small class="form-text text-muted">Ejemplo: 00000000-0</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                  <label for="nombres">Nombres: *</label>
                  <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres', $user->nombres) }}" placeholder="Nombres" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                  <label for="apellidos">Apellidos:</label>
                  <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos', $user->apellidos) }}" placeholder="Apellidos">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono:</label>
                  <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono', $user->telefono) }}" placeholder="Teléfono">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email:</label>
                  <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email', $user->email) }}" placeholder="Email">
                </div>
              </div>
            </div>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.manage.user.show', ['user' => $user->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
