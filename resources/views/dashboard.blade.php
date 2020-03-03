@extends( 'layouts.app' )
@section( 'title','Inicio - '.config( 'app.name' ) )
@section( 'header','Inicio' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-home" aria-hidden="true"></i> Inicio</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    @if(Auth::user()->tipo <= 2)
    <div class="col-md-3 col-sm-6 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ count($usuarios) }}</h3>

          <p>Administradores /<br>Supervisores</p>
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
        <a href="{{ route('usuarios.index') }}" class="small-box-footer">
          Ver usuarios <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ count($contratos) }}</h3>

          <p>Contratos</p>
        </div>
        <div class="icon">
          <i class="fa fa-clipboard"></i>
        </div>
        <a href="{{ route('contratos.index') }}" class="small-box-footer">
          Ver contratos <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    @endif
    
    @if(Auth::user()->tipo <= 3)
    <div class="col-md-3 col-sm-6 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ count($inventarios) }}</h3>

          <p>Inventarios</p>
        </div>
        <div class="icon">
          <i class="fa fa-cubes"></i>
        </div>
        <a href="{{ route('inventarios.index') }}" class="small-box-footer">
          Ver inventarios <i class="fa fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    @endif

    @if(Auth::user()->tipo <= 2)
      <div class="col-md-12">
        <h4 class="text-center" style="margin-bottom: 20px">Contratos / Documentos por vencer (Menos de {{ Auth::user()->empresa->configuracion->dias_vencimiento }} días)</h4>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-clipboard"></i> Contractos</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-clone"></i> Documentos</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <table class="table data-table table-bordered table-hover" style="width: 100%">
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
                        <a class="btn btn-primary btn-flat btn-sm" href="{{ route('contratos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
              <table class="table data-table table-bordered table-hover" style="width: 100%">
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
                      <td>{{ $d->contrato->nombre }}</td>
                      <td>{{ $d->nombre }}</td>
                      <td>{{ $d->vencimiento }}</td>
                      <td>
                        <a class="btn btn-primary btn-flat btn-sm" href="{{ route('contratos.show', ['id' => $d->contrato_id] )}}"><i class="fa fa-search"></i></a>
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

      <div class="col-md-12">      
        <h4 class="text-center" style="margin-bottom: 20px">Contratos / Documentos de Empleados por vencer (Menos de {{ Auth::user()->empresa->configuracion->dias_vencimiento }} días)</h4>
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_3" data-toggle="tab" aria-expanded="true"><i class="fa fa-clipboard"></i> Contractos</a></li>
            <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false"><i class="fa fa-clone"></i> Documentos</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_3">
              <table class="table data-table table-bordered table-hover" style="width: 100%">
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
                        <a class="btn btn-primary btn-flat btn-sm" href="{{ route('empleados.show', ['id' => $d->empleado_id] )}}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_4">
              <table class="table data-table table-bordered table-hover" style="width: 100%">
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
                      <td>{{ $d->empleado->usuario->nombres }}</td>
                      <td>{{ $d->nombre }}</td>
                      <td>{{ $d->vencimiento }}</td>
                      <td>
                        <a class="btn btn-primary btn-flat btn-sm" href="{{ route('empleados.show', ['id' => $d->contrato_id] )}}"><i class="fa fa-search"></i></a>
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
    @endif
    
    @if(Auth::user()->tipo >= 3)
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-money"></i> Sueldos</h3>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
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
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('sueldos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

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
    @endif
  </div>

  <div class="row">
    <div class="col-md-12">
    </div>
  </div>
@endsection

@section('scripts')
@if(Auth::user()->tipo >= 3)
<script type="text/javascript">
  let jornada     = @json(Auth::user()->empleado->proyectarJornada()),
      eventos     = @json(Auth::user()->empleado->getEventos()),
      feriados    = @json(Auth::user()->empleado->getFeriados()),
      comidas     = @json(Auth::user()->empleado->getComidasToCalendar()),
      asistencias = @json(Auth::user()->empleado->getAsistencias());

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
