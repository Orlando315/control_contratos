@extends('layouts.app')

@section('title', 'Empleados')

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
      <h2>Empleados</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empleados.index') }}">Empleados</a></li>
        <li class="breadcrumb-item">Contratos</li>
        <li class="breadcrumb-item active"><strong>editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Editar Empleado</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.empleados.contrato.update', ['empleado' => $empleado->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <fieldset class="mb-3">
              <legend>Contrato</legend>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('inicio') ? ' has-error' : '' }}">
                    <label class="control-label" for="inicio">Inicio: *</label>
                    <input id="inicio" class="form-control" type="text" name="inicio" value="{{ old('inicio', $empleado->lastContrato->inicio) }}" placeholder="dd-mm-yyyy" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('inicio_jornada') ? ' has-error' : '' }}">
                    <label class="control-label" for="inicio_jornada">Inicio de Jornada: *</label>
                    <input id="inicio_jornada" class="form-control" type="text" name="inicio_jornada" value="{{ old('inicio_jornada', $empleado->lastContrato->inicio_jornada) }}" placeholder="dd-mm-yyyy" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('fin') ? ' has-error' : '' }}">
                    <label class="control-label" for="fin">Fin:</label>
                    <input id="fin" class="form-control" type="text" name="fin" value="{{ old('fin', $empleado->lastContrato->fin) }}" placeholder="dd-mm-yyyy">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('sueldo') ? ' has-error' : '' }}">
                    <label class="control-label" for="sueldo">Sueldo: *</label>
                    <input id="sueldo" class="form-control" type="number" step="1" min="1" max="9999999999999" name="sueldo" value="{{ old('sueldo', $empleado->lastContrato->sueldo) }}" placeholder="Sueldo" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('jornada') ? ' has-error' : '' }}">
                    <label class="control-label" class="form-control" for="jornada">Jornada: *</label>
                    <select id="jornada" class="form-control" name="jornada" required>
                      <option value="">Seleccione...</option>
                      <option value="5x2"{{ old('jornada', $empleado->lastContrato->jornada) == '5x2' ? ' selected' : '' }}>5x2</option>
                      <option value="4x3"{{ old('jornada', $empleado->lastContrato->jornada) == '4x3' ? ' selected' : '' }}>4x3</option>
                      <option value="6x1"{{ old('jornada', $empleado->lastContrato->jornada) == '6x1' ? ' selected' : '' }}>6x1</option>
                      <option value="7x7"{{ old('jornada', $empleado->lastContrato->jornada) == '7x7' ? ' selected' : '' }}>7x7</option>
                      <option value="10x10"{{ old('jornada', $empleado->lastContrato->jornada) == '10x10' ? ' selected' : '' }}>10x10</option>
                      <option value="12x12"{{ old('jornada', $empleado->lastContrato->jornada) == '12x12' ? ' selected' : '' }}>12x12</option>
                      <option value="20x10"{{ old('jornada', $empleado->lastContrato->jornada) == '20x10' ? ' selected' : '' }}>20x10</option>
                      <option value="7x14"{{ old('jornada', $empleado->lastContrato->jornada) == '7x14' ? ' selected' : '' }}>7x14</option>
                      <option value="14x14"{{ old('jornada', $empleado->lastContrato->jornada) == '14x14' ? ' selected' : '' }}>14x14</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                    <label class="control-label" for="descripcion">Descripción: </label>
                    <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion', $empleado->lastContrato->descripcion) }}" placeholder="Descripción">
                  </div>
                </div>
              </div>
            </fieldset>

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
              <a class="btn btn-default btn-sm" href="{{ route('admin.empleados.show', ['empleado' => $empleado->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#inicio, #fin, #inicio_jornada').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#jornada').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })
    });
  </script>
@endsection
