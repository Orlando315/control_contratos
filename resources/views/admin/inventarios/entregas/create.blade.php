@extends('layouts.app')

@section('title', 'Entregas')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Entregas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.index') }}">Inventarios</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.show', ['inventario' => $inventario->id]) }}">Inventario</a></li>
        <li class="breadcrumb-item">Entregas</li>
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
          <h4>Agregar entrega - {{ $inventario->nombre }}</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.entrega.store', ['inventario' => $inventario->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group{{ $errors->has('usuario') ? 'has-error' : '' }}">
              <label for="usuario">Empleado: *</label>
              <select id="usuario" class="form-control" name="usuario" required>
                <option value="">Seleccione...</option>
                @foreach($empleados as $empleado)
                  <option value="{{ $empleado->usuario->id }}" {{ old('usuario') == $empleado->usuario->id ? 'selected':'' }}>{{ $empleado->usuario->nombres }} {{ $empleado->usuario->apellidos }}</option>
                @endforeach
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('cantidad') ? 'has-error' : '' }}">
                  <label class="control-label" for="cantidad">Cantidad: *</label>
                  <input id="cantidad" class="form-control" type="number" step="1" min="1" maxlength="999999" name="cantidad" value="{{ old('cantidad') }}" placeholder="Cantidad" required>
                </div>
              </div>
              <div class="col-md-6">
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
              <a class="btn btn-default btn-sm" href="{{ url()->previous() }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#usuario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

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
