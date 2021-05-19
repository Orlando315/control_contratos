@extends('layouts.app')

@section('title', 'Documentos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  @if($documento->isType('App\Empleado') || $documento->isType('App\Contrato') || $documento->isType('App\Transporte'))
    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  @endif
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
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
          <form action="{{ route('admin.documento.update', ['documento' => $documento->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            @if($documento->isType('App\Empleado') || $documento->isType('App\Contrato') || $documento->isType('App\Transporte'))
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('requisito') ? ' has-error' : '' }}">
                    <label for="requisito">Requisitos faltantes:</label>
                    <select id="requisito" class="form-control" name="requisito" style="width: 100%">
                      <option value="">Seleccione...</option>
                      @foreach($requisitos as $requisito)
                        <option value="{{ $requisito->id }}"{{ old('requisito', $documento->requisito_id) == $requisito->id ? ' selected' : '' }}>{{ $requisito->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            @endif

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $documento->nombre) }}" placeholder="Nombre" {{ $documento->isRequisito() ? 'readonly' : 'required' }}>
                </div>
              </div>

              @if(!$documento->isType('App\TransporteConsumo'))
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

            @if($documento->isTypeEmpleado())
              <div class="form-group{{ $errors->has('visibilidad') ? ' has-error' : '' }}">
                <label for="visibilidad">Visibilidad:</label>

                <div class="custom-control custom-checkbox">
                  <input id="visibilidad" class="custom-control-input" type="checkbox" name="visibilidad" value="1"{{ old('visibilidad', $documento->visibilidad) ? ' checked' : '' }}>
                  <label class="custom-control-label" for="visibilidad"><i class="icon-visibilidad fa fa-eye-slash" aria-hidden="true"></i> Permitir visibilidad</label>
                </div>
                <span class="form-text text-muted">Determina si el Empleado puede o no ver el Documento desde su perfil.</span>
              </div>
            @endif

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
  @if($documento->isType('App\Empleado') || $documento->isType('App\Contrato') || $documento->isType('App\Transporte'))
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  @endif
  <script type="text/javascript">
    $(document).ready( function(){
      $('#vencimiento').datepicker({
        format: 'dd-mm-yyyy',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      @if($documento->isType('App\Empleado') || $documento->isType('App\Contrato') || $documento->isType('App\Transporte'))
        $('#requisito').select2({
          allowClear: true,
          theme: 'bootstrap4',
          placeholder: 'Seleccionar...',
        });

        $('#requisito').change(function (){
          $('#nombre').prop('disabled', $(this).val() != '')
        })

        $('#requisito').change()
      @endif

      @if($documento->isTypeEmpleado())
        $('#visibilidad').change(function () {
          let isChecked = $(this).is(':checked');

          $('.icon-visibilidad').toggleClass('fa-eye', isChecked);
          $('.icon-visibilidad').toggleClass('fa-eye-slash', !isChecked);
        });
        $('#visibilidad').change();
      @endif
    });
  </script>
@endsection
