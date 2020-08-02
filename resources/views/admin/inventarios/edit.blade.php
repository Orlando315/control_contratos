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
      <h2>Inventarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventarios.index') }}">Inventarios</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Editar inventario</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.inventarios.update', ['id' => $inventario->id]) }}" method="POST" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label class="control-label" class="form-control" for="tipo">Tipo: *</label>
                  <select id="tipo" class="form-control" name="tipo" required>
                    <option value="">Seleccione...</option>
                    @if(Auth::user()->tipo < 3)
                      <option value="1"{{ old('tipo', $inventario->tipo) == '1' ? ' selected' : '' }}>Insumo</option>
                      <option value="2"{{ old('tipo', $inventario->tipo) == '2' ? ' selected' : '' }}>EPP</option>
                      <option value="4"{{ old('tipo', $inventario->tipo) == '4' ? ' selected' : '' }}>Equipo</option>
                      <option value="5"{{ old('tipo', $inventario->tipo) == '5' ? ' selected' : '' }}>Maquinaria</option>
                      <option value="6"{{ old('tipo', $inventario->tipo) == '6' ? ' selected' : '' }}>Herramienta</option>
                    @endif
                    <option value="3"{{ old('tipo', $inventario->tipo) == '3' ? ' selected' : '' }}>Otro</option>
                  </select>
                </div>
                <div class="form-group{{ $errors->has('otro') ? ' has-error' : '' }}" style="display: none">
                  <input id="otro" class="form-control" type="text" name="otro" maxlength="50" value="{{ old('otro', $inventario->tipo()) }}" placeholder="Otro tipo" disabled required>
                  <small class="form-text-text-muted">Especifique el tipo</small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label class="control-label" for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $inventario->nombre) }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
                  <label class="control-label" for="valor">Valor: *</label>
                  <input id="valor" class="form-control" type="number" step="1" min="1" max="9999999999999" name="valor" value="{{ old('valor', $inventario->valor) }}" placeholder="Valor" required>
                </div>
              </div>
            </div>
            
            <fieldset id="section-extras" class="border-bottom border-top mb-2 py-3" style="display: none" disabled>
              <div class="row">
                <div class="col-md-4">
                  <div class="custom-control custom-checkbox">
                    <input id="calibracion" class="custom-control-input" type="checkbox" name="calibracion" value="1"{{ $inventario->calibracion ? ' checked' : '' }}>
                    <label class="custom-control-label" for="calibracion">Requiere calibración</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="custom-control custom-checkbox">
                    <input id="certificado" class="custom-control-input" type="checkbox" name="certificado" value="1"{{ $inventario->certificado ? ' checked' : '' }}>
                    <label class="custom-control-label" for="certificado">Certificado</label>
                  </div>
                </div>
              </div>
            </fieldset>
            
            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  <label class="control-label" for="fecha">Fecha: *</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha', $inventario->fecha) }}" placeholder="dd-mm-yyyy" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('cantidad') ? ' has-error' : '' }}">
                  <label class="control-label" for="cantidad">Cantidad: *</label>
                  <input id="cantidad" class="form-control" type="number" step="1" min="1" max="999999" name="cantidad" value="{{ old('cantidad', $inventario->cantidad) }}" placeholder="Valor" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('stock_critico') ? ' has-error' : '' }}">
                  <label class="control-label" for="stock_critico">Stock crítico:</label>
                  <input id="stock_critico" class="form-control" type="number" step="1" min="1" max="999" name="stock_critico" value="{{ old('stock_critico', $inventario->low_stock) }}" placeholder="Stcok crítico">
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                  <label class="control-label" for="descripcion">Descripción:</label>
                  <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion', $inventario->descripcion) }}" placeholder="Descripción">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
                  <label class="control-label" for="observacion">Observación:</label>
                  <input id="observacion" class="form-control" type="text" name="observacion" maxlength="200" value="{{ old('observacion', $inventario->observacion) }}" placeholder="Observación">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('adjunto') ? ' has-error' : '' }}">
                  <label for="adjunto">Adjunto:</label>
                  <div class="custom-file">
                    <input id="adjunto" class="custom-file-input" type="file" name="adjunto" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    <label class="custom-file-label" for="adjunto">Seleccionar</label>
                  </div>
                  <small class="form-text text-muted">Formatos permitidos: jpg, jpeg, png, pdf, txt, xlsx, docx</small>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.inventarios.show', ['inventario' => $inventario->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#tipo').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...'
      })

      $('#fecha').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

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

      $('#tipo').change(function () {
        let tipo = +$(this).val();
        let isOtro = (tipo == 3);
        let hasExtras = ([4,5,6].includes(tipo));

        $('#otro').prop('disabled', !isOtro).closest('.form-group').toggle(isOtro)
        $('#section-extras').toggle(hasExtras).prop('disabled', !hasExtras)
      })

      $('#tipo').change()
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
