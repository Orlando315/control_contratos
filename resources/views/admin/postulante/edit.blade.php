@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Postulantes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empleado.index') }}">Postulantes</a></li>
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
          <h5>Editar postulante</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.postulante.update', ['postulante' => $postulante->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                  <label for="nombres">Nombres: *</label>
                  <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres', $postulante->nombres) }}" placeholder="Nombres" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                  <label for="apellidos">Apellidos:</label>
                  <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos', $postulante->apellidos) }}" placeholder="Apellidos">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" value="{{ old('rut', $postulante->rut) }}" placeholder="RUT" required>
                  <span class="help-block">Ejemplo: 00000000-0</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono:</label>
                  <input id="telefono" class="form-control" type="text" name="telefono" maxlength="20" value="{{ old('telefono', $postulante->telefono) }}" placeholder="Teléfono">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email:</label>
                  <input id="email" class="form-control" type="email" name="email" maxlength="50" value="{{ old('email', $postulante->email) }}" placeholder="Email">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.postulante.show', ['postulante' => $postulante->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
