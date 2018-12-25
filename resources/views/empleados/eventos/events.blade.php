@extends( 'layouts.app' )

@section( 'title', 'Eventos - '.config( 'app.name' ) )
@section( 'header', 'Eventos' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
	  <li class="active"> Eventos </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
  </section>

  <section style="margin-top: 20px">
    <div class="row">
      <div class="col-sm-12 col-md-4 col-md-offset-4">
        <form id="exportForm" action="{{ route('eventos.export') }}" method="POST">
          {{ csrf_field() }}
          <div class="input-daterange input-group">
            <input id="inicioExport" type="text" class="form-control" name="inicio" placeholder="yyyy-mm-dd" required>
            <span class="input-group-addon">Hasta</span>
            <input id="finExport" type="text" class="form-control" name="fin" placeholder="yyyy-mm-dd" required>
          </div>
          <center style="margin-top: 10px">
            <button id="search" class="btn btn-flat btn-primary" type="button">Buscar</button>
          </center>

          <div class="alert alert-danger" style="display: none">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong class="text-center">Ha ocurrido un error.</strong> 
          </div>
        </form>
      </div>
      <div class="col-md-12" style="margin-top: 20px">
        <div class="box box-solid">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button id="exportEvents" class="btn btn-flat btn-success"><i class="fa fa-file-excel-o"></i> Exportar</button>
              </div>
              <div class="col-md-12" style="margin-top: 10px">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Empleado</th>
                      <th>Asistencia</th>
                      <th>Descanso</th>
                      <th>Licencia médica</th>
                      <th>Vacaciones</th>
                      <th>Permiso</th>
                      <th>Permiso no remunerable</th>
                      <th>Despido</th>
                      <th>Renuncia</th>
                      <th>Inasistencia</th>
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
  </section>

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

      $('#search').click(getEvents)
      $('#exportEvents').click(function(){
        $('#exportForm').submit()
      })

    })

    function getEvents(){
      let btn = $(this),
          action = '{{ route("eventos.getEvents") }}',
          alert  = $('.alert');

      btn.button('loading');
      alert.hide();
      overlay.show();

      $.ajax({
        type: 'POST',
        url: action,
        data: {
          inicio: $('#inicioExport').val(),
          fin: $('#finExport').val(),
          _token: '{{ csrf_token() }}',
        },
        dataType: 'json',
      })
      .done(function(events){
        $('#tbody').empty();
        $.each(events, function(i, empleado){
          let tr = $('<tr></tr>')
          if(i > 0){
            $.each(empleado, function(k, v){
              tr.append($('<td></td>').text(v))
            })

          $('#tbody').append(tr)
          }
        })
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
      })
      .always(function(){
        btn.button('reset');
        overlay.hide();
      })
    }
 	</script>
@endsection
