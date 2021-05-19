@extends('layouts.app')

@section('title', 'Partidas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Partidas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contrato.show', ['contrato' => $contrato->id]) }}">Partidas</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar partida</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.partida.store', ['contrato' => $contrato->id]) }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label for="tipo">Tipo: *</label>
                  <select id="tipo" class="custom-select" name="tipo" required>
                    @foreach($tipos as $tipo)
                      <option value="{{ $tipo }}"{{ old('tipo', 'otro') == $tipo ? ' selected' : '' }}>{{ ucfirst($tipo) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('codigo') ? ' has-error' : '' }}">
                  <label class="control-label" for="codigo">Código: *</label>
                  <input id="codigo" class="form-control" type="text" name="codigo" maxlength="50" value="{{ old('codigo') }}" placeholder="Código" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('monto') ? ' has-error' : '' }}">
                  <label for="monto">Monto: *</label>
                  <input id="monto" class="form-control" type="number" name="monto" min="0" max="999999999999" step="0.01" value="{{ old('monto') }}" placeholder="Monto" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="100" value="{{ old('descripcion') }}" placeholder="Descripción">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.contrato.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
