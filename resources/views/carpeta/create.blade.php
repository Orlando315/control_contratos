@extends('layouts.app')
@section('title', 'Carpetas - '.config('app.name'))
@section('header', 'Carpetas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ $type == 'contrato' ? route('contratos.show', ['contrato' => $model->id]) : route('empleados.show', ['empleado' => $model->id]) }}">Carpetas</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('carpeta.store', ['type' => $type, 'id' => $model->id, 'carpeta' => optional($carpeta)->id]) }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar carpeta</h4>

        <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
          <label class="control-label" for="nombre">Nombre: *</label>
          <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre de la carpeta" required>
        </div>

        @if (count($errors) > 0)
        <div class="alert alert-danger alert-important">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>  
        </div>
        @endif

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ $type == 'contrato' ? route('contratos.show', ['contrato' => $model->id]) : route('empleados.show', ['empleado' => $model->id]) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
