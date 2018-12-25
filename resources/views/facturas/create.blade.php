@extends( 'layouts.app' )
@section( 'title', 'Facturas - '.config( 'app.name' ) )
@section( 'header','Facturas' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('facturas.index') }}">Facturas</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('facturas.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <h4>Agregar factura</h4>

        <div class="form-group {{ $errors->has('tipo') ? 'has-error' : '' }}">
          <label class="control-label" class="form-control" for="tipo">Tipo: *</label>
          <select id="tipo" class="form-control" name="tipo" required>
            <option value="">Seleccione...</option>
            <option value="1" {{ old('tipo') == '1' ? 'selected' : '' }}>Ingreso</option>
            <option value="2" {{ old('tipo') == '2' ? 'selected' : '' }}>Egreso</option>
          </select>
        </div>

        <div class="form-group {{ $errors->has('nombre') ? 'has-error' : '' }}">
          <label class="control-label" for="nombre">Nombre: *</label>
          <input id="nombre" class="form-control" type="text" name="nombre" maxlength="30" value="{{ old('nombre') ? old('nombre') : '' }}" placeholder="Nombre" required>
        </div>

        <div class="form-group {{ $errors->has('realizada_por') ? 'has-error' : '' }}">
          <label class="control-label" for="realizada_por">Realizada por: *</label>
          <input id="realizada_por" class="form-control" type="text" name="realizada_por" maxlength="50" value="{{ old('realizada_por') ? old('realizada_por') : '' }}" placeholder="Realizada Por" required>
        </div>

        <div class="form-group {{ $errors->has('realizada_para') ? 'has-error' : '' }}">
          <label class="control-label" for="realizada_para">Realizada para: *</label>
          <input id="realizada_para" class="form-control" type="text" name="realizada_para" maxlength="50" value="{{ old('realizada_para') ? old('realizada_para') : '' }}" placeholder="Realizada Para" required>
        </div>

        <div class="form-group {{ $errors->has('fecha') ? 'has-error' : '' }}">
          <label class="control-label" for="fecha">Fecha: *</label>
          <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') ? old('fecha') : '' }}" placeholder="dd-mm-yyyy" required>
        </div>

        <div class="form-group {{ $errors->has('valor') ? 'has-error' : '' }}">
          <label class="control-label" for="valor">Valor: *</label>
          <input id="valor" class="form-control" type="number" step="1" min="1" maxlength="999999999999999" name="valor" value="{{ old('valor') ? old('valor') : '' }}" placeholder="Valor" required>
        </div>

        <div class="form-group {{ $errors->has('pago_fecha') ? 'has-error' : '' }}">
          <label class="control-label" for="pago_fecha">Fecha del pago: *</label>
          <input id="pago_fecha" class="form-control" type="text" name="pago_fecha" value="{{ old('pago_fecha') ? old('pago_fecha') : '' }}" placeholder="dd-mm-yyyy" required>
        </div>

        <div class="form-group {{ $errors->has('pago_estado') ? 'has-error' : '' }}">
          <label class="control-label" class="form-control" for="pago_estado">Estado del pago: *</label>
          <select id="pago_estado" class="form-control" name="pago_estado" required>
            <option value="">Seleccione...</option>
            <option value="0" {{ old('pago_estado') == '0' ? 'selected' : '' }}>Pendiente</option>
            <option value="1" {{ old('pago_estado') == '1' ? 'selected' : '' }}>Pagada</option>
          </select>
        </div>

        <div class="form-group {{ $errors->has('adjunto1') ? 'has-error' : '' }}">
          <label class="control-label" for="adjunto1">Adjunto #1: </label>
          <input id="adjunto1" type="file" name="adjunto1" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
        </div>

        <div class="form-group {{ $errors->has('adjunto2') ? 'has-error' : '' }}">
          <label class="control-label" for="adjunto2">Adjunto #2: </label>
          <input id="adjunto2" type="file" name="adjunto2" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
        </div>

        @if (count($errors) > 0)
        <div class="alert alert-danger alert-important">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>  
        </div>
        @endif

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ route('facturas.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#fecha, #pago_fecha').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });
  });
</script>
@endsection
