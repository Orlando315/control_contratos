@extends('layouts.app')

@section('title', 'Configuración')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresa</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empresa.perfil') }}">Empresas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empresa.configuracion') }}">Configuración</a></li>
        <li class="breadcrumb-item">Representante Sii</li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-random"></i> Editar representante</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.empresa.configuracion.sii.representante.update') }}" method="POST">
            @method('PATCH')
            @csrf

            <fieldset>
              <legend class="form-legend">Datos del Representante Legal</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group"{{ $errors->has('rut') ? ' has-error' : '' }}>
                    <label for="rut">RUT: *</label>
                    <input id="rut" class="form-control" type="text" name="rut" value="{{ old('rut', $configuracion->sii_representante->rut) }}" maxlength="11" pattern="^(\d{4,9}-[\dk])$" placeholder="RUT" required>
                    <span class="help-block">Ejemplo: 00000000-0</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group"{{ $errors->has('clave_sii') ? ' has-error' : '' }}>
                    <label for="clave_sii">Clave Sii: *</label>
                    <input id="clave_sii" class="form-control" type="password" name="clave_sii" placeholder="Clave Sii" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group"{{ $errors->has('clave_certificado_digital') ? ' has-error' : '' }}>
                    <label for="certificatePassword">Clave del certificado digital: *</label>
                    <input id="certificatePassword" class="form-control" type="password" name="clave_certificado_digital" placeholder="Clave del certificado digital" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group"{{ $errors->has('vencimiento_certificado') ? ' has-error' : '' }}>
                    <label for="vencimiento_certificado">Fecha de vencimiento del certificado:</label>
                    <input id="vencimiento_certificado" class="form-control" type="text" name="vencimiento_certificado" value="{{ old('vencimiento_certificado', optional($configuracion->vencimiento_certificado)->format('d-m-Y')) }}" placeholder="dd-mm-yyyy">
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="alert alert-danger alert-important"{!! $errors->any() ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.empresa.configuracion') }}"><i class="fa fa-reply"></i> Atras</a>
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
    $(document).ready(function () {
      $('#vencimiento_certificado').datepicker({
        format: 'dd-mm-yyyy',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });
    });
  </script>
@endsection
