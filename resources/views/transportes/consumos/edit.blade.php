@extends( 'layouts.app' )
@section( 'title', 'Editar - '.config( 'app.name' ) )
@section( 'header','Editar' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('transportes.show', ['transporte' => $consumo->transporte_id]) }}">Transportes</a></li>
    <li>Consumos</li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('consumos.update', ['id' => $consumo->id]) }}" method="POST" enctype="multipart/form-data">

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar consumo</h4>

        <div class="form-group {{ $errors->has('tipo') ? 'has-error' : '' }}">
          <label class="control-label" class="form-control" for="tipo">Tipo: *</label>
          <select id="tipo" class="form-control" name="tipo" required>
            <option value="">Seleccione...</option>
            <option value="1" {{ old('tipo') == '1' ? 'selected' : $consumo->tipo == 1 ? 'selected' : '' }}>Mantenimiento</option>
            <option value="2" {{ old('tipo') == '2' ? 'selected' : $consumo->tipo == 2 ? 'selected' : '' }}>Combustible</option>
            <option value="3" {{ old('tipo') == '3' ? 'selected' : $consumo->tipo == 3 ? 'selected' : '' }}>Peaje</option>
            <option value="4" {{ old('tipo') == '4' ? 'selected' : $consumo->tipo == 4 ? 'selected' : '' }}>Gastos varios</option>
          </select>
        </div>

        <div class="form-group {{ $errors->has('fecha') ? 'has-error' : '' }}">
          <label class="control-label" for="fecha">Fecha: *</label>
          <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') ? old('fecha') : $consumo->fecha() }}" placeholder="Fecha" required>
        </div>

        <div class="form-group {{ $errors->has('cantidad') ? 'has-error' : '' }}">
          <label class="control-label" for="cantidad">Cantidad: *</label>
          <input id="cantidad" class="form-control" type="number" name="cantidad" step="0.1" min="1" max="999" value="{{ old('cantidad') ? old('cantidad') : $consumo->cantidad }}" placeholder="Cantidad" required>
        </div>

        <div class="form-group {{ $errors->has('valor') ? 'has-error' : '' }}">
          <label class="control-label" for="valor">Valor: *</label>
          <input id="valor" class="form-control" type="number" name="valor" step="0.1" min="1" max="999999999" value="{{ old('valor') ? old('valor') : $consumo->valor }}" placeholder="Valor" required>
        </div>

        <div class="form-group {{ $errors->has('chofer') ? 'has-error' : '' }}">
          <label class="control-label" for="chofer">Chofer: *</label>
          <input id="chofer" class="form-control" type="text" name="chofer" maxlength="50" value="{{ old('chofer') ? old('chofer') : $consumo->chofer }}" placeholder="Chofer" required>
        </div>

        <div class="form-group {{ $errors->has('observacion') ? 'has-error' : '' }}">
          <label class="control-label" for="observacion">Observación: </label>
          <input id="observacion" class="form-control" type="text" name="observacion" maxlength="200" value="{{ old('observacion') ? old('observacion') : $consumo->observacion }}" placeholder="Observación">
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
          <a class="btn btn-flat btn-default" href="{{ route('consumos.show', [$consumo->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#fecha').datepicker({
      format: 'dd-mm-yyyy',
      endDate: 'today',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });

    $('#tipo').change(function(){
      let tipo = $(this).val()

      let bool = tipo == 2

      $('#cantidad')
        .prop('required', bool)
        .closest('.form-group')
        .attr('hidden', !bool)
    })

    $('#tipo').change()
  });
</script>
@endsection
