@extends('layouts.app')

@section('title', 'Inicio - '.config('app.name'))

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <!-- Fullcalendar -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/fullcalendar/fullcalendar.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/fullcalendar/scheduler.min.css') }}">
@endsection

@section('content')
  <div class="row">
    @if(Auth::user()->tipo <= 2)
      <div class="col-6 col-md-3">
        <div class="ibox ">
          <div class="ibox-title">
            <h5>Contratos</h5>
          </div>
          <div class="ibox-content">
            <h2 class="">
              <i class="fa fa-clipboard text-warning"></i> {{ count($contratos) }}
            </h2>
          </div>
        </div>
      </div>
    @endif

    @if(Auth::user()->tipo <= 3)
      <div class="col-6 col-md-3">
        <div class="ibox">
          <div class="ibox-title">
            <h5>Inventarios</h5>
          </div>
          <div class="ibox-content">
            <h2 class="">
              <i class="fa fa-cubes text-danger"></i> {{ count($inventarios) }}
            </h2>
          </div>
        </div>
      </div>
    @endif
  </div>

  <div class="row">
    @if(Auth::user()->tipo <= 2)
      <div class="col-md-12">
        <div class="ibox">
          <div class="ibox-title">
            <h5>Contratos / Documentos por vencer (Menos de {{ Auth::user()->empresa->configuracion->dias_vencimiento }} días)</h5>
          </div>
          <div class="ibox-content">
            <ul class="nav nav-tabs" role="tablist">
              <li><a class="nav-link active" href="#tab-1" data-toggle="tab" aria-expanded="true"><i class="fa fa-clipboard"></i> Contratos</a></li>
              <li><a class="nav-link" href="#tab-2" data-toggle="tab" aria-expanded="false"><i class="fa fa-clone"></i> Documentos</a></li>
            </ul>
            <div class="tab-content pt-3">
              <div class="tab-pane active" id="tab-1" role="tabpanel" aria-labelledby="contratos-tab">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Inicio</th>
                      <th class="text-center">Fin</th>
                      <th class="text-center">Valor</th>
                      <th class="text-center">Empleados</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contratosPorVencer as $d)
                      <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $d->nombre }}</td>
                        <td>{{ $d->inicio }}</td>
                        <td>{{ $d->fin }}</td>
                        <td>{{ $d->valor() }}</td>
                        <td>{{ $d->empleados->count() }}</td>
                        <td>
                          <a class="btn btn-success btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $d->id] )}}"><i class="fa fa-search"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab-2"  role="tabpanel" aria-labelledby="documentos-tab">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Contrato</th>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Vencimiento</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($documentosDeContratosPorVencer as $d)
                      <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $d->documentable->nombre }}</td>
                        <td>{{ $d->nombre }}</td>
                        <td>{{ $d->vencimiento }}</td>
                        <td>
                          <a class="btn btn-success btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $d->documentable_id] )}}"><i class="fa fa-search"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="ibox">
          <div class="ibox-title">
            <h5>Contratos / Documentos de Empleados por vencer (Menos de {{ Auth::user()->empresa->configuracion->dias_vencimiento }} días)</h5>
          </div>
          <div class="ibox-content">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li><a class="nav-link active" href="#tab-3" data-toggle="tab" aria-expanded="true"><i class="fa fa-clipboard"></i> Contratos</a></li>
                <li><a class="nav-link" href="#tab-4" data-toggle="tab" aria-expanded="false"><i class="fa fa-clone"></i> Documentos</a></li>
              </ul>
              <div class="tab-content pt-3">
                <div class="tab-pane active" id="tab-3" role="tabpanel" aria-labelledby="contratos-tab">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Empleado</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Jornada</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($empleadosContratosPorVencer as $d)
                        <tr>
                          <td>{{ $loop->index + 1 }}</td>
                          <td>{{ $d->empleado->usuario->nombres }}</td>
                          <td>{{ $d->inicio }}</td>
                          <td>{{ $d->fin }}</td>
                          <td>{{ $d->jornada }}</td>
                          <td>
                            <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $d->empleado_id] )}}"><i class="fa fa-search"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab-4" role="tabpanel" aria-labelledby="documentos-tab">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Empleado</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Vencimiento</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($documentosDeEmpleadosPorVencer as $d)
                        <tr>
                          <td>{{ $loop->index + 1 }}</td>
                          <td>{{ $d->documentable->usuario->nombres }}</td>
                          <td>{{ $d->nombre }}</td>
                          <td>{{ $d->vencimiento }}</td>
                          <td>
                            <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['id' => $d->documentable_id] )}}"><i class="fa fa-search"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
          </div>
        </div>
      </div>
    @endif
    
    @if(Auth::user()->empleado)
      @if(Auth::user()->tipo <= 2)
        <div class="col-md-12">
          <h3 class="text-center"> Información como Empleado</h3>
        </div>
      @endif
      <div class="col-md-12 mb-3">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-money"></i> Sueldos</a></li>
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-level-up"></i> Anticipos</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-1">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Alcance líquido</th>
                    <th class="text-center">Sueldo líquido</th>
                    <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach(Auth::user()->sueldos()->get() as $d)
                      <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $d->created_at }}</td>
                        <td>{{ $d->alcanceLiquido() }}</td>
                        <td>{{ $d->sueldoLiquido() }}</td>
                        <td>
                          <a class="btn btn-success btn-xs" href="{{ route('sueldos.show', ['suedldo' => $d->id] )}}"><i class="fa fa-search"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-2">
              <div class="panel-body">
                <div class="mb-3 text-right">
                  <a class="btn btn-primary btn-xs" href="{{ route('anticipos.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Solicitar anticipo</a>
                </div>
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Anticipo</th>
                      <th class="text-center">Bono</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Descripción</th>
                      <th class="text-center">Adjunto</th>
                      <th class="text-center">Estatus</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach(Auth::user()->empleado->anticipos()->get() as $anticipo)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $anticipo->anticipo() }}</td>
                        <td>{{ $anticipo->bono() }}</td>
                        <td>{{ $anticipo->fecha }}</td>
                        <td>{{ $anticipo->descripcion }}</td>
                        <td>
                          @if($anticipo->adjunto)
                            <a href="{{ $anticipo->adjunto_download }}" title="Descargar adjunto">Descargar</a>
                          @else
                            N/A
                          @endif
                        </td>
                        <td>{!! $anticipo->status() !!}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            <li><a class="nav-link active" href="#tab-21" data-toggle="tab">Calendario</a></li>
            @if(Auth::user()->isEmpleado())
              <li><a class="nav-link" href="#tab-22" data-toggle="tab">Eventos</a></li>
            @endif
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-21">
              <div class="panel-body">
                <div class="alert alert-success alert-calendar" style="display: none">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong class="text-center">Solicitud enviada exitosamente.</strong> 
                </div>
                <div id="calendar"></div>
              </div>
            </div>
            @if(Auth::user()->isEmpleado())
              <div class="tab-pane" id="tab-22">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Tipo</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">Agregado</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach(Auth::user()->empleado->eventos()->notAsistencias()->latest()->get() as $evento)
                        <tr id="evento-{{ $evento->id }}">
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $evento->tipo() }}</td>
                          <td>{{ $evento->inicio }}</td>
                          <td>{{ $evento->fin ?? 'N/A' }}</td>
                          <td>{!! $evento->status() !!}</td>
                          <td>{{ optional($evento->created_at)->format('d-m-Y H:i:s')}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endif
  </div>

  @if(Auth::user()->isEmpleado())
    <div id="delEventModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delEventModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delEventForm" action="#" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delEventModalLabel">Eliminar Evento</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Desea eliminar este evento?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="eventsModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="eventForm" action="{{ route('eventos.store') }}" method="POST">
            <input id="eventDay" type="hidden" name="inicio" value="">
            {{ csrf_field() }}

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Agregar evento</h4>
            </div>
            <div id="events-modal-body" class="modal-body">
              <div class="alert alert-danger" style="display: none">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong class="text-center">Ha ocurrido un error.</strong> 
              </div>

              <h4 class="text-center" id="eventTitle"></h4>

              <div class="form-group">
                <label for="tipo">Evento: *</label>
                <select id="tipo" class="form-control" name="tipo" required style="width: 100%">
                  <option value="">Seleccione...</option>
                  <option value="2">Licencia médica</option>
                  <option value="3">Vacaciones</option>
                  <option value="4">Permiso</option>
                  <option value="5">Permiso no remunerable</option>
                  @if(Auth::user()->empleado->despidoORenuncia())
                    <option value="6">Despido</option>
                    <option value="7">Renuncia</option>
                  @endif
                  <option value="8">Inasistencia</option>
                  <option value="9">Reemplazo</option>
                </select>
              </div>

              <div class="form-group">
                <label for="fin">Fin: <small>(Opcional)</small></label>
                <input id="fin" class="form-control" type="text" name="fin" placeholder="yyyy-mm-dd">
              </div>

              <div class="form-group{{ $errors->has('reemplazo') ? ' has-error' : '' }}" style="display: none">
                <label for="reemplazo">Reemplazo: *</label>
                <select id="reemplazo" class="form-control" name="reemplazo" required style="width: 100%">
                  <option value="">Seleccione...</option>
                  @foreach($otrosEmpleados as $d)
                    <option value="{{ $d->id }}">{{ $d->usuario->rut }} | {{ $d->usuario->nombres }} {{ $d->usuario->apellidos }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group" hidden>
                <label for="valor">Valor: *</label>
                <input id="valor" class="form-control" type="number" step="1" min="1" max="999999999" name="valor" placeholder="Valor" rqeuired>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('script')
  @if(Auth::user()->isEmpleado())
    <!-- Datepicker -->
    <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <!-- Fullcalendar -->
    <script type="text/javascript" src="{{ asset('js/plugins/fullcalendar/lib/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/fullcalendar/locale/es.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/fullcalendar/scheduler.min.js') }}"></script>
    <script type="text/javascript">
      const jornada     = @json(Auth::user()->empleado->proyectarJornada());
      const eventos     = @json(Auth::user()->empleado->getEventos());
      const feriados    = @json(Auth::user()->empleado->getFeriados());
      const comidas     = @json(Auth::user()->empleado->getComidasToCalendar());
      const asistencias = @json(Auth::user()->empleado->getAsistencias());

      $(document).ready(function(){
        $('#calendar').fullCalendar({
          locale: 'es',
          eventSources:
          [
            {
              events: jornada.trabajo,
              color: '#00a65a',
              textcolor: 'white'
            },
            {
              events: jornada.descanso,
              color: '#9c9c9c',
              textcolor: 'white'
            },
            {
              events: feriados,
              color: '#f39c12',
              textcolor: 'white'
            },
            {
              events: eventos,
            },
            {
              events: comidas
            },
            {
              events: asistencias,
              color: '#00a65a',
              textcolor: 'white'
            }
          ],
          dayClick: function(date){
            $('#eventTitle').text(date.format())
            $('#eventDay').val(date.format())
            $('#eventsModal').modal('show')
          },
          eventClick: function(event){
            if(event.id){
              // $('#delEventModal').modal('show');
              // $('#delEventForm').attr('action', '{{ route("eventos.index") }}/' + event.id);
            }else{
              // $('#delEventForm').attr('action', '#');
            }
          }
        })

        $('#fin').datepicker({
          format: 'yyyy-mm-dd',
          startDate: 'today',
          language: 'es',
          keyboardNavigation: false,
          autoclose: true
        });

        $('#reemplazo, #tipo').select2({
          dropdownParent: $('#events-modal-body'),
          theme: 'bootstrap4',
          placeholder: 'Seleccione...',
        })

        $('#reemplazo').trigger('change')

        $('#eventForm').submit(storeEvent)
        ///$('#delEventForm').submit(delEvent)

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
            .toggle(isReemplazo)
        })
      })

      function storeEvent(e){
        e.preventDefault();

        let form = $(this),
            action = form.attr('action'),
            alert  = $('#eventsModal .alert');
            button = form.find('button[type="submit"]');

        console.log('Submiting...')
        button.prop('disabled', true);
        alert.hide();

        console.log('Submiting...')

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
            $('.alert-calendar').show().delay(5000).hide('slow')
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
          button.prop('disabled', false);
        })
      }

      function delEvent(e){
        e.preventDefault();

        let form = $(this),
            action = form.attr('action'),
            alert  = form.find('.alert');
            button = form.find('button[type="submit"]');

        button.prop('disabled', true);
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
          button.prop('disabled', false);
        })
      }
    </script>
  @endif
@endsection
