@extends('layouts.app')
@section('title', 'Variables - '.config('app.name'))
@section('header', 'Variables')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('plantilla.index') }}">Variables</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection

@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form  action="{{ route('variable.store') }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar variable</h4>

        <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
          <label class="control-label" for="nombre">Nombre de la variable: *</label>
          <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
        </div>

        <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
          <label class="control-label" for="valor">Tipo: *</label>
          <select id="tipo" class="form-control" name="tipo" required>
            <option value="text"{{ old('tipo') == 'text' ? ' selected' : '' }}>Texto</option>
            <option value="date"{{ old('tipo') == 'date' ? ' selected' : '' }}>Fecha</option>
            <option value="number"{{ old('tipo') == 'number' ? ' selected' : '' }}>Numerico</option>
            <option value="email"{{ old('tipo') == 'email' ? ' selected' : '' }}>Email</option>
            <option value="rut"{{ old('tipo') == 'rut' ? ' selected' : '' }}>RUT</option>
            <option value="firma"{{ old('tipo') == 'firma' ? ' selected' : '' }}>Firma</option>
          </select>
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
          <a class="btn btn-flat btn-default" href="{{ route('plantilla.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
