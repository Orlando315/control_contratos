@extends('layouts.app')

@section('title', 'Facturas')

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
      <h2>Facturas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.facturas.index') }}">Facturas</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar factura</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.facturas.store') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato_id') ? ' has-error' : '' }}">
                  <label for="contrato_id">Contrato: *</label>
                  <select id="contrato_id" class="form-control" name="contrato_id" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ old('contrato_id') == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('etiqueta_id') ? ' has-error' : '' }}">
                  <label for="etiqueta_id">Etiqueta:</label>
                  <select id="etiqueta_id" class="form-control" name="etiqueta_id">
                    <option value="">Seleccione...</option>
                    @foreach($etiquetas as $etiqueta)
                      <option value="{{ $etiqueta->id }}"{{ old('etiqueta_id') == $etiqueta->id ? ' selected' : '' }}>{{ $etiqueta->etiqueta }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label for="tipo">Tipo: *</label>
                  <select id="tipo" class="form-control" name="tipo" required>
                    <option value="">Seleccione...</option>
                    <option value="1" {{ old('tipo') == '1' ? 'selected' : '' }}>Ingreso</option>
                    <option value="2" {{ old('tipo') == '2' ? 'selected' : '' }}>Egreso</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="30" value="{{ old('nombre') }}" placeholder="Nombre" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('realizada_por') ? ' has-error' : '' }}">
                  <label for="realizada_por">Realizada por: *</label>
                  <input id="realizada_por" class="form-control" type="text" name="realizada_por" maxlength="50" value="{{ old('realizada_por') }}" placeholder="Realizada Por" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('realizada_para') ? ' has-error' : '' }}">
                  <label for="realizada_para">Realizada para: *</label>
                  <input id="realizada_para" class="form-control" type="text" name="realizada_para" maxlength="50" value="{{ old('realizada_para') }}" placeholder="Realizada Para" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  <label for="fecha">Fecha: *</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') }}" placeholder="dd-mm-yyyy" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
                  <label for="valor">Valor: *</label>
                  <input id="valor" class="form-control" type="number" step="1" min="1" maxlength="999999999999999" name="valor" value="{{ old('valor') }}" placeholder="Valor" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('pago_fecha') ? ' has-error' : '' }}">
                  <label for="pago_fecha">Fecha del pago: *</label>
                  <input id="pago_fecha" class="form-control" type="text" name="pago_fecha" value="{{ old('pago_fecha') }}" placeholder="dd-mm-yyyy" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('pago_estado') ? ' has-error' : '' }}">
                  <label for="pago_estado">Estado del pago: *</label>
                  <select id="pago_estado" class="form-control" name="pago_estado" required>
                    <option value="">Seleccione...</option>
                    <option value="0" {{ old('pago_estado') == '0' ? 'selected' : '' }}>Pendiente</option>
                    <option value="1" {{ old('pago_estado') == '1' ? 'selected' : '' }}>Pagada</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('adjunto1') ? ' has-error' : '' }}">
                  <label for="adjunto1">Adjunto #1: </label>
                  <div class="custom-file">
                    <input id="adjunto1" class="custom-file-input" type="file" name="adjunto1" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <label class="custom-file-label" for="adjunto1">Seleccionar</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('adjunto2') ? ' has-error' : '' }}">
                  <label for="adjunto2">Adjunto #2: </label>
                  <div class="custom-file">
                    <input id="adjunto2" class="custom-file-input" type="file" name="adjunto2" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <label class="custom-file-label" for="adjunto2">Seleccionar</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.facturas.index') }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#fecha, #pago_fecha').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });
      
      $('#contrato_id, #etiqueta_id, #tipo, #pago_estado').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('.custom-file-input').change(function () {
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
            changeLabel($(this).attr('id'), file.name)
          }else{
            changeLabel($(this).attr('id'), 'Seleccionar', true)
            showAlert('El archivo no es de un tipo admitido.')
          }
        }
      })
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(id, name, clear = false){
      $(`#${id}`).siblings(`label[for="${id}"]`).text(name);

      if(clear){
        $(`#${id}`).val('') 
      }
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
    }
  </script>
@endsection
