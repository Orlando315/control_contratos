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
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contrato.index') }}">Contratos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contrato.show', ['contrato' => $empleado->contrato_id]) }}">Contrato</a></li>
        <li class="breadcrumb-item active"><strong>Empleado</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-view')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contrato.show', ['contrato' => $empleado->contrato_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('empleado-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.empleado.edit', ['empleado' => $empleado->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        <a class="btn btn-default btn-sm" href="{{ route('admin.empleado.contrato.create', ['empleado' => $empleado->id]) }}"><i class="fa fa-refresh" aria-hidden="true"></i> Cambio de jornada</a>
        @if(!$empleado->usuario->isEmpresa())
          <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#toggleModal"><i class="fa fa-exchange" aria-hidden="true"></i> Cambiar role</button>
        @endif
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#contratoModal"><i class="fa fa-clipboard" aria-hidden="true"></i> Cambio de contrato</button>
      @endpermission
      <a class="btn btn-default btn-sm" href="{{ route('admin.empleado.print', ['empleado' => $empleado->id]) }}" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</a>
      @permission('empleado-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
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
              <span class="pull-right">
                @permission('contrato-view')
                  <a href="{{ route('admin.contrato.show', ['contrato' => $empleado->contrato_id]) }}">
                    {{ $empleado->contrato->nombre }}
                  </a>
                @else
                  {{ $empleado->contrato->nombre }}
                @endpermission
                </span>
            </li>
            <li class="list-group-item">
              <b>Usuario</b>
              <span class="pull-right">
                @permission('user-view')
                  <a href="{{ route('admin.usuario.show', ['usuario' => $empleado->usuario->id]) }}">
                    {{ $empleado->usuario->usuario }}
                  </a>
                @else
                  {{ $empleado->usuario->usuario }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Roles</b>
              <span class="pull-right">{!! $empleado->usuario->allRolesNames() !!}</span>
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
              <span class="pull-right">{{ $empleado->usuario->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Dirección</b>
              <span class="pull-right">{{ $empleado->direccion }}</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring($empleado->usuario->telefono)</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring($empleado->usuario->email)</span>
            </li>
            <li class="list-group-item">
              <b>Profesión</b>
              <span class="pull-right">@nullablestring($empleado->profesion)</span>
            </li>
            <li class="list-group-item">
              <b>Talla de camisa</b>
              <span class="pull-right">@nullablestring($empleado->talla_camisa)</span>
            </li>
            <li class="list-group-item">
              <b>Talla de zapato</b>
              <span class="pull-right">@nullablestring($empleado->talla_zapato)</span>
            </li>
            <li class="list-group-item">
              <b>Talla de pantalon</b>
              <span class="pull-right">@nullablestring($empleado->talla_pantalon)</span>
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
                @permission('empleado-edit')
                  <a class="btn btn-default btn-xs" href="{{ route('admin.empleado.contrato.edit', ['empleado' => $empleado->id]) }}" title="Editar contrato"><i class="fa fa-pencil"></i></a>
                @endpermission
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
                  <span class="pull-right">{{ $empleado->lastContrato->sueldo() }}</span>
                </li>
                <li class="list-group-item">
                  <b>Inicio</b>
                  <span class="pull-right">{{ $empleado->lastContrato->inicio }}</span>
                </li>
                <li class="list-group-item">
                  <b>Inicio de Jornada</b>
                  <span class="pull-right">{{$empleado->lastContrato->inicio_jornada}}</span>
                </li>
                <li class="list-group-item">
                  <b>Fin</b>
                  <span class="pull-right">{!! $empleado->lastContrato->fin ?? '<span class="text-muted">Indefinido</span>' !!}</span>
                </li>
                <li class="list-group-item">
                  <b>Descripción</b>
                  <span class="pull-right">@nullablestring($empleado->lastContrato->descripcion)</span>
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
                  <span class="pull-right">{{ $empleado->banco->cuenta }}</span>
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
                  <span class="pull-right">@nullablestring($empleado->nombre_emergencia)</span>
                </li>
                <li class="list-group-item">
                  <b>Teléfono</b>
                  <span class="pull-right">@nullablestring($empleado->telefono_emergencia)</span>
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
                                  <a href="{{ $requisito->isFile() ? route('admin.documento.download', ['documento' => $requisito->documento->id]) : route('admin.carpeta.show', ['carpeta' => $requisito->documento->id]) }}">
                                    {!! $requisito->icon() !!} {{ $requisito->nombre }}
                                    @if($requisito->documento->vencimiento)
                                      <small class="text-muted">- {{ $requisito->documento->vencimiento }}</small>
                                    @endif
                                  </a>
                                @else
                                  {!! $requisito->icon() !!} {{ $requisito->nombre }}
                                @endif
                              </div>
                              <div class="col-3">
                                @permission('empleado-edit')
                                  <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                    <ul class="dropdown-menu" x-placement="bottom-start">
                                      @if($requisito->documento)
                                        @if($requisito->isFile())
                                          @if($requisito->documento->isPdf())
                                            <li>
                                              <a title="Ver PDF" href="#" data-toggle="modal" data-target="#pdfModal" data-url="{{ $requisito->documento->asset_url }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i> Ver PDF
                                              </a>
                                            </li>
                                          @endif
                                          <li><a class="dropdown-item" href="{{ route('admin.documento.edit', ['documento' => $requisito->documento->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                          <li><a class="dropdown-item text-danger" type="button" title="Eliminar requisito" data-url="{{ route('admin.documento.destroy', ['documento' => $requisito->documento->id]) }}" data-toggle="modal" data-target="#delFileModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</a></li>
                                        @else
                                          <li><a class="dropdown-item" href="{{ route('admin.carpeta.edit', ['carpeta' => $requisito->documento->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                        @endif
                                      @else
                                        <li><a class="dropdown-item" href="{{ $requisito->isFile() ? route('admin.documento.create', ['type' => 'empleados', 'id' => $empleado->id, 'carpeta' => null, 'requisito' => $requisito->id]) : route('admin.carpeta.create', ['type' => 'empleados', 'id' => $empleado->id, 'requisito' => $requisito->id]) }}"><i class="fa fa-plus"></i> Agregar</a></li>
                                      @endif
                                    </ul>
                                  </div>
                                @endpermission
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
                  @permission('empleado-edit')
                    @if($empleado->documentos()->count() < 10)
                      <div class="mb-3 text-right">
                        <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => 'empleados', 'id' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.documento.create', ['type' => 'empleados', 'id' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                      </div>
                    @endif
                  @endpermission

                  <div class="row icons-box icons-folder">
                    @foreach($empleado->carpetas()->main()->get() as $carpeta)
                      <div class="col-md-3 col-xs-4 infont mb-3">
                        <a href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}">
                          @if($carpeta->isRequisito() || $carpeta->isTypeEmpleado())
                            <span class="pull-left text-muted">
                              @if($carpeta->isRequisito())
                                <i class="fa fa-asterisk" aria-hidden="true" title="Requisito" style="font-size: 12px"></i>
                              @endif
                              @if($carpeta->isTypeEmpleado() && $carpeta->isVisible())
                                <i class="fa fa-eye" aria-hidden="true" title="Visible para el Empleado" style="font-size: 12px"></i>
                              @endif
                            </span>
                          @endif
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
                  @permission('plantilla-documento-create')
                    <div class="mb-3 text-right">
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.create', ['contrato' => $empleado->contrato_id, 'empleado' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo documento</a>
                   </div>
                  @endpermission

                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Documento</th>
                        <th>Padre</th>
                        <th>Caducidad</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($empleado->plantillaDocumentos as $plantillaDocumento)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>@nullablestring($plantillaDocumento->nombre)</td>
                          <td>@nullablestring(optional($plantillaDocumento->padre)->nombre)</td>
                          <td class="text-center">@nullablestring(optional($plantillaDocumento->caducidad)->format('d-m-Y'))</td>
                          <td class="text-center">
                            @permission('plantilla-documento-view|plantilla-documento-edit')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('plantilla-documento-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.plantilla.documento.show', ['documento' => $plantillaDocumento->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('plantilla-documento-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.plantilla.documento.edit', ['documento' => $plantillaDocumento->id]) }}">
                                        <i class="fa fa-pencil"></i> Editar
                                      </a>
                                    </li>
                                  @endpermission
                                </ul>
                              </div>
                            @endpermission
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
                      <tr class="text-center">
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Estatus</th>
                        <th>Adjunto</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($empleado->solicitudes as $solicitud)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td class="text-center">{{ $solicitud->tipo() }}</td>
                          <td>@nullablestring($solicitud->descripcion)</td>
                          <td class="text-center">{!! $solicitud->status() !!}</td>
                          <td class="text-center">
                            @if($solicitud->adjunto)
                              <a href="{{ $solicitud->download }}" title="Descargar adjunto">Descargar</a>
                            @else
                              @nullablestring(null)
                            @endif
                          </td>
                          <td class="text-center">
                            @permission('solicitud-view')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('solicitud-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.solicitud.show', ['solicitud' => $solicitud->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                </ul>
                              </div>
                            @endpermission
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
          @role('developer|superadmin|empresa')
            <li><a class="nav-link" href="#tab-5" data-toggle="tab"><i class="fa fa-heartbeat"></i> Covid-19</a></li>
          @endrole
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
                  @foreach($empleado->sueldos as $sueldo)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $sueldo->created_at }}</td>
                      <td>{{ $sueldo->alcanceLiquido() }}</td>
                      <td>{{ $sueldo->sueldoLiquido() }}</td>
                      <td>
                        @permission('sueldo-view')
                          <a class="btn btn-success btn-xs" href="{{ route('admin.sueldo.show', ['sueldo' => $sueldo->id] )}}"><i class="fa fa-search"></i></a>
                        @endpermission
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
                    <th class="text-center">Serie</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Anticipo</th>
                    <th class="text-center">Bono</th>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->anticipos as $anticipo)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>@nullablestring($anticipo->serie)</td>
                      <td>{{ $anticipo->fecha }}</td>
                      <td class="text-right">{{ $anticipo->anticipo() }}</td>
                      <td class="text-right">{{ $anticipo->bono() }}</td>
                      <td><small>{!! $anticipo->status() !!}</small></td>
                      <td>
                        @permission('anticipo-view')
                          <a class="btn btn-success btn-xs" href="{{ route('admin.anticipo.show', ['anticipo' => $anticipo->id]) }}"><i class="fa fa-search"></i></a>
                        @endpermission
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
                  @foreach($empleado->reemplazos as $reemplazo)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $reemplazo->inicio }}</td>
                      <td>{!! $reemplazo->nombreReemplazo() !!}</td>
                      <td>{{ $reemplazo->valor() }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @permission('covid19-index')
            <div class="tab-pane" id="tab-5">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>¿Tiene respuestas<br>positivas?</th>
                      <th>Fecha</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($empleado->usuario->covid19Respuestas as $respuesta)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center"><small>{!! $respuesta->positiveAnswers() !!}</small></td>
                        <td class="text-center">{{ $respuesta->created_at->format('d-m-Y H:i:s') }}</td>
                        <td class="text-center">
                          @permission('covid19-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.covid19.show', ['respuesta' => $respuesta->id] )}}"><i class="fa fa-search"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
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
                      <td>@nullablestring($evento->fin)</td>
                      <td>{!! $evento->status() !!}</td>
                      <td>{{ optional($evento->created_at)->format('d-m-Y H:i:s') }}</td>
                      <td>
                        @permission('empleado-edit')
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              @if($evento->isPendiente())
                                <li><a class="dropdown-item" type="button" data-url="{{ route('admin.evento.status', ['evento' => $evento->id] ) }}" data-type="1" data-toggle="modal" data-target="#statusEventoModal"><i class="fa fa-check"></i> Aprobar</a></li>
                                <li><a class="dropdown-item" type="button" data-url="{{ route('admin.evento.status', ['evento' => $evento->id] ) }}" data-type="0" data-toggle="modal" data-target="#statusEventoModal"><i class="fa fa-ban"></i> Rechazar</a></li>
                              @endif
                              <li><a class="dropdown-item" type="button" data-toggle="modal" data-target="#delEventModal" data-url="{{ route('admin.evento.destroy', ['evento' => $evento->id]) }}"><i class="fa fa-times"></i> Eliminar</a></li>
                            </ul>
                          </div>
                        @endpermission
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
  
  @if(!$empleado->usuario->isEmpresa())
    <div id="toggleModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="toggleModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.empleado.changeRole', ['empleado' => $empleado->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="toggleModalLabel">Cambiar de role</h4>
            </div>
            <div class="modal-body">
              <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                <p class="text-center">¿Desea asignar un Role adicional a este Empleado?</p>
                <label>Seleccionar role:</label>
                @if(Auth::user()->isAdmin())
                  <div class="custom-control custom-radio m-0">
                    <input id="role-admin" class="custom-control-input" type="radio" name="role" value="administrador"{{ $empleado->usuario->hasActiveOrInactiveRole('administrador') ? ' checked' : '' }}>
                    <label for="role-admin" class="custom-control-label">Administrador</label>
                  </div>
                @endif
                <div class="custom-control custom-radio">
                  <input id="role-supervisor" class="custom-control-input" type="radio" name="role" value="supervisor"{{ $empleado->usuario->hasActiveOrInactiveRole('supervisor') ? ' checked' : '' }}>
                  <label for="role-supervisor" class="custom-control-label">Supervisor</label>
                </div>
                <div class="custom-control custom-radio">
                  <input id="role-empleado" class="custom-control-input" type="radio" name="role" value="empleado"{{ $empleado->usuario->hasActiveOrInactiveRole('administrador|supervisor') ? '' : ' checked' }}>
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

  @permission('empleado-edit')
    <div id="contratoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="contratoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.empleado.contrato.cambio', ['empleado' => $empleado->id]) }}" method="POST">
            @method('PATCH')
            @csrf

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
                      <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
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

    <div id="delFileModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delete-file-form" action="#" method="POST">
            @method('DELETE')
            @csrf

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

    <div id="delEventModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delEventModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delEventForm" action="#" method="POST">
            @method('DELETE')
            @csrf

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

    <div id="eventsModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="eventsModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="eventForm" action="{{ route('admin.evento.store', ['empleado' => $empleado->id]) }}" method="POST">
            <input id="eventDay" type="hidden" name="inicio" value="">
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="eventsModalLabel">Agregar evento</h4>
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
                  @foreach($empleados as $otherEmpleado)
                    <option value="{{ $otherEmpleado->id }}">{{ $otherEmpleado->usuario->rut }} | {{ $otherEmpleado->usuario->nombre() }}</option>
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

    <div id="statusEventoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusEventoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="status-modal-form" action="#" method="POST">
            <input id="status-modal-value" type="hidden" name="status">
            @method('PUT')
            @csrf

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
  @endpermission

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
                <div class="ibox">
                  <div class="ibox-content no-padding">
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
                        <span class="pull-right">{{ $contrato->sueldo() }}</span>
                      </li>
                      <li class="list-group-item">
                        <b>Inicio</b>
                        <span class="pull-right">{{ $contrato->inicio }}</span>
                      </li>
                      <li class="list-group-item">
                        <b>Inicio de Jornada</b>
                        <span class="pull-right">{{ $contrato->inicio_jornada }}</span>
                      </li>
                      <li class="list-group-item">
                        <b>Fin</b>
                        <span class="pull-right">{!! $contrato->fin ?? '<span class="text-muted">Indefinido</span>' !!}</span>
                      </li>
                      <li class="list-group-item">
                        <b>Descripción</b>
                        <span class="pull-right">@nullablestring($contrato->descripcion)</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  @permission('empleado-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.empleado.destroy', ['empleado' => $empleado->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar empleado</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Empleado?</h4>
              @if($empleado->usuario->isAdmin())
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
  @endpermission

  <div id="exportModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.empleado.export', ['empleado' => $empleado->id]) }}" method="POST">
          @csrf

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
  @include('partials.preview-pdf')
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
      @permission('empleado-edit')
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

        $('#contrato').select2({
          dropdownParent: $('#contrato-modal-body'),
          theme: 'bootstrap4',
          placeholder: 'Seleccione...',
        });

        $('#reemplazo, #tipo').select2({
          dropdownParent: $('#events-modal-body'),
          theme: 'bootstrap4',
          placeholder: 'Seleccione...',
        });

        $('#reemplazo').trigger('change');
        $('#eventForm').submit(storeEvent);
        $('#delEventForm').submit(delEvent);

        $('#tipo').change(function(){
          let tipo = $(this).val();
          let isReemplazo = tipo == 9;
          let isDespidoRenuncia = (tipo == 6 || tipo == 7);

          $('#fin')
            .closest('.form-group')
            .attr('hidden', (isReemplazo || isDespidoRenuncia));

          $('#reemplazo, #valor')
            .prop('required', isReemplazo)
            .closest('.form-group')
            .attr('hidden', !isReemplazo);
        });

        $('#delEventModal').on('show.bs.modal', function (e) {
          let url = $(e.relatedTarget).data('url');

          if(url){
            $('#delEventForm').attr('action', url);
          }
          $('#delEventForm button[type="submit"]').prop('disabled', !url);
        });

        $('#statusEventoModal').on('show.bs.modal', function (e) {
          let type = +$(e.relatedTarget).data('type'),
              url = $(e.relatedTarget).data('url');

          title = type == 1 ? 'aprobar' : 'rechazar';

          $('#status-modal-form button[type="submit"]').prop('disabled', !url);
          $('#status-modal-form').attr('action', url);
          $('#status-modal-value').val(type);
          $('#status-modal-label').text(title);
        });
      @endpermission

      $('#inicioExport, #finExport').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      }).on('changeDate', function(e){
        let inicio = new Date($('#inicioExport').val()),
            fin = new Date($('#finExport').val());

        if(inicio > fin){
          inicio.setDate(inicio.getDate() + 1);
          let newDate = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate();
          $('#finExport').datepicker('setDate', newDate);
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
          $('#eventTitle').text(date.format());
          $('#eventDay').val(date.format());
          @permission('empleado-edit')
            $('#eventsModal').modal('show');
          @endpermission
        },
        eventClick: function(event){
          @permission('empleado-edit')
            if(event.id){
              $('#delEventModal').modal('show');
              $('#delEventForm').attr('action', '{{ route("admin.evento.index") }}/' + event.id);
            }else{
              $('#delEventForm').attr('action', '#');
            }

            $('#delEventForm button[type="submit"]').prop('disabled', !event.id);
          @endpermission
        }
      });
   	});//Ready
    
    @permission('empleado-edit')
      function storeEvent(e){
        e.preventDefault();

        let form = $(this),
            action = form.attr('action'),
            alert  = $('#eventsModal .alert'),
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
              location.reload();
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
            form[0].reset();
            $('#eventsModal').modal('hide');
          }else{
            alert.show().delay(7000).hide('slow');
            alert.find('strong').text(r.message || 'Ha ocurrido un error.');
          }
        })
        .fail(function(){
          alert.show().delay(7000).hide('slow');
          alert.find('strong').text('Ha ocurrido un error');
        })
        .always(function(){
          button.prop('disabled', false);
        });
      }

      function delEvent(e){
        e.preventDefault();

        let form = $(this),
            action = form.attr('action'),
            alert  = form.find('.alert'),
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
            $(`#evento-${r.evento.id}`);
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
        });
      }
    @endpermission
 	</script>
@endsection
