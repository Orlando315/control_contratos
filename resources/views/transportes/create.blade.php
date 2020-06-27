@extends('layouts.app')

@section('title', 'Transportes')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Transportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('transportes.index') }}">Transportes</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Agregar transporte</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('transportes.store') }}" method="POST">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('supervisor') ? ' has-error' : '' }}">
                  <label for="supervisor">Supervisor: *</label>
                  <select id="supervisor" class="form-control" name="supervisor" required>
                    <option value="">Seleccione...</option>
                    @foreach($usuarios as $usuario)
                      <option value="{{ $usuario->id }}" {{ old('usuario') == $usuario->id ? 'selected':'' }}>{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
              <div class="form-group{{ $errors->has('vehiculo') ? ' has-error' : '' }}">
                <label for="vehiculo">Vehiculo: *</label>
                <input id="vehiculo" class="form-control" type="text" name="vehiculo" maxlength="50" value="{{ old('vehiculo') }}" placeholder="Vehiculo" required>
              </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('patente') ? ' has-error' : '' }}">
                  <label for="patente">Patente: *</label>
                  <input id="patente" class="form-control" type="text" name="patente" maxlength="50" value="{{ old('patente') }}" placeholder="Patente" required>
                </div>
              </div>
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
              <a class="btn btn-default btn-sm" href="{{ route('transportes.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#contrato, #supervisor').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })
    });
  </script>
@endsection
