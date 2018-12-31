@extends( 'layouts.app' )

@section( 'title', 'Calendario - '.config( 'app.name' ) )
@section( 'header', 'Calendario' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('contratos.index') }}">Contratos</a></li>
	  <li class="active"> Calendario </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
  </section>

  <section style="margin-top: 20px">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-flat btn-success" data-toggle="modal" data-target="#exportModal"><i class="fa fa-file-excel-o"></i> Exportar jornadas</button>
              </div>
              <div class="col-md-12">
                <div id="calendar"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <div id="eventsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Agregar evento</h4>
        </div>
        <div class="modal-body">
          <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-danger" style="display: none">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong class="text-center">Ha ocurrido un error.</strong> 
            </div>
          </div>
            <form id="eventForm" class="col-md-8 col-md-offset-2" action="#" method="POST">
              <input id="eventDay" type="hidden" name="inicio" value="">
              {{ csrf_field() }}
              <h4 class="text-center" id="eventTitle"></h4>
              <div class="form-group">
                <label for="tipo">Evento: *</label>
                <select id="tipo" class="form-control" name="tipo" required>
                  <option value="">Seleccione...</option>
                  <option value="2">Licencia médica</option>
                  <option value="3">Vacaciones</option>
                  <option value="4">Permiso</option>
                  <option value="5">Permiso no remunerable</option>
                  <option value="8">Inasistencia</option>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="fin">Fin: <span class="help-block">(Opcional)</span></label>
                <input id="fin" class="form-control" type="text" name="fin" placeholder="yyyy-mm-dd">
              </div>

              <center>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-flat btn-primary" type="submit">Gardar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="delEventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delEventModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delEventModalLabel">Eliminar Evento</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delEventForm" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Desea eliminar este evento?</h4><br>

              <center>
                <button class="btn btn-flat btn-danger" type="submit">Eliminar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exportModalLabel">Exportar a excel</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="exportForm" class="col-md-8 col-md-offset-2" action="{{ route('contratos.exportJornadas', ['contrato' => $contrato->id]) }}" method="POST">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label" for="inicioExport">Inicio: *</label>
                <input id="inicioExport" class="form-control" type="text" name="inicio" placeholder="yyyy-mm-dd" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="finExport">Fin: *</label>
                <input id="finExport" class="form-control" type="text" name="fin" placeholder="yyyy-mm-dd" rqeuired>
              </div>

              <center>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-flat btn-success" type="submit">Enviar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
 	<script type="text/javascript">

    var eventos  = @json($eventos),
        jornadas = @json($jornadas);

    $(document).ready(function(){
      $('#inicioExport, #finExport').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      }).on('changeDate', function(e){
        var inicio = new Date($('#inicioExport').val()),
            fin = new Date($('#finExport').val());

        if(inicio > fin){
          inicio.setDate(inicio.getDate() + 1)
          var newDate = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate()
          $('#finExport').datepicker('setDate', newDate)
        }
      });

      $('#calendar').fullCalendar(
        {
          schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
          locale: 'es',
          defaultView: 'daysTimeline',
          views: {
            daysTimeline: {
              type: 'timeline',
              duration: { days: 7 },
              slotDuration: { days: 1 },
              slotLabelFormat: 'dd D/M'
            }
          },
          resourcesColumns:
          [
            {
              labelText: 'Empleados',
              field: 'title',
              width: '30%'
            }
          ],
          resources: [
            @foreach($empleados as $d)
            {id: '{{$d->id}}', title: '{{$d->usuario->nombres}} {{$d->usuario->apellidos}}', path: '{{ route("eventos.store", ["empleado"=>$d->id]) }}'},
            @endforeach
          ],
          eventSources: [
            {
              events: eventos
            },
            {
              events: jornadas.trabajo,
              color: '#00a65a',
              textcolor: 'white'
            },
            {
              events: jornadas.descanso,
              color: '#9c9c9c',
              textcolor: 'white'
            }
          ],
          dayClick: function(date, jsEvent, view, resourceObj){
            $('#eventTitle').html(resourceObj.title + '<br>' + date.format())
            $('#eventDay').val(date.format())
            $('#eventsModal').modal('show')
            $('#eventForm').attr('action', resourceObj.path)
          },
          eventClick: function(event){
            if(event.id){
              $('#delEventModal').modal('show');
              $('#delEventForm').attr('action', '{{ route("eventos.index") }}/' + event.id);
            }else{
              $('#delEventForm').attr('action', '#');
            }
          }
        }
      )

      $('#tipo').change(function(){
        let tipo = $(this).val()

        let isReemplazo = tipo == 9
        let isDespidoRenuncia = (tipo == 6 || tipo == 7)

        $('#fin')
          .closest('.form-group')
          .attr('hidden', (isReemplazo || isDespidoRenuncia))

        $('#reemplazo, #valor')
          .prop('required', isReemplazo)
          .closest('.form-group')
          .attr('hidden', !isReemplazo)

      })

      $('#reemplazo').select2()
      $('#reemplazo').change()

      $('#eventForm').submit(storeEvent)
      $('#delEventForm').submit(delEvent)
      
    })

    function storeEvent(e){
      e.preventDefault();

      var form = $(this),
          action = form.attr('action'),
          alert  = $('#eventsModal .alert');
          button = form.find('button[type="submit"]');

      button.button('loading');
      alert.hide();

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){

          if(r.evento.tipo == 6 || r.evento.tipo == 7 || r.evento.tipo == 9){
            location.reload()
          }

          $('#calendar').fullCalendar('renderEvent', {
            resourceId: r.evento.empleado_id,
            id: r.evento.id,
            className: 'clickableEvent',
            title: r.data.titulo,
            start: r.evento.inicio,
            end: r.evento.fin,
            allDay: true,
            color: r.data.color
          });
          form[0].reset()
          $('#eventsModal').modal('hide');
        }else{
          alert.show().delay(7000).hide('slow');
          alert.find('strong').text(r.message || 'Ha ocurrido un error.')
        }
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
        alert.find('strong').text('Ha ocurrido un error')
      })
      .always(function(){
        button.button('reset');
      })
    }

    function delEvent(e){
      e.preventDefault();

      var form = $(this),
          action = form.attr('action'),
          alert  = form.find('.alert');
          button = form.find('button[type="submit"]');

      button.button('loading');
      alert.hide();

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){
          $('#calendar').fullCalendar('removeEvents', r.evento.id);
          $('#delEventModal').modal('hide');
        }else{
          alert.show().delay(7000).hide('slow');
        }
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
      })
      .always(function(){
        button.button('reset');
      })
    }
 	</script>
@endsection
