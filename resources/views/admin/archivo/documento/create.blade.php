@extends('layouts.app')

@section('title', 'Documentos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <style type="text/css">
    .switch .onoffswitch-inner:before{
      content: 'Sí';
    }
    .switch .onoffswitch-inner:after{
      content: 'No';
    }
  </style>
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('archivo.index') }}">Archivo</a></li>
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
          <form action="{{ route('admin.archivo.documento.store', ['carpeta' => optional($carpeta)->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('vencimiento') ? ' has-error' : '' }}">
                  <label for="vencimiento">Vencimiento:</label>
                  <input id="vencimiento" class="form-control" type="text" name="vencimiento" value="{{ old('vencimiento') }}" placeholder="dd-mm-yyyy">
                </div>
              </div>
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
              <span class="form-text text-muted">Formatos permitidos: jpg, jpeg, png, pdf, txt, xlsx, docx</span>
            </div>

            @if(!$carpeta)
              <div class="form-group{{ $errors->has('publico') ? ' has-error' : '' }}">
                <label for="publico">Público:</label>

                <div class="switch mb-3">
                  <div class="onoffswitch">
                    <input id="check-publico" class="onoffswitch-checkbox" type="checkbox" name="publico" value="1"{{ old('publico', 1) ? ' checked' : '' }}>
                    <label class="onoffswitch-label" for="check-publico">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </div>
                <span class="form-text text-muted">Todos los usuarios tendrán acceso.</span>
              </div>
            @else
              @if($carpeta->isPublic())
                <p class="text-center">El documento será público ya que la carpeta padre también es pública.</p>
              @endif

              @if($carpeta->isPrivate())
                <p class="text-center">El documento será privado ya que la carpeta padre también es privada.</p>
              @endif
            @endif

            @if(!$carpeta || ($carpeta && $carpeta->isPrivate()))
              <div class="form-group{{ $errors->has('usuarios') ? ' has-error' : '' }}">
                <label for="usuarios">Usuarios:</label>
                <select id="usuarios" class="form-control" name="usuarios[]" multiple style="width: 100%">
                  <option value="">Seleccione...</option>
                  @foreach($users as $user)
                    <option value="{{ $user->id }}"{{ old('usuarios') ? (in_array($user->id, old('usuarios', [])) ? ' selected' : '') : ($carpeta && $carpeta->archivoUsers->contains($user->id) ? ' selected' : '') }}>{{ $user->nombre() }}</option>
                  @endforeach
                </select>

                <span class="form-text text-muted">Usuarios que tendán acceso al documento.</span>
                @if($carpeta)
                  <span class="form-text text-muted">Solo se mostrarán los Usuarios que tengan acceso a la carpeta padre del documento.</span>
                @endif
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
              <a class="btn btn-default btn-sm" href="{{ $carpeta ? route('archivo.carpeta.show', ['carpeta' => $carpeta->id]) : route('archivo.index') }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#vencimiento').datepicker({
        format: 'dd-mm-yyyy',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#usuarios').select2({
        allowClear: true,
        theme: 'bootstrap4',
        placeholder: 'Seleccionar...',
      });

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

      @if(!$carpeta)
        $('#check-publico').change(function () {
          let isChecked = $(this).is(':checked');

          $('#usuarios').prop('disabled', isChecked).closest('.form-group').toggle(!isChecked);
        });
        $('#check-publico').change();
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
