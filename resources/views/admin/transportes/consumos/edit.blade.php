@extends('layouts.app')

@section('title', 'Editar')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Consumos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transportes.index') }}">Transportes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transportes.show', ['transporte' => $consumo->transporte_id]) }}">Consumos</a></li>
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
          <h4>Editar consumo</h4>
        </div>
        <div class="ibox-content">
          <form  action="{{ route('admin.consumos.update', ['consumo' => $consumo->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label for="tipo">Tipo: *</label>
                  <select id="tipo" class="form-control" name="tipo" required>
                    <option value="">Seleccione...</option>
                    <option value="1" {{ old('tipo') == '1' ? 'selected' : $consumo->tipo == 1 ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="2" {{ old('tipo') == '2' ? 'selected' : $consumo->tipo == 2 ? 'selected' : '' }}>Combustible</option>
                    <option value="3" {{ old('tipo') == '3' ? 'selected' : $consumo->tipo == 3 ? 'selected' : '' }}>Peaje</option>
                    <option value="4" {{ old('tipo') == '4' ? 'selected' : $consumo->tipo == 4 ? 'selected' : '' }}>Gastos varios</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  <label for="fecha">Fecha: *</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha', $consumo->fecha()) }}" placeholder="Fecha" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('cantidad') ? ' has-error' : '' }}">
                  <label for="cantidad">Cantidad: *</label>
                  <input id="cantidad" class="form-control" type="number" name="cantidad" step="0.1" min="1" max="999" value="{{ old('cantidad', $consumo->cantidad) }}" placeholder="Cantidad" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
                  <label for="valor">Valor: *</label>
                  <input id="valor" class="form-control" type="number" name="valor" step="0.1" min="1" max="999999999" value="{{ old('valor', $consumo->valor) }}" placeholder="Valor" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('chofer') ? ' has-error' : '' }}">
                  <label for="chofer">Chofer: *</label>
                  <input id="chofer" class="form-control" type="text" name="chofer" maxlength="50" value="{{ old('chofer', $consumo->chofer) }}" placeholder="Chofer" required>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
              <label for="observacion">Observación: </label>
              <input id="observacion" class="form-control" type="text" name="observacion" maxlength="200" value="{{ old('observacion', $consumo->observacion) }}" placeholder="Observación">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.consumos.show', ['consumo' => $consumo->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
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
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#fecha').datepicker({
        format: 'dd-mm-yyyy',
        endDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#tipo').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

      $('#tipo').change(function(){
        let tipo = $(this).val()

        let bool = tipo == 2

        $('#cantidad')
          .prop('required', bool)
          .closest('.form-group')
          .attr('hidden', !bool)
      })

      $('#tipo').change()
    });
  </script>
@endsection
