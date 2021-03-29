@extends('layouts.app')

@section('title', 'Requisitos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Requisitos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.show', ['contrato' => $contrato->id]) }}">Contrato</a></li>
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
          <h5>Agregar requisito</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.requisito.store', ['contrato' => $contrato->id, 'type' => $type]) }}" method="POST">
            @csrf

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label class="control-label" for="nombre">Nombre: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
            </div>

            <div class="form-group{{ $errors->has('carpeta') ? ' has-error' : '' }}">
              <label class="control-label" for="carpeta">Es carpeta:</label>
              <div class="custom-control custom-switch" title="Seleccione si el requisito es una carpeta">
                <input id="carpeta" class="custom-control-input" type="checkbox" name="carpeta" value="1"{{ old('carpeta') ? ' checked' : '' }}>
                <label class="custom-control-label" for="carpeta">Carpeta</label>
              </div>
              <small class="text-muted">Una vez creado el requisito, no se podrá cambiar esta opción.</small>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
