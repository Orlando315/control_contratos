@extends('layouts.app')

@section('title', 'Inicio')

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
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-create')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contrato.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Contrato</a>
      @endpermission

      @permission('empleado-create')
        <div class="btn-group">
          <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle" aria-expanded="false"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Empleado</button>
          <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
            <li><a class="dropdown-item" href="{{ route('admin.empleado.create') }}">Nuevo Empleado</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.empleado.import.create') }}">Importar Empleados</a></li>
          </ul>
        </div>
      @endpermission

      @permission('cotizacion-create')
        <a class="btn btn-default btn-sm" href="{{ route('admin.cotizacion.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Cotización</a>
      @endpermission

      @permission('compra-create')
        <a class="btn btn-default btn-sm" href="{{ route('admin.compra.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Orden de compra</a>
      @endpermission
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-md-3">
      @permission('contrato-index')
        <div class="ibox mb-3">
          <div class="ibox-content p-0">
            <div class="widget style1">
              <div class="row align-items-end">
                <div class="col-4 text-center">
                  <i class="fa fa-clipboard fa-3x"></i>
                </div>
                <div class="col-8 text-right">
                  <span>Contratos</span>
                  <h2 class="font-bold">{{ $contratos }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endpermission

      @permission('inventario-v2-index')
        <div class="ibox mb-3">
          <div class="ibox-content p-0">
            <div class="widget style1">
              <div class="row align-items-end">
                <div class="col-4 text-center">
                  <i class="fa fa-cubes fa-3x"></i>
                </div>
                <div class="col-8 text-right">
                  <span>Inventarios V2</span>
                  <h2 class="font-bold">{{ $inventarios }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endpermission

      @permission('solicitud-index')
        <div class="ibox mb-3">
          <div class="ibox-content p-0">
            <div class="widget style1">
              <div class="row align-items-end">
                <div class="col-4 text-center">
                  <i class="fa fa-archive fa-3x"></i>
                </div>
                <div class="col-8 text-right">
                  <span>Solicitudes</span>
                  <h2 class="font-bold">{{ $solicitudes }}</h2>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endpermission
    </div>
    <div class="col-md-9">
      @permission('contrato-index|empleado-index|transporte-index')
        <div class="ibox">
          <div class="ibox-title">
            <h5>Contratos / Documentos por vencer</h5>
          </div>
          @permission('contrato-index')
            <div class="ibox-content py-1">
              <div class="row">
                <div class="col-12 text-center">
                  <p class="mb-1"><strong>Contratos</strong></p>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-muted" href="{{ route('admin.expiration', ['type' => 'contratos', 'days' => 21]) }}#vencidos" title="Contratos {{ $contratosPorVencer->vencidos }} / Documentos {{ $documentosContratosPorVencer->vencidos }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $contratosPorVencer->vencidos }} Contratos</span> <span class="label">{{ $documentosContratosPorVencer->vencidos }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>Vencidos</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-danger" href="{{ route('admin.expiration', ['type' => 'contratos', 'days' => 3]) }}" title="Contratos {{ $contratosPorVencer->lessThan3 }} / Documentos {{ $documentosContratosPorVencer->lessThan3 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $contratosPorVencer->lessThan3 }} Contratos</span> <span class="label">{{ $documentosContratosPorVencer->lessThan3 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>3 días</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-warning" href="{{ route('admin.expiration', ['type' => 'contratos', 'days' => 7]) }}" title="Contratos {{ $contratosPorVencer->lessThan7 }} / Documentos {{ $documentosContratosPorVencer->lessThan7 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $contratosPorVencer->lessThan7 }} Contratos</span> <span class="label">{{ $documentosContratosPorVencer->lessThan7 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>7 días</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-primary" href="{{ route('admin.expiration', ['type' => 'contratos', 'days' => 21]) }}" title="Contratos {{ $contratosPorVencer->lessThan21 }} / Documentos {{ $documentosContratosPorVencer->lessThan21 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $contratosPorVencer->lessThan21 }} Contratos</span> <span class="label">{{ $documentosContratosPorVencer->lessThan21 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>21 días</small>
                </div>
              </div>
            </div>
          @endpermission
          @permission('empleado-index')
            <div class="ibox-content py-1">
              <div class="row">
                <div class="col-12 text-center">
                  <p class="mb-1"><strong>Empleados</strong></p>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-muted" href="{{ route('admin.expiration', ['type' => 'empleados', 'days' => 21]) }}#vencidos" title="Contratos {{ $empleadosContratosPorVencer->vencidos }} / Documentos {{ $documentosEmpleadosPorVencer->vencidos }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $empleadosContratosPorVencer->vencidos }} Contratos</span> <span class="label">{{ $documentosEmpleadosPorVencer->vencidos }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>Vencidos</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-danger" href="{{ route('admin.expiration', ['type' => 'empleados', 'days' => 3]) }}" title="Contratos {{ $empleadosContratosPorVencer->lessThan3 }} / Documentos {{ $documentosEmpleadosPorVencer->lessThan3 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $empleadosContratosPorVencer->lessThan3 }} Contratos</span> <span class="label">{{ $documentosEmpleadosPorVencer->lessThan3 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>3 días</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-warning" href="{{ route('admin.expiration', ['type' => 'empleados', 'days' => 7]) }}" title="Contratos {{ $empleadosContratosPorVencer->lessThan7 }} / Documentos {{ $documentosEmpleadosPorVencer->lessThan7 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $empleadosContratosPorVencer->lessThan7 }} Contratos</span> <span class="label">{{ $documentosEmpleadosPorVencer->lessThan7 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>7 días</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-primary" href="{{ route('admin.expiration', ['type' => 'empleados', 'days' => 21]) }}" title="Contratos {{ $empleadosContratosPorVencer->lessThan21 }} / Documentos {{ $documentosEmpleadosPorVencer->lessThan21 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $empleadosContratosPorVencer->lessThan21 }} Contratos</span> <span class="label">{{ $documentosEmpleadosPorVencer->lessThan21 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>21 días</small>
                </div>
              </div>
            </div>
          @endpermission
          @permission('transporte-index')
            <div class="ibox-content py-1">
              <div class="row">
                <div class="col-12 text-center">
                  <p class="mb-1"><strong>Transportes</strong></p>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-muted" href="{{ route('admin.expiration', ['type' => 'transportes', 'days' => 21]) }}#vencidos" title="Documentos {{ $documentosTransportesPorVencer->vencidos }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $documentosTransportesPorVencer->vencidos }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>Vencidos</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-danger" href="{{ route('admin.expiration', ['type' => 'transportes', 'days' => 3]) }}" title="Documentos {{ $documentosTransportesPorVencer->lessThan3 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $documentosTransportesPorVencer->lessThan3 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>3 días</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-warning" href="{{ route('admin.expiration', ['type' => 'transportes', 'days' => 7]) }}" title="Documentos {{ $documentosTransportesPorVencer->lessThan7 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $documentosTransportesPorVencer->lessThan7 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>7 días</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                  <a class="main-expiration-clocks text-primary" href="{{ route('admin.expiration', ['type' => 'transportes', 'days' => 21]) }}" title="Documentos {{ $documentosTransportesPorVencer->lessThan21 }}">
                    <div class="w-100">
                      <i class="fa fa-clock-o fa-4x"></i>
                      <br>
                      <p class="m-0"><span class="label">{{ $documentosTransportesPorVencer->lessThan21 }} Documentos</span></p>
                    </div>
                  </a>
                  <small class=text-muted>21 días</small>
                </div>
              </div>
            </div>
          @endpermission
        </div>
      @endpermission
    </div>
  </div>

  @role('empleado')
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-money"></i> Sueldos</a></li>
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-level-up"></i> Anticipos</a></li>
            <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-long-arrow-up"></i> Egresos (Inventario V2)</a></li>
          </ul>
          <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Alcance líquido</th>
                    <th>Sueldo líquido</th>
                    <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(Auth::user()->sueldos as $sueldo)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $sueldo->created_at }}</td>
                        <td class="text-right">{{ $sueldo->alcanceLiquido() }}</td>
                        <td class="text-right">{{ $sueldo->sueldoLiquido() }}</td>
                        <td class="text-center">
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              <li>
                                <a class="dropdown-item" href="{{ route('sueldo.show', ['sueldo' => $sueldo->id]) }}">
                                  <i class="fa fa-search"></i> Ver
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                <div class="mb-3 text-right">
                  <a class="btn btn-primary btn-xs" href="{{ route('anticipo.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Solicitar anticipo</a>
                </div>
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Solicitud</th>
                      <th>Anticipo</th>
                      <th>Bono</th>
                      <th>Fecha</th>
                      <th>Descripción</th>
                      <th>Adjunto</th>
                      <th>Estatus</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(Auth::user()->empleado->anticipos()->get() as $anticipo)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center" title="Si el Empleado solicito o no el Anticipo"><small>{!! $anticipo->solicitud() !!}</small></td>
                        <td class="text-right">{{ $anticipo->anticipo() }}</td>
                        <td class="text-right">{{ $anticipo->bono() }}</td>
                        <td class="text-center">{{ $anticipo->fecha }}</td>
                        <td>@nullablestring($anticipo->descripcion)</td>
                        <td class="text-center">
                          @if($anticipo->adjunto)
                            <a href="{{ $anticipo->adjunto_download }}" title="Descargar adjunto">Descargar</a>
                          @else
                            @nullablestring(null)
                          @endif
                        </td>
                        <td class="text-center"><small>{!! $anticipo->status() !!}</small></td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div id="tab-3" class="tab-pane">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Inventario</th>
                      <th>Contrato</th>
                      <th>Cantidad</th>
                      <th>Descripción</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(Auth::user()->egresos()->with(['inventario', 'contrato'])->get() as $egreso)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>@nullablestring(optional($egreso->inventario)->nombre)</td>
                        <td>@nullablestring(optional($egreso->contrato)->nombre)</td>
                        <td class="text-right">{{ $egreso->cantidad() }}</td>
                        <td>@nullablestring($egreso->descripcion)</td>
                        <td class="text-center">
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              <li>
                                <a class="dropdown-item" href="{{ route('inventario.egreso.show', ['egreso' => $egreso->id]) }}">
                                  <i class="fa fa-search"></i> Ver
                                </a>
                              </li>
                            </ul>
                          </div>
                        </td>
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
            <li><a class="nav-link" href="#tab-22" data-toggle="tab">Eventos</a></li>
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
                        <td>@nullablestring($evento->fin)</td>
                        <td>{!! $evento->status() !!}</td>
                        <td>{{ optional($evento->created_at)->format('d-m-Y H:i:s')}}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="eventsModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="eventForm" action="{{ route('evento.store') }}" method="POST">
            <input id="eventDay" type="hidden" name="inicio" value="">
            @csrf

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

    <div id="gotoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="gotoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="gotoModalLabel">Seleccionar fecha</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="gotoDate">Fecha: *</label>
              <input id="gotoDate" class="form-control" type="text" placeholder="yyyy-mm-dd" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  @endrole
