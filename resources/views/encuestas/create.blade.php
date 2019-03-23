@extends('layouts.app')
@section('title', 'Encuestas - '.config( 'app.name'))
@section('header','Encuestas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('encuestas.index') }}">Encuestas</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('encuestas.store') }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar encuesta</h4>

        <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
          <label class="control-label" for="titulo">Título: *</label>
          <input id="titulo" class="form-control" type="text" name="titulo" maxlength="100" value="{{ old('titulo') ? old('titulo') : '' }}" placeholder="Título" required>
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
          <a class="btn btn-flat btn-default" href="{{ route('encuestas.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
