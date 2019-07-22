@extends('layouts.app')
@section('title', 'Editar - '.config('app.name'))
@section('header','Editar')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('gastos.index') }}">Etiquetas</a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('gastos.update', ['id' => $gasto->id]) }}" method="POST">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar gasto</h4>

        <div class="form-group {{ $errors->has('contrato_id') ? 'has-error' : '' }}">
          <label class="control-label" for="contrato_id">Contrato: *</label>
          <select id="contrato_id" class="form-control" name="contrato_id" required>
            <option value="">Seleccione...</option>
            @foreach($contratos as $contrato)
              <option value="{{ $contrato->id }}" {{ old('contrato_id') == $contrato->id ? 'selected' : $contrato->id == $gasto->contrato_id ? 'selected' : '' }}>{{ $contrato->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('etiqueta_id') ? 'has-error' : '' }}">
          <label class="control-label" for="etiqueta_id">Etiqueta: *</label>
          <select id="etiqueta_id" class="form-control" name="etiqueta_id">
            <option value="">Seleccione...</option>
            @foreach($etiquetas as $etiqueta)
              <option value="{{ $etiqueta->id }}" {{ old('etiqueta_id') == $etiqueta->id ? 'selected' : $etiqueta->id == $gasto->etiqueta_id ? 'selected' : '' }}>{{ $etiqueta->etiqueta }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('nombre') ? 'has-error' : '' }}">
          <label class="control-label" for="nombre">Nombre: *</label>
          <input id="nombre" class="form-control" type="text" name="nombre" maxlength="200" value="{{ old('nombre') ?? $gasto->nombre }}" placeholder="Nombre" required>
        </div>

        <div class="form-group {{ $errors->has('valor') ? 'has-error' : '' }}">
          <label class="control-label" for="valor">Nombre: *</label>
          <input id="valor" class="form-control" type="number" name="valor" min="0" max="9999999999999999999" value="{{ old('valor') ?? $gasto->valor }}" placeholder="Valor" required>
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
          <a class="btn btn-flat btn-default" href="{{ route('gastos.show', ['id' => $etiqueta->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
