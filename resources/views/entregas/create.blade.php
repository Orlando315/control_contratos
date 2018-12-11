@extends( 'layouts.app' )
@section( 'title', 'Entregas - '.config( 'app.name' ) )
@section( 'header','Entregas' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('inventarios.index') }}">Inventarios</a></li>
    <li>Entregas</li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('entregas.store', ['inventario' => $inventario]) }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar entrega - {{ $inventario->nombre }}</h4>

        <div class="form-group {{ $errors->has('contrato') ? 'has-error' : '' }}">
          <label class="control-label" class="form-control" for="contrato">Contrato: *</label>
          <select id="contrato" class="form-control" name="contrato" required>
            <option value="">Seleccione...</option>
            @foreach($contratos as $contrato)
              <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('empleado_id') ? 'has-error' : '' }}">
          <label class="control-label" for="empleado_id">Empleado: *</label>
          <select id="empleado_id" class="form-control" name="empleado_id" disabled required>
            <option value="">Seleccione...</option>
          </select>
        </div>

        <div class="form-group {{ $errors->has('cantidad') ? 'has-error' : '' }}">
          <label class="control-label" for="cantidad">Cantidad: *</label>
          <input id="cantidad" class="form-control" type="number" step="1" min="1" maxlength="999999" name="cantidad" value="{{ old('cantidad') ? old('cantidad') : '' }}" placeholder="Cantidad" required>
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
    $('#fecha').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });

    $('#contrato').change(getEmpleados)
    $('#contrato').change()

    $('#contrato').select2()
    $('#empleado_id').select2({
      disabled: true
    })
  });

  function getEmpleados(){
    let contrato = $(this).val(),
        select = $('#empleado_id');

    if(contrato == '') return;

    $.ajax({
      type: 'POST',
      url: '{{ route("empleados.index") }}/contratos/' + contrato,
      data: {
        _token: '{{ csrf_token() }}'
      },
      dataType: 'json',
    })
    .done(function(data){
      select.empty().append(new Option('Seleccione...', '', false, false)).trigger('change');
      if(data.length > 0){
        $.each(data, function(k, v){
          select.append(new Option(v.usuario.rut + '|' + v.usuario.nombres + ' ' + v.usuario.apellidos, v.usuario.id, false, false)).trigger('change')
        })

        select.prop('disabled', false)
      }else{
        select.prop('disabled', true)
      }
    })
    .fail(function(){
      select.prop('disabled', false)
    })
    .always(function(){

    })
  }
</script>
@endsection
