@extends('layouts.app')
@section('title', 'Editar - '.config('app.name'))
@section('header', 'Editar')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('encuestas.show', ['encuesta' => $pregunta->encuesta_id]) }}">Encuesta</a></li>
    <li>Preguntas</li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('preguntas.update', ['pregunta' => $pregunta->id]) }}" method="POST">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar pregunta</h4>

        <div class="form-group {{ $errors->has('pregunta') ? 'has-error' : '' }}">
          <label class="control-label" for="pregunta">Pregunta: *</label>
          <input id="pregunta" class="form-control" type="text" name="pregunta" maxlength="100" value="{{ old('pregunta') ? old('pregunta') : $pregunta->pregunta }}" placeholder="Pregunta" required>
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
          <a class="btn btn-flat btn-default" href="{{ route('preguntas.show', ['pregunta' => $pregunta->id]) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
