@extends('layouts.app')
@section('title', 'Etiquetas - '.config('app.name'))
@section('header','Etiquetas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('etiquetas.index') }}">Etiquetas</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('etiquetas.store') }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar etiqueta</h4>

        <div class="form-group {{ $errors->has('etiqueta') ? 'has-error' : '' }}">
          <label class="control-label" for="etiqueta">Nombre: *</label>
          <input id="etiqueta" class="form-control" type="text" name="etiqueta" maxlength="50" value="{{ old('etiqueta') ?? '' }}" placeholder="Etiqueta" required>
        </div>

        @if(count($errors) > 0)
          <div class="alert alert-danger alert-important">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ route('etiquetas.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
