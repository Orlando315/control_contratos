@extends( 'layouts.app' )

@section( 'title', 'Comidas - '.config( 'app.name' ) )
@section( 'header', 'Comidas' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('contratos.index') }}">Contratos</a></li>
    <li class="active"> Comidas </li>
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

  <div id="eventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="eventModalLabel"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="eventForm" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('PATCH') }}
              {{ csrf_field() }}
              <input id="eventComida" type="hidden" name="comida" value="">
              <h4 class="text-center">
                ¿Desea <span id="eventTitle"></span> la comida en esta fecha? <span id="eventTextFecha"></span>
              </h4>
              <br>

              <center>
                <button class="btn btn-flat btn-primary" type="submit">Guardar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
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

    var comidas  = @json($comidas),
        asistencias = @json($asistencias);

    $(document).ready(function(){

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
            {id: '{{$d->id}}', title: '{{$d->usuario->nombres}} {{$d->usuario->apellidos}}'},
            @endforeach
          ],
          eventSources: [
            {
              events: comidas
            },
            {
              events: asistencias,
              color: '#00a65a',
              textcolor: 'white'
            },
          ],
          dayClick: function(date, jsEvent, view, resourceObj){
            let eventsOnThisDay = $('#calendar').fullCalendar('clientEvents', event => event.start.format() == date.format() && event.resourceId == resourceObj.id);

            if(eventsOnThisDay.length != 1){ return; }
            
            let action = `{{ route('eventos.index') }}/comida/${eventsOnThisDay[0].id}`

            $('#eventModalLabel').text(`${resourceObj.title} - Agregar comida`);
            $('#eventTitle').text('Agregar')
            $('#eventComida').val(1)
            $('#eventTextFecha').text(date.format())
            $('#eventForm').attr('action', action)
            $('#eventModal').modal('show')
          },
          eventClick: function(event){
            if(event.className.includes('clickableEvent') && event.id){
              let action = `{{ route('eventos.index') }}/comida/${event.id.slice(1)}`

              $('#eventModalLabel').text(`Eliminar comida`);
              $('#eventTitle').text('Eliminar')
              $('#eventComida').val(0)
              $('#eventTextFecha').text(event.start.format())
              $('#eventForm').attr('action', action)
              $('#eventModal').modal('show')
            }else{
              $('#eventForm').attr('action', '#')
            }
          },
        }
      )

      $('#eventForm').submit(storeEvent)
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
          if(r.evento.comida){
            $('#calendar').fullCalendar('renderEvent', {
              resourceId: r.evento.empleado_id,
              id: 'C' + r.evento.id,
              className: 'clickableEvent',
              title: 'Comida',
              start: r.evento.inicio,
              end: null,
              allDay: true,
              color: '#001f3f'
            });
          }else{
            $('#calendar').fullCalendar('removeEvents', 'C' + r.evento.id);
          }

          form[0].reset()
          $('#eventModal').modal('hide');
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

  </script>
@endsection
