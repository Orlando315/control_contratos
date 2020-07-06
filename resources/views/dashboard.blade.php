@extends('layouts.app')

@section('title', 'Inicio - '.config('app.name'))

@section('head')
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
                          <a class="btn btn-success btn-xs" href="{{ route('contratos.show', ['contrato' => $d->id] )}}"><i class="fa fa-search"></i></a>
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
                          <a class="btn btn-success btn-xs" href="{{ route('contratos.show', ['contrato' => $d->documentable_id] )}}"><i class="fa fa-search"></i></a>
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
                            <a class="btn btn-success btn-xs" href="{{ route('empleados.show', ['empleado' => $d->empleado_id] )}}"><i class="fa fa-search"></i></a>
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
                          <td>{{ $d->doumentable->usuario->nombres }}</td>
                          <td>{{ $d->nombre }}</td>
                          <td>{{ $d->vencimiento }}</td>
                          <td>
                            <a class="btn btn-success btn-xs" href="{{ route('empleados.show', ['id' => $d->documentable_id] )}}"><i class="fa fa-search"></i></a>
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
      <div class="col-md-12">
        <div class="ibox">
          <div class="ibox-title">
            <h5 class="text-center"><i class="fa fa-money"></i> Sueldos</h5>
          </div>
          <div class="ibox-content">
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
      </div>

      <div class="col-md-12">
        <div class="ibox">
          <div class="ibox-content">
            <div id="calendar"></div>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection

@section('script')
  @if(Auth::user()->empleado)
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
              $('#delEventModal').modal('show');
              $('#delEventForm').attr('action', '{{ route("eventos.index") }}/' + event.id);
            }else{
              $('#delEventForm').attr('action', '#');
            }
          }
        })
      })
    </script>
  @endif
@endsection
