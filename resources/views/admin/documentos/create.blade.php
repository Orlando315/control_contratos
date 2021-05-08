@extends('layouts.app')

@section('title', 'Documentos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  @if($type == 'empleados' || $type == 'contratos' || $type == 'transportes')
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
          <h5>Agregar documento</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.documentos.store', ['type' => $type, 'id' => $model->id, 'carpeta' => optional($carpeta)->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if($type == 'empleados' || $type == 'contratos' || $type == 'transportes')
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('requisito') ? ' has-error' : '' }}">
                    <label for="requisito">Requisitos faltantes:</label>
                    <select id="requisito" class="form-control" name="requisito" style="width: 100%">
                      <option value="">Seleccione...</option>
                      @foreach($requisitos as $requisito)
                        <option value="{{ $requisito->id }}"{{ old('requisito', optional($requisitoSelected)->id) == $requisito->id ? ' selected' : '' }}>{{ $requisito->nombre }}</option>
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
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', optional($requisitoSelected)->nombre) }}" placeholder="Nombre" required>
                </div>
              </div>
              @if($type != 'consumos')
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('vencimiento') ? ' has-error' : '' }}">
                    <label for="vencimiento">Vencimiento:</label>
                    <input id="vencimiento" class="form-control" type="text" name="vencimiento" value="{{ old('vencimiento') }}" placeholder="dd-mm-yyyy">
                  </div>
                </div>
              @endif
            </div>

            <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
              <label for="observacion">Obervación:</label>
              <input id="observacion" class="form-control" type="text" name="observacion" maxlength="100" value="{{ old('observacion') }}" placeholder="Observación">
            </div>

            <div class="form-group{{ $errors->has('documento') ? ' has-error' : '' }}">
              <label for="documento">Documento: *</label>
              <div class="custom-file">
                <input id="documento" class="custom-file-input" type="file" name="documento" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                <label class="custom-file-label" for="documento">Seleccionar</label>
              </div>
              <small class="form-text text-muted">Formatos permitidos: jpg, jpeg, png, pdf, txt, xlsx, docx</small>
            </div>

            @if($type == 'empleados')
              <div class="form-group{{ $errors->has('visibilidad') ? ' has-error' : '' }}">
                <label for="visibilidad">Visibilidad:</label>

                <div class="custom-control custom-checkbox">
                  <input id="visibilidad" class="custom-control-input" type="checkbox" name="visibilidad" value="1"{{ old('visibilidad') ? ' checked' : '' }}>
                  <label class="custom-control-label" for="visibilidad"><i class="icon-visibilidad fa fa-eye-slash" aria-hidden="true"></i> Permitir visibilidad</label>
                </div>
                <span class="form-text text-muted">Determina si el Empleado puede o no ver el Documento desde su perfil.</span>
              </div>
            @endif

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route(($carpeta ? 'admin.carpeta.show' : 'admin.'.$type.'.show'), ($carpeta ? ['carpeta' => $carpeta->id] : [$varName => $model->id])) }}"><i class="fa fa-reply"></i> Atras</a>
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
  @if($type == 'empleados' || $type == 'contratos' || $type == 'transportes')
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  @endif
  <script type="text/javascript">
    $(document).ready( function(){
      @if($type != 'consumos')
        $('#vencimiento').datepicker({
          format: 'dd-mm-yyyy',
          startDate: 'today',
          language: 'es',
          keyboardNavigation: false,
          autoclose: true
        });
      @endif

      @if($type == 'empleados' || $type == 'contratos' || $type == 'transportes')
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

      $('#documento').change(function () {
        if(this.files && this.files[0]){
          let file = this.files[0];

          if([
            'image/png',
            'image/jpeg',
            'text/plain',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]
            .includes(file.type)) {
            changeLabel(file.name)
          }else{
            changeLabel('Seleccionar')
            showAlert('El archivo no es de un tipo admitido.')
            return false;
          }
        }
      });

      @if($type == 'empleados')
        $('#visibilidad').change(function () {
          let isChecked = $(this).is(':checked');

          $('.icon-visibilidad').toggleClass('fa-eye', isChecked);
          $('.icon-visibilidad').toggleClass('fa-eye-slash', !isChecked);
        });
        $('#visibilidad').change();
      @endif
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(name){
      $('#documento').siblings(`label[for="documento"]`).text(name);
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
      $('#documento').val('')
    }
  </script>
@endsection
