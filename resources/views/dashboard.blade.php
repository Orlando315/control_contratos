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
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Usuarios</span>
          <span class="info-box-number">{{ count($usuarios) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-clipboard"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Contratos</span>
          <span class="info-box-number">{{ count($contratos) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    @endif
    
    @if(Auth::user()->tipo <= 3)
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-cubes"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Inventario</span>
          <span class="info-box-number">{{ count($inventarios) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
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
