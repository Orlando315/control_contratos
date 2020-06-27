@extends('layouts.app')

@section('title', 'Anticipos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('anticipos.index') }}">Anticipos</a></li>
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
          <h5>Editar anticipo</h5>          
        </div>
        <div class="ibox-content">
          <form action="{{ route('anticipos.update', ['anticipo' => $anticipo->id]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  <label class="control-label" for="fecha">Fecha: *</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha', $anticipo->fecha) }}" placeholder="dd-mm-yyyy" required>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('anticipo') ? ' has-error' : '' }}">
                  <label class="control-label" for="anticipo">Anticipo: *</label>
                  <input id="anticipo" class="form-control" type="number" step="1" min="1" maxlength="999999" name="anticipo" value="{{ old('anticipo', $anticipo->anticipo) }}" placeholder="Anticipo" required>
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
              <a class="btn btn-default btn-sm" href="{{ url()->previous() }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#fecha').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });
    });
  </script>
@endsection
