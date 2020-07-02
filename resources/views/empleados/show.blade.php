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
        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">Contratos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.show', ['contrato' => $empleado->contrato_id]) }}">Contrato</a></li>
        <li class="breadcrumb-item active"><strong>Empleado</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('contratos.show', ['contrato' => $empleado->contrato_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('empleados.edit', [$empleado->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <a class="btn btn-default btn-sm" href="{{ route('empleados.cambio', [$empleado->id]) }}"><i class="fa fa-refresh" aria-hidden="true"></i> Cambio de jornada</a>
      <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#toggleModal"><i class="fa fa-exchange" aria-hidden="true"></i> {{ $empleado->usuario->tipo == 3 ? 'Volver Empleado' : 'Ascender a Supervisor' }} </button>
      <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#contratoModal"><i class="fa fa-refresh" aria-hidden="true"></i> Cambio de contrato</button>
      
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
              <span class="pull-right"><a href="{{ route('contratos.show', ['contrato' => $empleado->contrato_id]) }}">{{ $empleado->contrato->nombre }}</a></span>
            </li>
            <li class="list-group-item">
              <b>Usuario</b>
              <span class="pull-right">
                <a href="{{ route('usuarios.show', ['usuario' => $empleado->usuario->id]) }}">
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
                <button class="btn btn-default btn-xs" titl="Ver historial" data-toggle="modal" data-target="#historyModal"><i class="fa fa-list"></i></button>
              </div>
            </div>
            <div class="ibox-content no-padding">
              <ul class="list-group">
                <li class="list-group-item">
                  <b>Jornada</b>
                  <span class="pull-right">{{ $empleado->contratos->last()->jornada }}</span>
                </li>
                <li class="list-group-item">
                  <b>Sueldo</b>
                  <span class="pull-right">{{ number_format($empleado->contratos->last()->sueldo, 0, ',', '.') }}</span>
                </li>
                <li class="list-group-item">
                  <b>Inicio</b>
                  <span class="pull-right">{{ $empleado->contratos->last()->inicio }}</span>
                </li>
                <li class="list-group-item">
                  <b>Inicio de Jornada</b>
                  <span class="pull-right"> {{$empleado->contratos->last()->inicio_jornada}} </span>
                </li>
                <li class="list-group-item">
                  <b>Fin</b>
                  <span class="pull-right"> {!! $empleado->contratos->last()->fin ?? '<span class="text-muted">Indefinido</span>' !!} </span>
                </li>
                <li class="list-group-item">
                  <b>Descripción</b>
                  <span class="pull-right"> {!! $empleado->contratos->last()->descripcion ?? 'N/A' !!} </span>
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
              <li><a class="nav-link active" href="#tab-11" data-toggle="tab"><i class="fa fa-paperclip"></i> Adjuntos</a></li>
              <li><a class="nav-link" href="#tab-12" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documentos</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab-11">
                <div class="panel-body">
                  <div class="mb-3">
                    <a class="btn btn-warning btn-sm" href="{{ route('carpeta.create', ['type' => 'empleados', 'id' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                    @if($empleado->documentos->count() < 10)
                      <a class="btn btn-primary btn-sm" href="{{ route('documentos.create.empleados', ['id' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                    @endif
                  </div>
                  <div class="row icons-box icons-folder">
                    @foreach($empleado->carpetas()->main()->get() as $carpeta)
                      <div class="col-md-3 col-xs-4 infont mb-3">
                        <a href="{{ route('carpeta.show', ['carpeta' => $carpeta->id]) }}">
                          <i class="fa fa-folder" aria-hidden="true"></i>
                          <p class="m-0">{{ $carpeta->nombre }}</p>
                        </a>
                      </div>
                    @endforeach
                  </div>

                  <hr class="hr-line-dashed">

                  <div class="row">
                    @forelse($empleado->documentos()->main()->get() as $documento)
                      <div id="file-{{ $documento->id }}" class="col-md-3 col-sm-4 col-xs-6 mb-3">
                        <div class="file m-0 file-options">
                          <div class="float-right dropdown">
                            <button data-toggle="dropdown" class="dropdown-toggle btn-white" aria-expanded="false"></button>
                            <ul class="dropdown-menu m-t-xs" x-placement="bottom-start" style="position: absolute; top: 21px; left: 0px; will-change: top, left;">
                              <li>
                                <a title="Editar documento" href="{{ route('documentos.edit', ['documento' => $documento->id]) }}">
                                  <i class="fa fa-pencil" aria-hidden="true"></i> Editar
                                </a>
                              </li>
                              <li>
                                <a class="btn-delete-file" type="button" title="Eliminar archivo" data-file="{{ $documento->id }}" data-toggle="modal" data-target="#delFileModal">
                                  <i class="fa fa-times" aria-hidden="true"></i> Eliminar
                                </a>
                              </li>
                            </ul>
                          </div>
                          <a href="{{ route('documentos.download', ['documento' => $documento->id]) }}">
                            <span class="corner"></span>

                            <div class="icon">
                              <i class="fa {{ $documento->getIconByMime() }}"></i>
                            </div>
                            <div class="file-name">
                              {{ $documento->nombre }}
                              @if($documento->vencimiento)
                                <br>
                                <small><strong>Vencimiento:</strong> {{ $documento->vencimiento }}</small>
                              @endif
                            </div>
                          </a>
                        </div>
                      </div>
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
                    <a class="btn btn-primary btn-sm" href="{{ route('plantilla.documento.create', ['contrato' => $empleado->contrato_id, 'empleado' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo documento</a>
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
                            <a class="btn btn-success btn-xs" href="{{ route('plantilla.documento.show', ['documento' => $d->id] )}}"><i class="fa fa-search"></i></a>
                            <a class="btn btn-primary btn-xs" href="{{ route('plantilla.documento.edit', ['documento' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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

  <div class="row mb-3">
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
                        <a class="btn btn-success btn-xs" href="{{ route('sueldos.show', ['sueldo' => $d->id] )}}"><i class="fa fa-search"></i></a>
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
                        <a class="btn btn-success btn-xs" href="{{ route('anticipos.show', ['anticipo' => $d->id] )}}"><i class="fa fa-search"></i></a>
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
                      <td><a href="{{ route('inventarios.show', ['inventario' => $d->inventario_id]) }}">{{ $d->inventario->nombre }}</a></td>
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
      <div class="ibox">
        <div class="ibox-title">
          <h5>Calendario</h5>
          <div class="ibox-tools">
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exportModal"><i class="fa fa-file-excel-o"></i> Exportar a excel</button>
          </div>
        </div>
        <div class="ibox-content">
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>

  <div id="toggleModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="toggleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('empleados.toggleTipo', ['empleado' => $empleado->id]) }}" method="POST">
          {{ method_field('PATCH') }}
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="toggleModalLabel">Cambiar de nivel</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de cambiar a {{ $empleado->usuario->tipo == 3 ? 'Empleado' : 'Supervisor' }}?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-success btn-sm" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="contratoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="contratoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('empleados.cambioContrato', ['empleado' => $empleado->id]) }}" method="POST">
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
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
          </button>
          <h4 class="modal-title" id="historyModalLabel">Historial de contratos</h4>
        </div>
        <div class="modal-body">
          @foreach($empleado->contratos()->get() as $contrato)
            <ul class="list-group">
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
                <span class="pull-right"> {{ $contrato->descripcion ?? 'N/A' }}
              </li>
            </ul>
          @endforeach
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
          <form action="{{ route('empleados.destroy', ['empleado' => $empleado->id]) }}" method="POST">
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
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="eventsModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="eventForm" action="{{ route('eventos.store', ['empleado' => $empleado->id]) }}" method="POST">
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
            <button class="btn btn-primary btn-sm" type="submit">Gardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="exportModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('empleados.export', ['empleado' => $empleado->id]) }}" method="POST">
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
    let jornada = @json($empleado->proyectarJornada()),
        eventos = @json($empleado->getEventos()),
        feriados = @json($empleado->getFeriados());
   	
    $(document).ready(function(){
      $('#delFileModal').on('show.bs.modal', function(e){
        let button = $(e.relatedTarget),
            file   = button.data('file'),
            action = '{{ route("documentos.index") }}/' + file;

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

      $('#delete-file-form').submit(deleteFile);
      $('#eventForm').submit(storeEvent)
      $('#delEventForm').submit(delEvent)

      $('#calendar').fullCalendar({
        locale: 'es',
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
            $('#delEventForm').attr('action', '{{ route("eventos.index") }}/' + event.id);
          }else{
            $('#delEventForm').attr('action', '#');
          }
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
   	});//Ready

    function deleteFile(e){
      e.preventDefault();

      var form = $(this),
          action = form.attr('action');

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){
          $('#file-' + r.id).remove();
          $('#delFileModal').modal('hide');
        }else{
          $('.alert').show().delay(7000).hide('slow');
        }
      })
      .fail(function(){
        $('.alert').show().delay(7000).hide('slow');
      })
    }

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
