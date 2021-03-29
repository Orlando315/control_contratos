@extends('layouts.app')

@section('title', 'Solicitudes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Solicitudes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.solicitud.index') }}">Solicitudes</a></li>
        <li class="breadcrumb-item active"><strong>Responder</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Responder solicitud</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.solicitud.update', ['solicitud' => $solicitud->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row">
              <div class="col-12">
                <p>
                  <strong>Solicitud:</strong> {{ $solicitud->tipo() }}</br>
                  <strong>Descripción:</strong> {{ $solicitud->descripcion }}
                </p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label>Estatus: *</label>
                <div class="custom-control custom-radio">
                  <input id="aprobar" class="custom-control-input" type="radio" name="estatus" value="aprobar"{{ old('estatus', ($solicitud->status ? 'aprobar' : '')) == 'aprobar' ? ' checked' : '' }}>
                  <label class="custom-control-label" for="aprobar">Aprobar</label>
                </div>
                <div class="custom-control custom-radio">
                  <input id="rechazar" class="custom-control-input" type="radio" name="estatus" value="rechazar"{{ old('estatus', ($solicitud->status ? 'rechazar' : '')) == 'rechazar' ? ' checked' : '' }}>
                  <label class="custom-control-label" for="rechazar">Rechazar</label>
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

            <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
              <label for="observacion">Observación:</label>
              <input id="observacion" class="form-control" type="text" name="observacion" maxlength="200" value="{{ old('observacion', $solicitud->observacion) }}" placeholder="Observación">
            </div>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.solicitud.show', ['solicitud' => $solicitud->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
