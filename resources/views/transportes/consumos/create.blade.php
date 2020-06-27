@extends('layouts.app')

@section('title', 'Consumos')

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
        <li class="breadcrumb-item"><a href="{{ route('transportes.index') }}">Transportes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('transportes.show', ['transporte' => $transporte->id]) }}">Consumos</a></li>
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
          <h4>Agregar consumo</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('consumos.store', ['transporte' => $transporte->id]) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->contrato_id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->contrato->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label for="tipo">Tipo: *</label>
                  <select id="tipo" class="form-control" name="tipo" required>
                    <option value="">Seleccione...</option>
                    <option value="1" {{ old('tipo') == '1' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="2" {{ old('tipo') == '2' ? 'selected' : '' }}>Combustible</option>
                    <option value="3" {{ old('tipo') == '3' ? 'selected' : '' }}>Peaje</option>
                    <option value="4" {{ old('tipo') == '4' ? 'selected' : '' }}>Gastos varios</option>
                  </select>
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
                <div class="form-group{{ $errors->has('cantidad') ? ' has-error' : '' }}">
                  <label for="cantidad">Cantidad: *</label>
                  <input id="cantidad" class="form-control" type="number" name="cantidad" step="0.1" min="1" max="999" value="{{ old('cantidad') }}" placeholder="Cantidad" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
                  <label for="valor">Valor: *</label>
                  <input id="valor" class="form-control" type="number" name="valor" step="0.1" min="1" max="999999999" value="{{ old('valor') }}" placeholder="Valor" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('chofer') ? ' has-error' : '' }}">
                  <label for="chofer">Chofer: *</label>
                  <input id="chofer" class="form-control" type="text" name="chofer" maxlength="50" value="{{ old('chofer') }}" placeholder="Chofer" required>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('adjunto') ? ' has-error' : '' }}">
              <label for="adjunto">Adjunto:</label>
              <div class="custom-file">
                <input id="adjunto" class="custom-file-input" type="file" name="adjunto" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                <label class="custom-file-label" for="adjunto">Seleccionar</label>
              </div>
              <small class="form-text text-muted">Formatos permitidos: jpg, jpeg, png, pdf, txt, xlsx, docx</small>
            </div>

            <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
              <label for="observacion">Observación: </label>
              <input id="observacion" class="form-control" type="text" name="observacion" maxlength="200" value="{{ old('observacion') }}" placeholder="Observación">
            </div>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="form-group text-right">
              <a class="btn btn-default btn-sm" href="{{ route('transportes.show', ['transporte' => $transporte->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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

      $('#contrato, #tipo').select2({
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

      $('#adjunto').change(function () {
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
      })
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(name){
      $('#adjunto').siblings(`label[for="adjunto"]`).text(name);
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
      $('#adjunto').val('')
    }
</script>
@endsection
