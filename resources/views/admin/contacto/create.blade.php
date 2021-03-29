@extends('layouts.app')

@section('title', 'Contactos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contactos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Contactos</li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar contacto</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.contacto.store', ['id' => $model->id, 'type' => $type]) }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono: *</label>
                  <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono') }}" placeholder="Teléfono" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email:</label>
                  <input id="email" class="form-control" type="email" name="email" maxlength="50" value="{{ old('email') }}" placeholder="Email">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('cargo') ? ' has-error' : '' }}">
                  <label for="cargo">Cargo:</label>
                  <input id="cargo" class="form-control" type="text" name="cargo" maxlength="50" value="{{ old('cargo') }}" placeholder="Cargo">
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion') }}" placeholder="Descripción">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.'.$type.'.show', [$type => $model->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
