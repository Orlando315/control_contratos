@extends( 'layouts.app' )
@section( 'title','Reportes - '.config( 'app.name' ) )
@section( 'header','Reportes - Inventario' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li>Reportes</li>
    <li class="active">Inventario</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-sm-12 col-md-4 col-md-offset-4">
      <form id="exportForm" action="{{ route('reportes.inventariosGet') }}" method="POST">
        {{ csrf_field() }}
        <div class="input-daterange input-group">
          <input id="inicioExport" type="text" class="form-control" name="inicio" placeholder="yyyy-mm-dd" required>
          <span class="input-group-addon">Hasta</span>
          <input id="finExport" type="text" class="form-control" name="fin" placeholder="yyyy-mm-dd" required>
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
                <h5 id="total-iventario" class="description-header">-</h5>
                <span class="description-text">TOTAL INVENTARIOS</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <h5 id="total-costo" class="description-header">-</h5>
                <span class="description-text">COSTO TOTAL</span>
              </div>
              <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-4 col-xs-4">
              <div class="description-block border-right">
                <h5 id="total-items" class="description-header">-</h5>
                <span class="description-text">TOTAL ITEMS</span>
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
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Agregado</th>
                  </tr>
                </thead>
                <tbody id="tbody">
                  <tr>
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
      .done(function(inventarios){
        $('#tbody').empty();
          let totalItems = 0,
              totalCosto = 0,
              totalInventario = 0;

        $.each(inventarios, function(i, inventario){
          let clase = ''

          $.each(inventarios, function(j, k){
            if(k.nombre == inventario.nombre && k.valor != inventario.valor){
              clase = 'danger'
            }
          })

          let tr = `<tr class="${clase}">`
          tr += `<td class="text-center">${inventario.tipo}</td>`
          tr += `<td class="text-center">${inventario.nombre}</td>`
          tr += `<td class="text-center">${inventario.valor}</td>`
          tr += `<td class="text-center">${inventario.fecha}</td>`
          tr += `<td class="text-center">${inventario.cantidad}</td>`
          tr += `<td class="text-center">${inventario.created_at}</td>`
          tr += '</tr>'

          totalItems += inventario.cantidad
          totalCosto += inventario.valor
          totalInventario++

          $('#tbody').append(tr)
        })

        $('#total-items').text(totalItems.toLocaleString('es-ES'))
        $('#total-costo').text(totalCosto.toLocaleString('es-ES'))
        $('#total-iventario').text(totalInventario.toLocaleString('es-ES'))
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
        $('#total-items, #total-costo, #total-iventario').text('-')
        $('#tbody').empty();
      })
      .always(function(){
        btn.button('reset');
        overlay.hide();
      })
    }
  </script>
@endsection
