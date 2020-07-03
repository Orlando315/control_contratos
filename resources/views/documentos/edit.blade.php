@extends('layouts.app')

@section('title', 'Documentos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Documentos</li>
        <li class="breadcrumb-item active"><strong>Edit</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar documento</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('documentos.update', ['documento' => $documento->id]) }}" method="POST" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $documento->nombre) }}" placeholder="Nombre" required>
                </div>
              </div>

              @if(!$documento->isType('App\TransporteAdjunto'))
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('vencimiento') ? ' has-error' : '' }}">
                    <label for="vencimiento">Vencimiento:</label>
                    <input id="vencimiento" class="form-control" type="text" name="vencimiento" value="{{ old( 'vencimiento', $documento->vencimiento) }}" placeholder="dd-mm-yyyy">
                  </div>
                </div>
              @endif
            </div>

            <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
              <label for="observacion">Obervación:</label>
              <input id="observacion" class="form-control" type="text" name="observacion" maxlength="100" value="{{ old('observacion', $documento->observacion) }}" placeholder="Observación">
            </div>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>  
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ $documento->backUrl }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#vencimiento').datepicker({
        format: 'dd-mm-yyyy',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });
    });
  </script>
@endsection
