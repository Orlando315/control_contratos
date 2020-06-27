@extends('layouts.app')

@section('title', 'Contratos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">Contratos</a></li>
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
          <h5>Agregar contrato</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('contratos.store') }}" method="POST">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
                  <label for="valor">Valor: *</label>
                  <input id="valor" class="form-control" type="number" step="1" min="1" max="9999999999999" name="valor" value="{{ old('valor') }}" placeholder="Valor" required>
                </div>                
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('inicio') ? ' has-error' : '' }}">
                  <label for="inicio">Inicio: *</label>
                  <input id="inicio" class="form-control" type="text" name="inicio" value="{{ old('inicio') }}" placeholder="dd-mm-yyyy" required>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fin') ? ' has-error' : '' }}">
                  <label for="fin">Fin: *</label>
                  <input id="fin" class="form-control" type="text" name="fin" value="{{ old('fin') }}" placeholder="dd-mm-yyyy" required>
                </div>                
              </div>
            </div>

            <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="150" value="{{ old('descripcion') }}" placeholder="Descripción">
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
              <a class="btn btn-default btn-sm" href="{{ route('contratos.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#inicio, #fin').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });
    });
  </script>
@endsection
