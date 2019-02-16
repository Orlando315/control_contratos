@extends( 'layouts.app' )
@section( 'title','Reportes - '.config( 'app.name' ) )
@section( 'header','Reportes - Facturas' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li>Reportes</li>
    <li class="active">Facturas</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-12 no-print">
      <button class="btn btn-default btn-flat btn-print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
    <div class="col-sm-12 col-md-4 col-md-offset-4 no-print">
      <form id="exportForm" action="{{ route('reportes.facturasGet') }}" method="POST">
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
          <div class="row">
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <h5 id="total-facturas" class="description-header">-</h5>
                <span class="description-text">TOTAL FACTURAS</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <h5 id="total-ingresos" class="description-header">-</h5>
                <span class="description-text">TOTAL INGRESOS</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <h5 id="total-egresos" class="description-header">-</h5>
                <span class="description-text">TOTAL EGRESOS</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12" style="margin-top: 10px">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Pago</th>
                  </tr>
                </thead>
                <tbody id="tbody">
                  <tr>
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
          contrato: $('#contrato').val(),
          fin: $('#finExport').val(),
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(facturas){
        $('#tbody').empty();
          let totalIngresos = 0,
              totalEgresos = 0,
              totalFacturas = 0;

        $.each(facturas, function(i, factura){
          let tr = '<tr>'
          tr += `<td class="text-center">${factura.tipolabel}</td>`
          tr += `<td class="text-center">${factura.nombre}</td>`
          tr += `<td class="text-center">${factura.valor.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${factura.fecha}</td>`
          tr += `<td class="text-center">${factura.pago}</td>`
          tr += '</tr>'

          if(factura.tipo == 1){
            totalIngresos += factura.valor
          }else{
            totalEgresos += factura.valor
          }
          totalFacturas++

          $('#tbody').append(tr)
        })

        $('#total-ingresos').text(totalIngresos.toLocaleString('es-ES'))
        $('#total-egresos').text(totalEgresos.toLocaleString('es-ES'))
        $('#total-facturas').text(totalFacturas.toLocaleString('es-ES'))
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
        $('#total-ingresos, #total-egresos, #total-facturas').text('-')
        $('#tbody').empty();
      })
      .always(function(){
        btn.button('reset');
        overlay.hide();
      })
    }
  </script>
@endsection
