@extends('layouts.app')
@section('title', 'Ayudas - '.config( 'app.name'))
@section('header','Ayudas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('ayudas.index') }}">Ayudas</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('ayudas.store') }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar ayuda</h4>

        <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
          <label class="control-label" for="titulo">Título: *</label>
          <input id="titulo" class="form-control" type="text" name="titulo" maxlength="50" value="{{ old('titulo') ?? '' }}" placeholder="Título" required>
        </div>

        <div class="form-group {{ $errors->has('contenido') ? 'has-error' : '' }}">
          <label class="control-label" for="contenido">Contenido:</label>
          <textarea id="contenido" class="form-control" name="contenido" maxlength="255" rows="3">{{ old('contenido') ?? '' }}</textarea>
        </div>

        <div class="form-group {{ $errors->has('video') ? 'has-error' : '' }}">
          <label class="control-label" for="video">Video:</label>
          <input id="video" class="form-control" type="text" name="video" value="{{ old('video') ?? '' }}"  placeholder="https://www.youtube.com/embed/<ID>">
          <span class="help-block">Incluir solo el ID del video.</span>
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
          <a class="btn btn-flat btn-default" href="{{ route('ayudas.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
