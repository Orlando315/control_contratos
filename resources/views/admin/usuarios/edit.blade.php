@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
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
          <h5>Editar administrador</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.usuarios.update', ['usuario' => $usuario->id]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}


            <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
              <label for="nombres">Nombres: *</label>
              <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres', $usuario->nombres) }}" placeholder="Nombres" required>
            </div>

            <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
              <label for="apellidos">Apellidos: *</label>
              <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos', $usuario->apellidos) }}" placeholder="Apellidos" required>
            </div>

            <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
              <label for="rut">RUT: *</label>
              <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="\d{4,9}-[\dk])$" value="{{ old('rut', $usuario->rut) }}" placeholder="RUT" required>
              <span class="help-block">Ejemplo: 00000000-0</span>
            </div>

            <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
              <label for="telefono">Teléfono:</label>
              <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono', $usuario->telefono) }}" placeholder="Teléfono">
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email">Email:</label>
              <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email', $usuario->email) }}" placeholder="Email">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.usuarios.show', ['usuario' => $usuario->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
