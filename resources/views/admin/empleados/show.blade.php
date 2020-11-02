@extends('layouts.app')

@section('title', 'Empleado')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <!-- Fullcalendar -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/fullcalendar/scheduler.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empleados</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Contratos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.show', ['contrato' => $empleado->contrato_id]) }}">Contrato</a></li>
        <li class="breadcrumb-item active"><strong>Empleado</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.show', ['contrato' => $empleado->contrato_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.empleados.edit', ['empleado' => $empleado->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.empleados.contrato.create', ['empleado' => $empleado->id]) }}"><i class="fa fa-refresh" aria-hidden="true"></i> Cambio de jornada</a>
      @if($empleado->usuario->tipo > 1)
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#toggleModal"><i class="fa fa-exchange" aria-hidden="true"></i> Cambiar rol</button>
      @endif
      <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#contratoModal"><i class="fa fa-refresh" aria-hidden="true"></i> Cambio de contrato</button>
      <a class="btn btn-default btn-sm" href="{{ route('admin.empleados.print', ['empleado' => $empleado->id]) }}" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</a>
      @if($empleado->usuario->tipo > 2 || ($empleado->usuario->tipo <= 2 && Auth::user()->tipo <= 2))
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endif
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title px-3">
          <h5>Datos del Empleado</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right"><a href="{{ route('admin.contratos.show', ['contrato' => $empleado->contrato_id]) }}">{{ $empleado->contrato->nombre }}</a></span>
            </li>
            <li class="list-group-item">
              <b>Usuario</b>
              <span class="pull-right">
                <a href="{{ route('admin.usuarios.show', ['usuario' => $empleado->usuario->id]) }}">
                  {{ $empleado->usuario->usuario }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ $empleado->usuario->nombres }}</span>
            </li>
            <li class="list-group-item">
              <b>Apellidos</b>
              <span class="pull-right">{{ $empleado->usuario->apellidos }}</span>
            </li>
            <li class="list-group-item">
              <b>Sexo</b>
              <span class="pull-right">{{ $empleado->sexo }}</span>
            </li>
            <li class="list-group-item">
              <b>Fecha de nacimiento</b>
              <span class="pull-right">{{ $empleado->fecha_nacimiento }}</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right"> {{ $empleado->usuario->rut }} </span>
            </li>
            <li class="list-group-item">
              <b>Dirección</b>
              <span class="pull-right"> {{ $empleado->direccion }} </span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right"> {{ $empleado->usuario->telefono ?? 'N/A' }} </span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">{{ $empleado->usuario->email ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Profesión</b>
              <span class="pull-right">{{ $empleado->profesion ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Talla de camisa</b>
              <span class="pull-right">{{ $empleado->talla_camisa ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Talla de zapato</b>
              <span class="pull-right">{{ $empleado->talla_zapato ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Talla de pantalon</b>
              <span class="pull-right">{{ $empleado->talla_pantalon ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ optional($empleado->created_at)->format('d-m-Y H:i:s') }}</small>
            </li>
          </ul>
        </div><!-- /.ibox-content -->
      </div>
    </div>
    <div class="col-md-9">
      <div class="row">
        <div class="col-md-4">
          <div class="ibox">
            <div class="ibox-title px-3">
              <h5>Contrato</h5>
              <div class="ibox-tools">
                <a class="btn btn-default btn-xs" href="{{ route('admin.empleados.contrato.edit', ['empleado' => $empleado->id]) }}" title="Editar contrato"><i class="fa fa-pencil"></i></a>
                <button class="btn btn-default btn-xs" title="Ver historial" data-toggle="modal" data-target="#historyModal"><i class="fa fa-list"></i></button>
              </div>
            </div>
            <div class="ibox-content no-padding">
              <ul class="list-group">
                <li class="list-group-item">
                  <b>Jornada</b>
                  <span class="pull-right">{{ $empleado->lastContrato->jornada }}</span>
                </li>
                <li class="list-group-item">
                  <b>Sueldo</b>
                  <span class="pull-right">{{ number_format($empleado->lastContrato->sueldo, 0, ',', '.') }}</span>
                </li>
                <li class="list-group-item">
                  <b>Inicio</b>
                  <span class="pull-right">{{ $empleado->lastContrato->inicio }}</span>
                </li>
                <li class="list-group-item">
                  <b>Inicio de Jornada</b>
                  <span class="pull-right"> {{$empleado->lastContrato->inicio_jornada}} </span>
                </li>
                <li class="list-group-item">
                  <b>Fin</b>
                  <span class="pull-right"> {!! $empleado->lastContrato->fin ?? '<span class="text-muted">Indefinido</span>' !!} </span>
                </li>
                <li class="list-group-item">
                  <b>Descripción</b>
                  <span class="pull-right"> {!! $empleado->lastContrato->descripcion ?? 'N/A' !!} </span>
                </li>
              </ul>
            </div><!-- /.ibox-content -->
          </div>
        </div>
        <div class="col-md-4">
          <div class="ibox">
            <div class="ibox-title px-3">
              <h5>Datos Bancarios</h5>
            </div>
            <div class="ibox-content no-padding">
              <ul class="list-group">
                <li class="list-group-item">
                  <b>Banco</b>
                  <span class="pull-right">{{ $empleado->banco->nombre }}</span>
                </li>
                <li class="list-group-item">
                  <b>Tipo de cuenta</b>
                  <span class="pull-right">{{ $empleado->banco->tipo_cuenta }}</span>
                </li>
                <li class="list-group-item">
                  <b>Cuenta</b>
                  <span class="pull-right"> {{ $empleado->banco->cuenta }} </span>
                </li>
              </ul>
            </div><!-- /.ibox-content -->
          </div>    
        </div>
        <div class="col-md-4">
          <div class="ibox">
            <div class="ibox-title px-3">
              <h5>Contacto de emergencia</h5>
            </div>
            <div class="ibox-content no-padding">
              <ul class="list-group">
                <li class="list-group-item">
                  <b>Nombre</b>
                  <span class="pull-right">{{ $empleado->nombre_emergencia ?? 'N/A' }}</span>
                </li>
                <li class="list-group-item">
                  <b>Teléfono</b>
                  <span class="pull-right">{{ $empleado->telefono_emergencia ?? 'N/A' }}</span>
                </li>
              </ul>
            </div><!-- /.ibox-content -->
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <div class="tabs-container">
            <ul class="nav nav-tabs">
              <li><a class="nav-link active" href="#tab-13" data-toggle="tab"><i class="fa fa-asterisk"></i> Requisitos</a></li>
              <li><a class="nav-link" href="#tab-11" data-toggle="tab"><i class="fa fa-paperclip"></i> Adjuntos</a></li>
              <li><a class="nav-link" href="#tab-12" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documentos</a></li>
              <li><a class="nav-link" href="#tab-14" data-toggle="tab"><i class="fa fa-archive"></i> Solicitudes</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab-13">
                <div class="panel-body">
                  <div class="row">
                    @forelse($empleado->requisitos() as $requisito)
                      <div class="col-lg-4">
                        <div class="ibox">
                          <div class="ibox-content p-2">
                            <div class="row">
                              <div class="col-9">
                                <i class="fa {{ $requisito->documento ? 'fa-check-square text-primary' : 'fa-square-o text-muted' }}"></i>
                                @if($requisito->documento)
                                  <a href="{{ route('admin.documentos.download', ['adjunto' => $requisito->documento->id]) }}">
                                    {{ $requisito->nombre }}
                                    @if($requisito->documento->vencimiento)
                                      <small class="text-muted">- {{ $requisito->documento->vencimiento }}</small>
                                    @endif
                                  </a>
                                @else
                                  {{ $requisito->nombre }}
                                @endif
                              </div>
                              <div class="col-3">
                                <div class="btn-group">
                                  <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                  <ul class="dropdown-menu" x-placement="bottom-start">
                                    @if($requisito->documento)
                                      <li><a class="dropdown-item" href="{{ route('admin.documentos.edit', ['documento' => $requisito->documento->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                      <li><a class="dropdown-item text-danger" type="button" title="Eliminar requisito" data-url="{{ route('admin.documentos.destroy', ['documento' => $requisito->documento->id]) }}" data-toggle="modal" data-target="#delFileModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</a></li>
                                    @else
                                      <li><a class="dropdown-item" href="{{ route('admin.documentos.create', ['type' => 'empleados', 'id' => $empleado->id, 'carpeta' => null, 'requisito' => $requisito->id]) }}"><i class="fa fa-plus"></i> Agregar</a></li>
                                    @endif
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @empty
                      <div class="col-12">
                        <p class="text-muted text-center mb-1">No hay requisitos</p>
                      </div>
                    @endforelse
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab-11">
                <div class="panel-body">
                  <div class="mb-3">
                    <a class="btn btn-warning btn-sm" href="{{ route('admin.carpeta.create', ['type' => 'empleados', 'id' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                    @if($empleado->documentos->count() < 10)
                      <a class="btn btn-primary btn-sm" href="{{ route('admin.documentos.create', ['type' => 'empleados', 'id' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                    @endif
                  </div>
                  <div class="row icons-box icons-folder">
                    @foreach($empleado->carpetas()->main()->get() as $carpeta)
                      <div class="col-md-3 col-xs-4 infont mb-3">
                        <a href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}">
                          <i class="fa fa-folder" aria-hidden="true"></i>
                          <p class="m-0">{{ $carpeta->nombre }}</p>
                        </a>
                      </div>
                    @endforeach
                  </div>

                  <hr class="hr-line-dashed">

                  <div class="row">
                    @forelse($empleado->documentos()->main()->get() as $documento)
                      @include('partials.documentos', ['edit' => true])
                    @empty
                      <div class="col-12">
                        <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
                      </div>
                    @endforelse
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="tab-12">
                <div class="panel-body">
                  <div class="mb-3">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.plantilla.documento.create', ['contrato' => $empleado->contrato_id, 'empleado' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo documento</a>
                  </div>
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Documento</th>
                        <th class="text-center">Empleado</th>
                        <th class="text-center">Padre</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($empleado->plantillaDocumentos as $d)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $d->nombre }}</td>
                          <td>{{ $d->empleado->nombre() }}</td>
                          <td>{{ optional($d->padre)->nombre ?? 'N/A' }}</td>
                          <td>
                            <a class="btn btn-success btn-xs" href="{{ route('admin.plantilla.documento.show', ['documento' => $d->id] )}}"><i class="fa fa-search"></i></a>
                            <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.edit', ['documento' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane" id="tab-14">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Tipo</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">Adjunto</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($empleado->solicitudes as $solicitud)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td> {{ $solicitud->tipo() }}</td>
                          <td>{{ $solicitud->descripcion ?? 'N/A' }}</td>
                          <td>{!! $solicitud->status() !!}</td>
                          <td>
                            @if($solicitud->adjunto)
                              <a href="{{ $solicitud->download }}" title="Descargar adjunto">Descargar</a>
                            @else
                              N/A
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-success btn-xs" href="{{ route('admin.solicitud.show', ['inventario' => $solicitud->id] )}}"><i class="fa fa-search"></i></a>
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
      </div>
    </div>
  </div>

  <div class="row mb-5">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-money"></i> Sueldos</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-level-up"></i> Anticipos</a></li>
          <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-retweet"></i> Reemplazos</a></li>
          <li><a class="nav-link" href="#tab-4" data-toggle="tab"><i class="fa fa-arrow-right"></i> Entregas de Inventario</a></li>
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
                  @foreach($empleado->sueldos()->get() as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->created_at }}</td>
                      <td>{{ $d->alcanceLiquido() }}</td>
                      <td>{{ $d->sueldoLiquido() }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.sueldos.show', ['sueldo' => $d->id] )}}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="tab-2">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Anticipo</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->anticipos as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->fecha }}</td>
                      <td>{{ $d->anticipo() }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.anticipos.show', ['anticipo' => $d->id] )}}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="tab-3">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Reemplazó a</th>
                    <th class="text-center">Valor</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->reemplazos()->get() as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $d->inicio }}</td>
                      <td>{!! $d->nombreReemplazo() !!}</td>
                      <td>{{ $d->valor() }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="tab-4">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Realizado por</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Recibido</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->entregas()->get() as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td><a href="{{ route('admin.inventarios.show', ['inventario' => $d->inventario_id]) }}">{{ $d->inventario->nombre }}</a></td>
                      <td>{{ $d->realizadoPor->nombres }} {{ $d->realizadoPor->apellidos }}</td>
                      <td>{{ $d->cantidad() }}</td>
                      <td>{{ $d->created_at }}</td>
                      <td>{!! $d->recibido() !!}</td>
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

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-21" data-toggle="tab">Calendario</a></li>
          <li><a class="nav-link" href="#tab-22" data-toggle="tab">Eventos</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-21">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exportModal"><i class="fa fa-file-excel-o"></i> Exportar a excel</button>
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
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->eventos()->notAsistencias()->latest()->get() as $evento)
                    <tr id="evento-{{ $evento->id }}">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $evento->tipo() }}</td>
                      <td>{{ $evento->inicio }}</td>
                      <td>{{ $evento->fin ?? 'N/A' }}</td>
                      <td>{!! $evento->status() !!}</td>
                      <td>{{ optional($evento->created_at)->format('d-m-Y H:i:s')}}</td>
                      <td>
                        <div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                          <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                            @if($evento->isPendiente())
                              <li><a class="dropdown-item" type="button" data-url="{{ route('admin.eventos.status', ['evento' => $evento->id] ) }}" data-type="1" data-toggle="modal" data-target="#statusEventoModal"><i class="fa fa-check"></i> Aprobar</a></li>
                              <li><a class="dropdown-item" type="button" data-url="{{ route('admin.eventos.status', ['evento' => $evento->id] ) }}" data-type="0" data-toggle="modal" data-target="#statusEventoModal"><i class="fa fa-ban"></i> Rechazar</a></li>
                            @endif
                            <li><a class="dropdown-item" type="button" data-toggle="modal" data-target="#delEventModal" data-url="{{ route('admin.eventos.destroy', ['evento' => $evento->id]) }}"><i class="fa fa-times"></i> Eliminar</a></li>
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
  </div>
  
  @if($empleado->usuario->tipo > 1)
    <div id="toggleModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="toggleModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.empleados.toggleTipo', ['empleado' => $empleado->id]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="toggleModalLabel">Cambiar de rol</h4>
            </div>
            <div class="modal-body">
              <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                <label>Seleccionar role:</label>
                @if(Auth::user()->tipo <= 2)
                  <div class="custom-control custom-radio m-0">
                    <input id="role-admin" class="custom-control-input" type="radio" name="role" value="2"{{ $empleado->usuario->tipo == 2 ? ' checked' : '' }}>
                    <label for="role-admin" class="custom-control-label">Administrador</label>
                  </div>
                @endif
                <div class="custom-control custom-radio">
                  <input id="role-supervisor" class="custom-control-input" type="radio" name="role" value="3"{{ $empleado->usuario->tipo == 3 ? ' checked' : '' }}>
                  <label for="role-supervisor" class="custom-control-label">Supervisor</label>
                </div>
                <div class="custom-control custom-radio">
                  <input id="role-empleado" class="custom-control-input" type="radio" name="role" value="4"{{ ($empleado->usuario->tipo == 4 || $empleado->usuario->tipo == 5) ? ' checked' : '' }}>
                  <label for="role-empleado" class="custom-control-label">Empleado</label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-success btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  <div id="contratoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="contratoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.empleados.contrato.cambio', ['empleado' => $empleado->id]) }}" method="POST">
          {{ method_field('PATCH') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="contratoModalLabel">Cambiar de contrato</h4>
          </div>
          <div id="contrato-modal-body" class="modal-body">
            <div class="form-group {{ $errors->has('contrato') ? 'has-error' : '' }}">
              <label class="control-label" for="contrato">Contrato: *</label>
              <select id="contrato" class="form-control" name="contrato" required style="width: 100%">
                <option value="">Seleccione...</option>
                  @foreach($contratos as $contrato)
                    @if($contrato->id != $empleado->contrato->id)
                      <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
                    @endif
                  @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-success btn-sm" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="historyModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
          </button>
          <h4 class="modal-title" id="historyModalLabel">Historial de contratos</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            @foreach($empleado->contratos as $contrato)
              <div class="col-md-4">
                <ul class="list-group mb-3">
                  <li class="list-group-item">
                    <b>Creado</b>
                    <span class="pull-right">{{ optional($contrato->created_at)->format('d-m-Y H:i:s') }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Jornada</b>
                    <span class="pull-right">{{ $contrato->jornada }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Sueldo</b>
                    <span class="pull-right">{{ number_format($contrato->sueldo, 0, ',', '.') }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Inicio</b>
                    <span class="pull-right">{{ $contrato->inicio }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Inicio de Jornada</b>
                    <span class="pull-right"> {{$contrato->inicio_jornada}} </span>
                  </li>
                  <li class="list-group-item">
                    <b>Fin</b>
                    <span class="pull-right"> {!! $contrato->fin ?? '<span class="text-muted">Indefinido</span>' !!} </span>
                  </li>
                  <li class="list-group-item">
                    <b>Descripción</b>
                    <span class="pull-right"> {{ $contrato->descripcion ?? 'N/A' }}</span>
                  </li>
                </ul>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="delFileModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="delete-file-form" action="#" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delFileModalLabel">Eliminar documento</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($empleado->usuario->tipo > 2 || ($empleado->usuario->tipo <= 2 && Auth::user()->tipo <= 2))
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.empleados.destroy', ['empleado' => $empleado->id]) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar empleado</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Empleado?</h4>
              @if($empleado->usuario->tipo == 2)
                <p class="text-center">El empleado tambien cuenta con perfil de Administrador. Si marca esta opción tamien se eliminará el Usuario Administrador relacionado al Empleado</p>
                <div class="custom-control custom-checkbox">
                  <input id="customCheck1" class="custom-control-input" type="checkbox" name="eliminar_admin" value="1">
                  <label class="custom-control-label" for="customCheck1">Eliminar usuario administrador</label>
                  <small class="form-text text-muted">Se eliminará toda la infomación relacionada con el Usuario</small>
                </div>
              @endif
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

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
            <button class="btn btn-danger btn-sm" type="submit" disabled>Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="eventsModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="eventForm" action="{{ route('admin.eventos.store', ['empleado' => $empleado->id]) }}" method="POST">
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
                @if(!$empleado->despidoORenuncia())
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

            <div class="form-group{{ $errors->has('reemplazo') ? ' has-error' : '' }}" hidden>
              <label for="reemplazo">Reemplazo: *</label>
              <select id="reemplazo" class="form-control" name="reemplazo" required style="width: 100%">
                <option value="">Seleccione...</option>
                @foreach($empleados as $d)
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

  <div id="exportModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.empleados.export', ['empleado' => $empleado->id]) }}" method="POST">
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="exportModalLabel">Exportar a excel</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="inicioExport">Inicio: *</label>
              <input id="inicioExport" class="form-control" type="text" name="inicio" placeholder="yyyy-mm-dd" required>
            </div>

            <div class="form-group">
              <label for="finExport">Fin: *</label>
              <input id="finExport" class="form-control" type="text" name="fin" placeholder="yyyy-mm-dd" rqeuired>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-success btn-sm" type="submit">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="statusEventoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusEventoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="status-modal-form" action="#" method="POST">
          <input id="status-modal-value" type="hidden" name="status">
          {{ method_field('PUT') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="statusEventoModalLabel">Cambiar estatus</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de <span id="status-modal-label"></span> este Evento?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="submit" disabled>Enviar</button>
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
@endsection

@section('script')
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
    let calendar = null;
    let jornada = @json($empleado->proyectarJornada()),
        eventos = @json($empleado->getEventos()),
        feriados = @json($empleado->getFeriados());
   	
    $(document).ready(function(){
      $('#delFileModal').on('show.bs.modal', function(e){
        let button = $(e.relatedTarget),
            action = button.data('url');

        $('#delete-file-form').attr('action', action);
      });

      $('#fin').datepicker({
        format: 'yyyy-mm-dd',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#inicioExport, #finExport').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      }).on('changeDate', function(e){
        let inicio = new Date($('#inicioExport').val()),
            fin = new Date($('#finExport').val());

        if(inicio > fin){
          inicio.setDate(inicio.getDate() + 1)
          let newDate = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate()
          $('#finExport').datepicker('setDate', newDate)
        }
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

      $('#contrato').select2({
        dropdownParent: $('#contrato-modal-body'),
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

      $('#reemplazo, #tipo').select2({
        dropdownParent: $('#events-modal-body'),
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

      $('#reemplazo').trigger('change')

      $('#eventForm').submit(storeEvent)
      $('#delEventForm').submit(delEvent)

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
        eventSources: [{
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
        }],
        dayClick: function(date){
          $('#eventTitle').text(date.format())
          $('#eventDay').val(date.format())
          $('#eventsModal').modal('show')
        },
        eventClick: function(event){
          if(event.id){
            $('#delEventModal').modal('show');
            $('#delEventForm').attr('action', '{{ route("admin.eventos.index") }}/' + event.id);
          }else{
            $('#delEventForm').attr('action', '#');
          }

          $('#delEventForm button[type="submit"]').prop('disabled', !event.id)
        }
      })

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

      $('#delEventModal').on('show.bs.modal', function (e) {
        let url = $(e.relatedTarget).data('url');

        if(url){
          $('#delEventForm').attr('action', url);
        }
        $('#delEventForm button[type="submit"]').prop('disabled', !url)
      })

      $('#statusEventoModal').on('show.bs.modal', function (e) {
        let type = +$(e.relatedTarget).data('type'),
            url = $(e.relatedTarget).data('url');

        title = type == 1 ? 'aprobar' : 'rechazar';

        $('#status-modal-form button[type="submit"]').prop('disabled', !url)
        $('#status-modal-form').attr('action', url)
        $('#status-modal-value').val(type)
        $('#status-modal-label').text(title)
      })
   	});//Ready

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
          $(`#evento-${r.evento.id}`)
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
@endsection
