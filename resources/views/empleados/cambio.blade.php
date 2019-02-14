@extends( 'layouts.app' )
@section( 'title', 'Empleados - '.config( 'app.name' ) )
@section( 'header','Empleados' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('empleados.index') }}">Empleados</a></li>
    <li class="active">Cambio</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('empleados.cambioStore', ['empleado'=>$empleado]) }}" method="POST">
        {{ csrf_field() }}

        <h4>Cambio de jornada</h4>

        <fieldset>
          <legend>Contrato</legend>

          <div class="form-group {{ $errors->has('inicio') ? 'has-error' : '' }}">
            <label class="control-label" for="inicio">Inicio: *</label>
            <input id="inicio" class="form-control" type="text" name="inicio" value="{{ old('inicio') ? old('inicio') : '' }}" placeholder="dd-mm-yyyy" required>
          </div>

          <div class="form-group {{ $errors->has('fin') ? 'has-error' : '' }}">
            <label class="control-label" for="fin">Fin:</label>
            <input id="fin" class="form-control" type="text" name="fin" value="{{ old('fin') ? old('fin') : '' }}" placeholder="dd-mm-yyyy">
          </div>

          <div class="form-group {{ $errors->has('jornada') ? 'has-error' : '' }}">
            <label class="control-label" class="form-control" for="jornada">Jornada: *</label>
            <select id="jornada" class="form-control" name="jornada">
              <option value="">Seleccione...</option>
              <option value="5x2" {{ old('jornada') == '5x2' ? 'selected' : '' }}>5x2</option>
              <option value="4x3" {{ old('jornada') == '4x3' ? 'selected' : '' }}>4x3</option>
              <option value="6x1" {{ old('jornada') == '6x1' ? 'selected' : '' }}>6x1</option>
              <option value="7x7" {{ old('jornada') == '7x7' ? 'selected' : '' }}>7x7</option>
              <option value="10x10" {{ old('jornada') == '10x10' ? 'selected' : '' }}>10x10</option>
              <option value="12x12" {{ old('jornada') == '12x12' ? 'selected' : '' }}>12x12</option>
              <option value="20x10" {{ old('jornada') == '20x10' ? 'selected' : '' }}>20x10</option>
            </select>
            <span class="help-block">Si no se selecciona, se colocara la jornada de la empresa</span>
          </div>
        </fieldset>

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
          <a class="btn btn-flat btn-default" href="{{ url()->previous() }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#inicio, #fin').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });
  });
</script>
@endsection