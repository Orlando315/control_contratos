@extends( 'layouts.app' )
@section( 'title', 'Transportes - '.config( 'app.name' ) )
@section( 'header','Transportes' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('transportes.index') }}">Transportes</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('transportes.store') }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar transporte</h4>

        <div class="form-group {{ $errors->has('supervisor') ? 'has-error' : '' }}">
          <label class="control-label" for="supervisor">Supervisor: *</label>
          <select id="supervisor" class="form-control" name="supervisor" required>
            <option value="">Seleccione...</option>
            @foreach($usuarios as $usuario)
              <option value="{{ $usuario->id }}" {{ old('usuario') == $usuario->id ? 'selected':'' }}>{{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('contrato') ? 'has-error' : '' }}">
          <label class="control-label" for="contrato">Contrato: *</label>
          <select id="contrato" class="form-control" name="contrato" required>
            <option value="">Seleccione...</option>
            @foreach($contratos as $contrato)
              <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('vehiculo') ? 'has-error' : '' }}">
          <label class="control-label" for="vehiculo">Vehiculo: *</label>
          <input id="vehiculo" class="form-control" type="text" name="vehiculo" maxlength="50" value="{{ old('vehiculo') ? old('vehiculo') : '' }}" placeholder="Vehiculo" required>
        </div>

        <div class="form-group {{ $errors->has('patente') ? 'has-error' : '' }}">
          <label class="control-label" for="patente">Patente: *</label>
          <input id="patente" class="form-control" type="text" name="patente" maxlength="50" value="{{ old('patente') ? old('patente') : '' }}" placeholder="Patente" required>
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
          <a class="btn btn-flat btn-default" href="{{ route('transportes.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#contrato, #supervisor').select2()
  });
</script>
@endsection
