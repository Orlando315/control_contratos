@extends('layouts.app')

@section('title', 'Solicitudes')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Solicitudes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('solicitud.index') }}">Solicitudes</a></li>
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
          <h4>Editar solicitud</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('solicitud.update', ['solicitud' => $solicitud->id]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label for="tipo">Tipo: *</label>
                  <select id="tipo" class="form-control" name="tipo" required>
                    <option value="">Seleccione...</option>
                    <option value="certificado"{{ old('tipo', $solicitud->tipo) == 'certificado' ? ' selected' : '' }}>Certificado laboral</option>
                    <option value="otro"{{ old('tipo', $solicitud->tipo) == 'otro' ? ' selected' : '' }}>Otro</option>
                  </select>
                </div>
                <div class="form-group{{ $errors->has('otro') ? ' has-error' : '' }}" style="display: none">
                  <input id="otro" class="form-control" type="text" name="otro" maxlength="50" value="{{ old('otro', $solicitud->otro) }}" placeholder="Otro tipo" disabled required>
                  <small class="form-text-text-muted">Especifique el tipo</small>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion', $solicitud->descripcion) }}" placeholder="Descripción">
            </div>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('solicitud.show', ['solicitud' => $solicitud->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#tipo').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

      $('#tipo').change(function () {
        let tipo = $(this).val();
        let isOtro = (tipo == 'otro');

        $('#otro').prop('disabled', !isOtro).closest('.form-group').toggle(isOtro)
      })

      $('#tipo').change()
    });
  </script>
@endsection
