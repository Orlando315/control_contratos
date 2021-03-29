@extends('layouts.app')

@section('title', 'Ediar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Pagos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cotizacion.facturacion.show', ['facturacion' => $pago->facturacion_id]) }}">Facturación</a></li>
        <li class="breadcrumb-item">Pagos</li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Editar pago</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.pago.update', ['pago' => $pago->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <h4 class="text-center">Total por pagar: {{ $pago->facturacion->pendienteWithoutPago($pago) }}</h4>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('monto') ? ' has-error' : '' }}">
                  <label for="metodo">Método:</label>
                  <select id="metodo" class="custom-select" name="metodo" required>
                    <option value="transferencia"{{ old('metodo', $pago->metodo) == 'transferencia' ? ' selected' : '' }}>Transferencia</option>
                    <option value="deposito"{{ old('metodo', $pago->metodo) == 'deposito' ? ' selected' : '' }}>Deposito</option>
                    <option value="otro"{{ old('metodo', $pago->metodo) == 'otro' ? ' selected' : '' }}>Otro</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group{{ $errors->has('otro') ? ' has-error' : '' }}" style="display: none">
                  <label for="otro">Otro: *</label>
                  <input id="otro" class="form-control" type="text" name="otro" maxlength="20" value="{{ old('otro', $pago->metodo_otro) }}" placeholder="Otro método" required disabled>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('monto') ? ' has-error' : '' }}">
                  <label for="monto">Monto: *</label>
                  <input id="monto" class="form-control" type="number" name="monto" min="0" max="{{ $pago->facturacion->pendienteWithoutPago($pago, false) }}" step="0.01" value="{{ old('monto', $pago->monto) }}" placeholder="Monto" required>
                  <small class="form-text text-muted">Monto máximo: {{ $pago->facturacion->pendienteWithoutPago($pago) }}</small>
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

            <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion', $pago->descripcion) }}" placeholder="Descripción">
            </div>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.cotizacion.facturacion.show', ['facturacion' => $pago->facturacion_id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#metodo').change(function () {
        let isOtro = $(this).val() == 'otro';

        $('#otro').prop({'disabled': !isOtro, 'required': isOtro}).closest('.form-group').toggle(isOtro);
      });
      $('#metodo').change();

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
