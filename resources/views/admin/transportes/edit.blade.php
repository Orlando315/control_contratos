@extends('layouts.app')

@section('title', 'Editar')

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
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transportes.index') }}">Transportes</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Editar transporte</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.transportes.update', ['transporte' => $transporte->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('patente') ? ' has-error' : '' }}">
                  <label for="patente">Patente: *</label>
                  <input id="patente" class="form-control" type="text" name="patente" maxlength="50" value="{{ old('patente', $transporte->patente) }}" placeholder="Patente" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contratos') ? ' has-error' : '' }}">
                  <label for="contratos">Contratos:</label>
                  <select id="contratos" class="form-control" name="contratos[]" multiple="multiple">
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ $transporte->contratos->contains($contrato->id) ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('supervisores') ? ' has-error' : '' }}">
                  <label for="supervisores">Supervisores:</label>
                  <select id="supervisores" class="form-control" name="supervisores[]" multiple="multiple">
                    <option value="">Seleccione...</option>
                    @foreach($supervisores as $supervisor)
                      <option value="{{ $supervisor->id }}"{{ $transporte->supervisores->contains($supervisor->id) ? ' selected' : '' }}>{{ $supervisor->rut }} | {{ $supervisor->nombre() }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('faenas') ? ' has-error' : '' }}">
                  <label for="faenas">Faenas:</label>
                  <select id="faenas" class="form-control" name="faenas[]" multiple="multiple">
                    <option value="">Seleccione...</option>
                    @foreach($faenas as $faena)
                      <option value="{{ $faena->id }}"{{ $transporte->faenas->contains($faena->id) ? ' selected' : '' }}>{{ $faena->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('modelo') ? ' has-error' : '' }}">
                  <label for="modelo">Modelo:</label>
                  <input id="modelo" class="form-control" type="text" name="modelo" maxlength="50" value="{{ old('modelo', $transporte->modelo) }}" placeholder="Modelo">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('marca') ? ' has-error' : '' }}">
                  <label for="marca">Marca:</label>
                  <input id="marca" class="form-control" type="text" name="marca" maxlength="50" value="{{ old('marca', $transporte->marca) }}" placeholder="Marca">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                  <label for="color">Color:</label>
                  <input id="color" class="form-control" type="text" name="color" maxlength="50" value="{{ old('color', $transporte->color) }}" placeholder="Color">
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="100" value="{{ old('descripcion', $transporte->vehiculo) }}" placeholder="Descripción">
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

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#contratos, #supervisores, #faenas').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })
    });
  </script>
@endsection
