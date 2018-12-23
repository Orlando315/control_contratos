@extends( 'layouts.app' )
@section( 'title', 'Editar - '.config( 'app.name' ) )
@section( 'header','Editar' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('transportes.index') }}">Transportes</a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('transportes.update', ['id' => $transporte->id]) }}" method="POST">

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar transporte</h4>

        <div class="form-group {{ $errors->has('vehiculo') ? 'has-error' : '' }}">
          <label class="control-label" for="vehiculo">Vehiculo: *</label>
          <input id="vehiculo" class="form-control" type="text" name="vehiculo" maxlength="50" value="{{ old('vehiculo') ? old('vehiculo') : $transporte->vehiculo }}" placeholder="Vehiculo" required>
        </div>

        <div class="form-group {{ $errors->has('patente') ? 'has-error' : '' }}">
          <label class="control-label" for="patente">Patente: *</label>
          <input id="patente" class="form-control" type="text" name="patente" maxlength="50" value="{{ old('patente') ? old('patente') :  $transporte->patente }}" placeholder="Patente" required>
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
          <a class="btn btn-flat btn-default" href="{{ route('transportes.show', [$transporte->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#fecha_mantencion').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });

    $('#contrato').select2()
  });
</script>
@endsection