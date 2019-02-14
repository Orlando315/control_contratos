@extends( 'layouts.app' )
@section( 'title','Reportes - '.config( 'app.name' ) )
@section( 'header','Reportes - General' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li>Reportes</li>
    <li class="active">General</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-12 no-print">
      <button class="btn btn-default btn-flat btn-print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
    <div class="col-sm-12 col-md-4 col-md-offset-4 no-print">
      <form id="exportForm" action="{{ route('reportes.generalGet') }}" method="POST">
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
                    <th class="text-center">F. Ingresos</th>
                    <th class="text-center">F. Egresos</th>
                    <th class="text-center">Inventarios</th>
                    <th class="text-center">Anticipos</th>
                    <th class="text-center">Sueldos</th>
                    <th class="text-center">Comidas</th>
                    <th class="text-center">Transporte</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody id="tbody-contratos">
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
      .done(function(contratos){
        $('#tbody-contratos, #tbody-empleados').empty();

        $.each(contratos, function(i, contrato){
          let clase = contrato.total < 0 ? 'text-danger' : 'text-success'

          let tr = '<tr>'
          tr += `<td class="text-center">${contrato.contrato}</td>`
          tr += `<td class="text-center">${contrato.ingresos.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.egresos.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.inventarios.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.anticipos.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.sueldos.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.comidas.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${contrato.transporte.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center ${clase}">${contrato.total.toLocaleString('es-ES')}</td>`
          tr += '</tr>'

          $('#tbody-contratos').append(tr)
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
