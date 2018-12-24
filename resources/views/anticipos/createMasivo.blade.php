@extends( 'layouts.app' )
@section( 'title', 'Anticipos - '.config( 'app.name' ) )
@section( 'header','Anticipos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('anticipos.index') }}">Anticipos</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('anticipos.storeMasivo') }}" method="POST">
        <input id="empleados" type="hidden" name="empleados">
        {{ csrf_field() }}

        <h4>Agregar anticipo masivo</h4>

        <div class="form-group {{ $errors->has('contrato') ? 'has-error' : '' }}">
          <label class="control-label" class="form-control" for="contrato">Contrato: *</label>
          <select id="contrato" class="form-control" name="contrato" required>
            <option value="">Seleccione...</option>
            @foreach($contratos as $contrato)
              <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('fecha') ? 'has-error' : '' }}">
          <label class="control-label" for="fecha">Fecha: *</label>
          <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') ? old('fecha') : '' }}" placeholder="dd-mm-yyyy" required>
        </div>

        <fieldset>
          <legend style="border-bottom: none">Empleados</legend>
          <table class="table table-sm table-condensed table-anticipos">
            <tbody id="tbody-empleados">
            </tbody>
          </table>
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
          <button id="btn-submit" class="btn btn-flat btn-primary" type="submit" disabled><i class="fa fa-send"></i> Guardar</button>
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
      endDate: 'today',
      keyboardNavigation: false,
      autoclose: true
    });

    $('#contrato').change(getEmpleados)
    $('#contrato').change()

    $('#contrato').select2()

    $('#tbody-empleados').on('change', '.input-anticipo', updateEmpleadosInfo)
  });

  let createElement = function(id, name, anticipo){
    let field = 
    `<tr>
      <td><p>${name}<p></td>
      <td class="form-inline">
        <div class="form-group">
          <label class="control-label" for="empleado_${id}">Anticipo: *</label>
          <input id="empleado_${id}" data-id="${id}" class="form-control input-sm input-anticipo" type="number" value="${anticipo}" required>
        </div>
      </td>
    </tr>`

    return field
  }

  let tbody = $('#tbody-empleados')
  let submit = $('#btn-submit')
  let empleados = {}
  let empleadosField = $('#empleados')

  function updateEmpleadosInfo(){
    let input = $(this),
        id = input.data('id'),
        anticipo = input.val();

    empleados[id] = anticipo * 1;
    empleadosField.val(JSON.stringify(empleados))
  }

  function getEmpleados(){
    let contrato = $(this).val();

    if(contrato == '') return;

    $.ajax({
      type: 'POST',
      url: '{{ route("anticipos.index") }}/empleados/' + contrato,
      data: {
        _token: '{{ csrf_token() }}'
      },
      dataType: 'json',
    })
    .done(function(data){
      tbody.empty()
      empleados = {}

      if(data.length > 0){
        $.each(data, function(k, v){
          let anticipo = v.latest_anticipo ? v.latest_anticipo.anticipo : 0;

          empleados[v.id] = anticipo

          let name = `${v.usuario.rut} | ${v.usuario.nombres} ${v.usuario.apellidos}`
          let element = createElement(v.id, name, anticipo)
          tbody.append(element)
        })

        submit.prop('disabled', false)
      }else{
        tbody.insertRow(0)
            .insertCell(0).innerHTML = 'No hay empleados registrados'
        submit.prop('disabled', true)
      }
    })
    .fail(function(){
      tbody.empty()
      empleados = {}
      submit.prop('disabled', false)
    })
    .always(function(){
      empleadosField.val(JSON.stringify(empleados))
    })
  }
</script>
@endsection
