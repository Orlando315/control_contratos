@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Transportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transportes.index') }}">Transportes</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Editar transporte</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.transportes.update', ['transporte' => $transporte->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('vehiculo') ? ' has-error' : '' }}">
              <label for="vehiculo">Vehiculo: *</label>
              <input id="vehiculo" class="form-control" type="text" name="vehiculo" maxlength="50" value="{{ old('vehiculo', $transporte->vehiculo) }}" placeholder="Vehiculo" required>
            </div>

            <div class="form-group{{ $errors->has('patente') ? ' has-error' : '' }}">
              <label for="patente">Patente: *</label>
              <input id="patente" class="form-control" type="text" name="patente" maxlength="50" value="{{ old('patente',  $transporte->patente) }}" placeholder="Patente" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.transportes.show', ['transporte' => $transporte->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
