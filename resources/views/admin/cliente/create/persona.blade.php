@extends('layouts.app')

@section('title', 'Clientes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Clientes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cliente.index') }}">Clientes</a></li>
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
          <h5>Agregar cliente - Persona</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.cliente.store', ['type' => 'persona']) }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="100" value="{{ old('nombre') }}" placeholder="Nombre" required>
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
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dkK])$" value="{{ old('rut') }}" placeholder="RUT" required>
                  <small class="form-text text-muted">Ejemplo: 00000000-0</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email:</label>
                  <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email') }}" placeholder="Email">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('ciudad') ? ' has-error' : '' }}">
                  <label for="ciudad">Ciudad:</label>
                  <input id="ciudad" class="form-control" type="text" name="ciudad" maxlength="50" value="{{ old('ciudad') }}" placeholder="Ciudad">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('comuna') ? ' has-error' : '' }}">
                  <label for="comuna">Comuna:</label>
                  <input id="comuna" class="form-control" type="text" name="comuna" maxlength="50" value="{{ old('comuna') }}" placeholder="Comuna">
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
              <label for="direccion">Dirección:</label>
              <input id="direccion" class="form-control" type="text" name="direccion" maxlength="200" value="{{ old('direccion') }}" placeholder="Dirección">
            </div>

            <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion') }}" placeholder="Descripción">
            </div>

            <div class="form-group">
              <label for="">Proveedor:</label>
              <div class="custom-control custom-checkbox">
                <input id="proveedor" class="custom-control-input" type="checkbox" name="proveedor" value="1"{{ old('proveedor') == '1' ? ' checked' : '' }}>
                <label class="custom-control-label" for="proveedor">
                  Es proveedor
                </label>
              </div>
              <small class="form-text text-muted">Se creará un registro de Proveedor usando la misma información</small>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.cliente.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
