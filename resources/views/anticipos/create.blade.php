@extends('layouts.app')

@section('title', 'Anticipos')

@section('head')
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
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Anticipos</li>
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
          <h5>Solicitar anticipo</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('anticipo.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h4 class="text-center">¿Desea solicitar un anticipo?</h4>

            <div class="switch mb-3">
              <div class="onoffswitch mx-auto">
                <input id="check-solicitud" class="onoffswitch-checkbox" type="checkbox" name="solicitud" value="1"{{ old('solicitud', '0') == '1' ? ' checked' : '' }}>
                <label class="onoffswitch-label" for="check-solicitud">
                  <span class="onoffswitch-inner"></span>
                  <span class="onoffswitch-switch"></span>
                </label>
              </div>
            </div>

            <fieldset id="fields-anticipo" style="display: none">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('anticipo') ? ' has-error' : '' }}">
                    <label for="anticipo">Anticipo: *</label>
                    <input id="anticipo" class="form-control" type="number" name="anticipo" min="1" max="99999999" value="{{ old('anticipo') }}" placeholder="Anticipo" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input id="check-avanzado" class="custom-control-input" type="checkbox" name="avanzado" value="1"{{ old('avanzado') == '1' ? ' checked' : '' }}>
                  <label class="custom-control-label" for="check-avanzado">
                    Avanzado
                  </label>
                </div>
              </div>

              <fieldset id="fields-avanzado" style="display: none">
                <legend>Avanzado:</legend>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('bono') ? ' has-error' : '' }}">
                      <label for="bono">Bono:</label>
                      <input id="bono" class="form-control" type="number" name="bono" min="0" max="99999999" value="{{ old('bono') }}" placeholder="Bono">
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

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                      <label for="descripcion">Descripción:</label>
                      <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion') }}" placeholder="Descripción">
                    </div>
                  </div>
                </div>
              </fieldset>
            </fieldset>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready( function(){
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
          }
        }
      });

      $('#check-avanzado').change(function () {
        let checked = $(this).is(':checked');

        $('#fields-avanzado').toggle(checked);
      });
      $('#check-avanzado').change();


      $('#check-solicitud').change(function () {
        let checked = $(this).is(':checked');

        $('#fields-anticipo').toggle(checked).prop('disabled', !checked);
      });
      $('#check-solicitud').change();
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