@endsection

@section('script')
  @role('empleado')
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
      const asistencias = @json(Auth::user()->empleado->getAsistencias());
      let calendar = null;

      $(document).ready(function(){
        calendar = $('#calendar').fullCalendar({
          schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
          locale: 'es',
          themeSystem: 'bootstrap4',
          bootstrapFontAwesome: {
            customDatePicker: 'fa-calendar',
          },
          header: {
            left:   'title',
            center: '',
            right:  'today,customDatePicker prev,next'
          },
          customButtons: {
            customDatePicker: {
              text: '',
              click: function() {
                $('#gotoModal').modal('show');
              },
            }
          },
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
        })

        $('#fin').datepicker({
          format: 'yyyy-mm-dd',
          startDate: 'today',
          language: 'es',
          keyboardNavigation: false,
          autoclose: true
        });

        $('#gotoDate').datepicker({
          format: 'yyyy-mm-dd',
          language: 'es',
          keyboardNavigation: false,
          autoclose: true
        }).on('changeDate', function(e){
          calendar.fullCalendar('gotoDate', moment($('#gotoDate').val()));
          $('#gotoModal').modal('hide');
        });

        $('#reemplazo, #tipo').select2({
          dropdownParent: $('#events-modal-body'),
          theme: 'bootstrap4',
          placeholder: 'Seleccione...',
        })

        $('#reemplazo').trigger('change')
        $('#eventForm').submit(storeEvent)

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
    </script>
  @endrole
@endsection
