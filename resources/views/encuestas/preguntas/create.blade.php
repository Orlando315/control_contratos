@extends('layouts.app')
@section('title', 'Preguntas - '.config( 'app.name'))
@section('header','Preguntas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('encuestas.show', ['encuesta' => $encuesta->id]) }}">Encuesta</a></li>
    <li>Preguntas</li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('preguntas.store', ['encuesta' => $encuesta->id]) }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar pregunta</h4>

        <div class="form-group {{ $errors->has('pregunta') ? 'has-error' : '' }}">
          <label class="control-label" for="pregunta">Pregunta: *</label>
          <input id="pregunta" class="form-control" type="text" name="pregunta" maxlength="100" value="{{ old('pregunta') ? old('pregunta') : '' }}" placeholder="Pregunta" required>
        </div>

        <legend>Opciones</legend>
        <p class="help-text">Se deben agregar al menos 2 opciones</p>
        <div class="form-group {{ $errors->has('opciones.1') ? 'has-error' : '' }}">
          <label class="control-label" for="opcion1">Opción 1:</label>
          <input id="opcion1" class="form-control" type="text" name="opciones[1]" maxlength="100" value="{{ old('opciones.1') ?? '' }}" placeholder="Opción 1" required>
        </div>
        <div class="form-group {{ $errors->has('opciones.2') ? 'has-error' : '' }}">
          <label class="control-label" for="opcion2">Opción 2:</label>
          <input id="opcion2" class="form-control" type="text" name="opciones[2]" maxlength="100" value="{{ old('opciones.2') ?? '' }}" placeholder="Opción 2" required>
        </div>
        <div class="form-group {{ $errors->has('opciones.3') ? 'has-error' : '' }}">
          <label class="control-label" for="opcion3">Opción 3:</label>
          <input id="opcion3" class="form-control" type="text" name="opciones[3]" maxlength="100" value="{{ old('opciones.3') ?? '' }}" placeholder="Opción 3">
        </div>
        <div class="form-group {{ $errors->has('opciones.4') ? 'has-error' : '' }}">
          <label class="control-label" for="opcion4">Opción 4:</label>
          <input id="opcion4" class="form-control" type="text" name="opciones[4]" maxlength="100" value="{{ old('opciones.4') ?? '' }}" placeholder="Opción 4">
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
          <a class="btn btn-flat btn-default" href="{{ route('encuestas.show', ['encuesta' => $encuesta->id]) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
