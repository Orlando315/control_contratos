@extends( 'layouts.app' )
@section( 'title','Reportes - '.config( 'app.name' ) )
@section( 'header','Reportes - Reemplazo' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li>Reportes</li>
    <li class="active">Reemplazo</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-12 no-print">
      <button class="btn btn-default btn-flat btn-print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
    <div class="col-sm-12 col-md-4 col-md-offset-4 no-print">
      <form id="exportForm" action="{{ route('reportes.reemplazosGet') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
          <div class="input-daterange input-group">
            <input id="inicioExport" type="text" class="form-control" name="inicio" placeholder="yyyy-mm-dd" required>
            <span class="input-group-addon">Hasta</span>
            <input id="finExport" type="text" class="form-control" name="fin" placeholder="yyyy-mm-dd" required>
          </div>
        </div>
        <div class="form-group">
          <div class="form-group">
            <label class="control-label" for="contrato">Contrato:</label>
            <select id="contrato" class="form-control" name="contrato">
              <option value="">Seleccione...</option>
              @foreach($contratos as $contrato)
                <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <center style="margin-top: 10px">
          <button id="search" class="btn btn-flat btn-primary" type="submit">Buscar</button>
        </center>

        <div class="alert alert-danger" style="display: none">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong class="text-center">Ha ocurrido un error.</strong> 
        </div>
      </form>
    </div>

    <div class="col-md-12" style="margin-top: 20px">
      <div class="box box-solid">
        <div class="box-header">
          <h3>Contratos</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Empleados</th>
                    <th class="text-center">Reemplazos</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody id="tbody-contratos">
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="overlay" style="display: none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
      </div>
    </div>

    <div class="col-md-12" style="margin-top: 20px">
      <div class="box box-solid">
        <div class="box-header">
          <h3>Empleados</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">RUT</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Reemplazos</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody id="tbody-empleados">
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="overlay" style="display: none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>
      </div>
    </div>
  </div>
  
@endsection

@section('scripts')
  <script type="text/javascript">
    var overlay = $('.overlay');

    $(document).ready(function(){

      $('.input-daterange').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false
      });

      $('#exportForm').submit(getEvents)

    })

    function getEvents(e){
      e.preventDefault();

      let form   = $(this),
          action = form.attr('action'),
          alert  = $('.alert'),
          btn    = $('#search');

      btn.button('loading');
      alert.hide();
      overlay.show();

      $.ajax({
        type: 'POST',
        url: action,
        data: {
          inicio: $('#inicioExport').val(),
          fin: $('#finExport').val(),
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(comidas){
        $('#tbody-contratos, #tbody-empleados').empty();

        $.each(comidas.contratos, function(i, contrato){
          let tr = '<tr>'
          tr += `<td class="text-center">${contrato.contrato}</td>`
          tr += `<td class="text-center">${contrato.empleados}</td>`
          tr += `<td class="text-center">${contrato.reemplazos.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.total.toLocaleString('es-ES')}</td>`
          tr += '</tr>'

          $('#tbody-contratos').append(tr)
        })

        $.each(comidas.empleados, function(i, empleado){
          let tr = '<tr>'
          tr += `<td class="text-center">${empleado.contrato}</td>`
          tr += `<td class="text-center">${empleado.rut}</td>`
          tr += `<td class="text-center">${empleado.empleado}</td>`
          tr += `<td class="text-center">${empleado.reemplazos.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${empleado.total.toLocaleString('es-ES')}</td>`
          tr += '</tr>'

          $('#tbody-empleados').append(tr)
        })

      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
        $('#tbody-contratos, #tbody-empleados').empty();
      })
      .always(function(){
        btn.button('reset');
        overlay.hide();
      })
    }
  </script>
@endsection
