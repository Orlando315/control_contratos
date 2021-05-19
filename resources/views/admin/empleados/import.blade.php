@extends('layouts.app')

@section('title', 'Empleados')

@section('head')
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
        <li class="breadcrumb-item"><a href="{{ route('admin.empleado.index') }}">Empleados</a></li>
        <li class="breadcrumb-item active"><strong>Importar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Importar Empleados </h5>

          <div class="ibox-tools">
            <a class="btn btn-default btn-xs" href="{{ asset('formatos/importar_empleados.xlsx') }}" download="importar_empleados.xlsx">
              <i class="fa fa-download" aria-hidden="true"></i> Descargar Formato
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.empleado.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row justify-content-center">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $c)
                      <option value="{{ $c->id }}"{{ old('contrato', ($contrato ? $contrato->id : ($c->isMain() ? $c->id : ''))) == $c->id ? ' selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }}">
                  <label for="archivo">Archivo: *</label>
                  <div class="custom-file">
                    <input id="archivo" class="custom-file-input" type="file" name="archivo" data-msg-placeholder="Seleccionar" required accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    <label class="custom-file-label" for="archivo">Seleccionar</label>
                  </div>
                  <small class="form-text text-muted">Formatos admitidos: .xls, .xlsx</small>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.empleado.index') }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#archivo').change(function () {
        if(this.files && this.files[0]){
          let file = this.files[0];

          if([
              'application/vnd.ms-excel',
              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
            .includes(file.type)) {
            changeLabel(file.name);
          }else{
            changeLabel('Seleccionar');
            showAlert('El archivo no es de un tipo admitido.');
          }
        }
      });

      $('#contrato').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(name){
      $('#archivo').siblings(`label[for="archivo"]`).text(name);
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
      $('#archivo').val('')
    }
  </script>
@endsection
