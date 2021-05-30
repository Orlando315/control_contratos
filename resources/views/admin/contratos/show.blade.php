@extends('layouts.app')

@section('title', 'Contrato')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contrato.index') }}">Contratos</a></li>
        <li class="breadcrumb-item active"><strong>Contrato</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contrato.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('contrato-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contrato.edit', ['contrato' => $contrato->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('contrato-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>
  
  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $contrato->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Inicio</b>
              <span class="pull-right">{{ $contrato->inicio }}</span>
            </li>
            <li class="list-group-item">
              <b>Fin</b>
              <span class="pull-right">{{ $contrato->fin }}</span>
            </li>
            <li class="list-group-item">
              <b>Valor</b>
              <span class="pull-right">{{ $contrato->valor() }}</span>
            </li>
            <li class="list-group-item">
              <b>Faena</b>
              <span class="pull-right">
                @if($contrato->faena_id)
                  @permission('faena-view')
                    <a href="{{ route('admin.faena.show', ['faena' => $contrato->faena_id]) }}">
                      {{ $contrato->faena->nombre }}
                    </a>
                  @else
                    {{ $contrato->faena->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($contrato->descripcion)</span>
            </li>
            <li class="list-group-item">
              <b>Principal</b>
              <span class="pull-right"> {!! $contrato->principal() !!}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="tabs-container">
        <div class="collapsable-tabs">
          <ul class="nav nav-tabs">
            @permission('requisito-index')
              <li><a class="nav-link active" href="#tab-13" data-toggle="tab"><i class="fa fa-asterisk"></i> Requisitos</a></li>
            @endpermission
            <li><a class="nav-link{{ !Auth::user()->hasPermission('requisito-index') ? ' active' : '' }}" href="#tab-11" data-toggle="tab"><i class="fa fa-paperclip"></i> Adjuntos</a></li>
            <li><a class="nav-link" href="#tab-12" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documentos</a></li>
            @permission('partida-index')
              <li><a class="nav-link" href="#tab-14" data-toggle="tab"><i class="fa fa-ellipsis-v"></i> Partidas</a></li>
            @endpermission
          </ul>
          <div class="ibox-tools">
            <a class="collapse-link" href="#" data-toggle="collapse" data-target="#panels-tab-1" aria-expanded="true">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div id="panels-tab-1" class="tab-content collapse show">
          @permission('requisito-index')
            <div class="tab-pane active" id="tab-13">
              <div class="panel-body">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="ibox m-2 m-lg-0">
                      <div class="ibox-title">
                        <h5>Contrato</h5>
                        <div class="ibox-tools">
                          <a class="collapse-link" href="#">
                            <i class="fa fa-chevron-up"></i>
                          </a>
                          @permission('requisito-create')
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                              <i class="fa fa-cogs"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user" x-placement="bottom-start">
                              <li><a href="{{ route('admin.requisito.create', ['contrato' => $contrato->id, 'type' => 'contratos']) }}" class="dropdown-item"><i class="fa fa-plus"></i> Agregar</a></li>
                            </ul>
                          @endpermission
                        </div>
                      </div>
                      @forelse($contrato->requisitosWithDocumentos() as $requisitoContrato)
                        <div class="ibox-content p-2">
                          <div class="row">
                            <div class="col-9">
                              <i class="fa {{ $requisitoContrato->documento ? 'fa-check-square text-primary' : 'fa-square-o text-muted' }}"></i>
                              @if($requisitoContrato->documento)
                                <a href="{{ $requisitoContrato->isFile() ? route('admin.documento.download', ['documento' => $requisitoContrato->documento->id]) : route('admin.carpeta.show', ['carpeta' => $requisitoContrato->documento->id]) }}">
                                  {!! $requisitoContrato->icon() !!} {{ $requisitoContrato->nombre }}
                                  @if($requisitoContrato->documento->vencimiento)
                                    <small class="text-muted">- {{ $requisitoContrato->documento->vencimiento }}</small>
                                  @endif
                                </a>
                              @else
                                {!! $requisitoContrato->icon() !!} {{ $requisitoContrato->nombre }}
                              @endif
                            </div>
                            <div class="col-3">
                              @permission('requisito-edit|requisito-delete')
                                <div class="btn-group">
                                  <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                  <ul class="dropdown-menu" x-placement="bottom-start">
                                    @permission('requisito-edit')
                                      <li><a class="dropdown-item" href="{{ route('admin.requisito.edit', ['requisito' => $requisitoContrato->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                    @endpermission
                                    @permission('requisito-delete')
                                      <li><a class="dropdown-item text-danger" type="button" data-toggle="modal" data-type="requisito" data-target="#delFileModal" data-url="{{ route('admin.requisito.destroy', ['requisito' => $requisitoContrato->id]) }}"><i class="fa fa-times"></i> Eliminar</a></li>
                                    @endpermission
                                  </ul>
                                </div>
                              @endpermission
                            </div>
                          </div>
                        </div>
                      @empty
                        <div class="ibox-content p-2">
                          <p class="text-muted text-center mb-1">No hay requisitos</p>
                        </div>
                      @endforelse
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="ibox m-2 m-lg-0">
                      <div class="ibox-title">
                        <h5>Empleados</h5>
                        <div class="ibox-tools">
                          <a class="collapse-link" href="#">
                            <i class="fa fa-chevron-up"></i>
                          </a>
                          @permission('requisito-create')
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                              <i class="fa fa-cogs"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user" x-placement="bottom-start">
                              <li><a href="{{ route('admin.requisito.create', ['contrato' => $contrato->id, 'type' => 'empleados']) }}" class="dropdown-item"><i class="fa fa-plus"></i> Agregar</a></li>
                            </ul>
                          @endpermission
                        </div>
                      </div>
                      @forelse($contrato->requisitos()->ofType('empleados')->get() as $requisitoEmpleado)
                        <div class="ibox-content p-2">
                          <div class="row">
                            <div class="col-9">
                              {!! $requisitoEmpleado->icon() !!} {{ $requisitoEmpleado->nombre }}
                            </div>
                            <div class="col-3">
                              @permission('requisito-edit|requisito-delete')
                                <div class="btn-group">
                                  <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                  <ul class="dropdown-menu" x-placement="bottom-start">
                                    @permission('requisito-edit')
                                      <li><a class="dropdown-item" href="{{ route('admin.requisito.edit', ['requisito' => $requisitoEmpleado->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                    @endpermission
                                    @permission('requisito-delete')
                                      <li><a class="dropdown-item text-danger" button="type" data-toggle="modal" data-type="requisito" data-target="#delFileModal" data-url="{{ route('admin.requisito.destroy', ['requisito' => $requisitoEmpleado->id]) }}"><i class="fa fa-times"></i> Eliminar</a></li>
                                    @endpermission
                                  </ul>
                                </div>
                              @endpermission
                            </div>
                          </div>
                        </div>
                      @empty
                        <div class="ibox-content p-2">
                          <p class="text-muted text-center mb-1">No hay requisitos</p>
                        </div>
                      @endforelse
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="ibox m-2 m-lg-0">
                      <div class="ibox-title">
                        <h5>Transportes</h5>
                        <div class="ibox-tools">
                          <a class="collapse-link" href="#">
                            <i class="fa fa-chevron-up"></i>
                          </a>
                          @permission('requisito-create')
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                              <i class="fa fa-cogs"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user" x-placement="bottom-start">
                              <li><a href="{{ route('admin.requisito.create', ['contrato' => $contrato->id, 'type' => 'transportes']) }}" class="dropdown-item"><i class="fa fa-plus"></i> Agregar</a></li>
                            </ul>
                          @endpermission
                        </div>
                      </div>
                      @forelse($contrato->requisitos()->ofType('transportes')->get() as $requisitoTransporte)
                        <div class="ibox-content p-2">
                          <div class="row">
                            <div class="col-9">
                              {!! $requisitoTransporte->icon() !!} {{ $requisitoTransporte->nombre }}
                            </div>
                            <div class="col-3">
                              @permission('requisito-edit|requisito-delete')
                                <div class="btn-group">
                                  <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                  <ul class="dropdown-menu" x-placement="bottom-start">
                                    @permission('requisito-edit')
                                      <li><a class="dropdown-item" href="{{ route('admin.requisito.edit', ['requisito' => $requisitoTransporte->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                    @endpermission
                                    @permission('requisito-delete')
                                      <li><a class="dropdown-item text-danger" button="type" data-toggle="modal" data-type="requisito" data-target="#delFileModal" data-url="{{ route('admin.requisito.destroy', ['requisito' => $requisitoTransporte->id]) }}"><i class="fa fa-times"></i> Eliminar</a></li>
                                    @endpermission
                                  </ul>
                                </div>
                              @endpermission
                            </div>
                          </div>
                        </div>
                      @empty
                        <div class="ibox-content p-2">
                          <p class="text-muted text-center mb-1">No hay requisitos</p>
                        </div>
                      @endforelse
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endpermission
          <div class="tab-pane{{ !Auth::user()->hasPermission('requisito-index') ? ' active' : '' }}" id="tab-11">
            <div class="panel-body">
              @if($contrato->documentos()->count() < 10 && Auth::user()->hasPermission('contrato-edit'))
                <div class="mb-3 text-right">
                  <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => 'contratos', 'id' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.documento.create', ['type' => 'contratos', 'id' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                </div>
              @endif
              <div class="row icons-box icons-folder">
                @foreach($contrato->carpetas()->main()->get() as $carpeta)
                  <div class="col-md-3 col-xs-4 infont mb-3">
                    <a href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}">
                      @if($carpeta->isRequisito())
                        <span class="pull-left text-muted" title="Requisito"><i class="fa fa-asterisk" aria-hidden="true" style="font-size: 12px"></i></span>
                      @endif
                      <i class="fa fa-folder" aria-hidden="true"></i>
                      <p class="m-0">{{ $carpeta->nombre }}</p>
                    </a>
                  </div>
                @endforeach
              </div>
              <hr class="hr-line-dashed">
              <div class="row">
                @forelse($contrato->documentos()->main()->get() as $documento)
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
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Documento</a>
                </div>
              @endpermission
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
                  @foreach($contrato->plantillaDocumentos as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>@nullablestring($d->nombre)</td>
                      <td>{{ $d->empleado->nombre() }}</td>
                      <td>@nullablestring(optional($d->padre)->nombre)</td>
                      <td>
                        @permission('plantilla-documento-view')
                          <a class="btn btn-success btn-xs" href="{{ route('admin.plantilla.documento.show', ['documento' => $d->id] )}}"><i class="fa fa-search"></i></a>
                        @endpermission
                        @permission('plantilla-documento-edit')
                          <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.edit', ['documento' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                        @endpermission
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @permission('partida-index')
            <div class="tab-pane" id="tab-14">
              <div class="panel-body">
                @permission('partida-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.partida.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Partida</a>
                  </div>
                @endpermission

                <div class="row mb-3">
                  <div class="col">
                    <canvas id="partidaTipoChart" width="200" height="100"></canvas>
                  </div>
                </div>

                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Tipo</th>
                      <th>Partidas</th>
                      <th>Total</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($partidasTipos as $tipo)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tipo->tipo() }}</td>
                        <td class="text-right">{{ $tipo->count }}</td>
                        <td class="text-right">{{ $tipo->monto() }}</td>
                        <td class="text-center">
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              <li>
                                <a class="dropdown-item" href="{{ route('admin.partida.tipo', ['contrato' => $contrato->id, 'tipo' => $tipo->tipo]) }}">
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
          @endpermission
        </div>
      </div>
    </div>    
  </div>
  
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <div class="collapsable-tabs">
          <ul class="nav nav-tabs">
            @permission('empleado-index')
              <li><a class="nav-link active" href="#tab-21" data-toggle="tab"><i class="fa fa-address-card"></i> Empleados</a></li>
            @endpermission
            @permission('transporte-index')
              <li><a class="nav-link{{ !Auth::user()->hasPermission('empleado-index') ? ' active' : '' }}" href="#tab-22" data-toggle="tab"><i class="fa fa-car"></i> Transportes</a></li>
            @endpermission
            @permission('inventario-egreso-index')
              <li><a class="nav-link" href="#tab-24" data-toggle="tab"><i class="fa fa-long-arrow-up"></i> Egresos (Inventarios V2)</a></li>
            @endpermission
            @permission('requerimiento-material-index')
              <li><a class="nav-link" href="#tab-25" data-toggle="tab"><i class="fa fa-list-ul"></i> Requerimiento de Materiales</a></li>
            @endpermission
          </ul>
          <div class="ibox-tools">
            <a class="collapse-link" href="#" data-toggle="collapse" data-target="#panels-tab-2" aria-expanded="true">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div id="panels-tab-2" class="tab-content collapse show">
          @permission('empleado-index')
            <div class="tab-pane active" id="tab-21">
              <div class="panel-body">
                <div class="mb-3">
                  @permission('sueldo-index')
                    <a class="btn btn-default btn-xs" href="{{ route('admin.sueldo.index', ['contrato' => $contrato->id]) }}"><i class="fa fa-money" aria-hidden="true"></i> Ver sueldos</a>
                  @endpermission
                  <a class="btn btn-default btn-xs" href="{{ route('admin.contrato.calendar', ['contrato' => $contrato->id]) }}"><i class="fa fa-calendar" aria-hidden="true"></i> Ver calendario</a>
                  @permission('empleado-create')
                    <div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo empleado</button>
                      <ul class="dropdown-menu" x-placement="bottom-start">
                        <li><a class="dropdown-item" href="{{ route('admin.empleado.create', ['contrato' => $contrato->id]) }}">Nuevo Empleado</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.empleado.import.create', ['contrato' => $contrato->id]) }}">Importar Empleados</a></li>
                      </ul>
                    </div>
                  @endpermission
                </div>
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombres</th>
                      <th class="text-center">Apellidos</th>
                      <th class="text-center">RUT</th>
                      <th class="text-center">Teléfono</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->empleados as $empleado)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $empleado->usuario->nombres }}</td>
                        <td>@nullablestring($empleado->usuario->apellidos)</td>
                        <td>{{ $empleado->usuario->rut }}</td>
                        <td>@nullablestring($empleado->usuario->telefono)</td>
                        <td>
                          @permission('empleado-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.empleado.show', ['empleado' => $empleado->id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
                          @permission('empleado-edit')
                            <a class="btn btn-primary btn-xs" href="{{ route('admin.empleado.edit', ['empleado' => $empleado->id]) }}"><i class="fa fa-pencil"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- #tab-1 -->
          @endpermission
          @permission('transporte-index')
            <div class="tab-pane{{ !Auth::user()->hasPermission('empleado-index') ? ' active' : '' }}" id="tab-22">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Patente</th>
                      <th class="text-center">Descripción</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->transportes as $transporte)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transporte->patente }}</td>
                        <td>{{ $transporte->vehiculo }}</td>
                        <td>
                          @permission('transporte-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.transporte.show', ['transporte' => $transporte->id] )}}"><i class="fa fa-search"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- #tab-2 -->
          @endpermission
          @permission('inventario-egreso-index')
            <div id="tab-24" class="tab-pane">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Inventario</th>
                      <th>Dirigido a</th>
                      <th>Tipo</th>
                      <th>Cantidad</th>
                      <th>Costo</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($contrato->inventariosV2Egreso as $egreso)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          @permission('inventario-v2-view')
                            <a href="{{ route('admin.inventario.v2.show', ['inventario' => $egreso->inventario_id]) }}">
                              {{ $egreso->inventario->nombre }}
                            </a>
                          @else
                            {{ $egreso->inventario->nombre }}
                          @endpermission
                        </td>
                        <td>
                          @if($egreso->cliente)
                            @permission('cliente-view')
                              <a href="{{ route('admin.cliente.show', ['cliente' => $egreso->cliente_id]) }}">
                                {{ $egreso->cliente->nombre }}
                              </a>
                            @else
                              {{ $egreso->cliente->nombre }}
                            @endpermission
                          @elseif($egreso->user)
                            @permission('user-view')
                              <a href="{{ route('admin.usuario.show', ['usuario' => $egreso->user_id]) }}">
                                {{ $egreso->user->nombre() }}
                              </a>
                            @else
                              {{ $egreso->user->nombre() }}
                            @endpermission
                          @else
                            @nullablestring(null)
                          @endif
                        </td>
                        <td class="text-center">{{ $egreso->tipo() }}</td>
                        <td class="text-right">{{ $egreso->cantidad() }}</td>
                        <td class="text-right">
                          @if($egreso->costo)
                            {{ $egreso->costo() }}
                          @else
                            @nullablestring(null)
                          @endif
                        </td>
                        <td class="text-center">
                          @permission('inventario-egreso-view|inventario-egreso-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('inventario-egreso-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.inventario.egreso.show', ['egreso' => $egreso->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('inventario-egreso-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.inventario.egreso.edit', ['egreso' => $egreso->id]) }}">
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
          @endpermission
          @permission('requerimiento-material-index')
            <div id="tab-25" class="tab-pane">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Faena</th>
                      <th class="text-center">Centro de Costo</th>
                      <th class="text-center">Dirigido a</th>
                      <th class="text-center">Productos</th>
                      <th class="text-center">Estatus</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->requerimientosMateriales as $requerimiento)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          @if($requerimiento->faena)
                            {{ $requerimiento->faena->nombre }}
                          @else
                            @nullablestring(null)
                          @endif
                        </td>
                        <td>
                          @if($requerimiento->centroCosto)
                            {{ $requerimiento->centroCosto->nombre }}
                          @else
                            @nullablestring(null)
                          @endif
                        </td>
                        <td>{{ $requerimiento->dirigidoA->nombre() }}</td>
                        <td class="text-right">{{ $requerimiento->productos_count }}</td>
                        <td class="text-center"><small>{!! $requerimiento->status() !!}</small></td>
                        <td>
                          @permission('requerimiento-material-view|requerimiento-material-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('requerimiento-material-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.requerimiento.material.show', ['requerimiento' => $requerimiento->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('requerimiento-material-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.requerimiento.material.edit', ['requerimiento' => $requerimiento->id]) }}">
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
          @endpermission
        </div>
      </div>
    </div>
  </div>

  @permission('contrato-edit|requisito-delete')
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
              <h4 class="modal-title" id="delFileModalLabel">Eliminar <span class="delItemType"></span></h4>
            </div>
            <div class="modal-body text-center">
              <h4 class="text-center">¿Esta seguro de eliminar este <span class="delItemType"></span>?</h4>
              <p class="text-center text-info-requisito">No se eliminarán los documentos o carpetas asociadas a este Requisito</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit" disabled>Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission

  @permission('contrato-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.contrato.destroy', ['contrato' => $contrato->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title">Eliminar Contrato</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Contrato?</h4>
              <p class="text-center">Se eliminaran todos los empleados en este contrato</p>
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
@endsection

@section('script')
  @include('partials.preview-pdf')

  <!-- Charts.js -->
  <script type="text/javascript" src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
  <script type="text/javascript">
    const partidasTipos = @json($partidasTipos);
    const colors = [
      '#4bc0c0',
      '#36a2eb',
      '#ff6384',
      '#ff9f40',
      '#ffcd56',
      '#23c6c8',
    ]
    let chartData = {
      labels: [],
      datasets: [],
      colors: [],
    }

    $.each(partidasTipos, function (k, partida){
      let title = partida.tipo.charAt(0).toUpperCase() + partida.tipo.slice(1);

      chartData.labels.push(title);
      chartData.datasets.push(partida.monto);
      chartData.colors.push(colors[k]);
    })

    $(document).ready(function () {
      var chartCanvas = document.getElementById('partidaTipoChart').getContext('2d');
      const config = {
        type: 'pie',
        data: {
          labels: chartData.labels,
          datasets: [{
            data: chartData.datasets,
            backgroundColor: chartData.colors
          }],
        },
        options: {
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Chart.js Pie Chart'
            }
          }
        }
      };
      
      new Chart(chartCanvas, config);
    });
  </script>

  @permission('contrato-edit|requisito-delete')
    <script type="text/javascript">
      $(document).ready(function(){
        $('#delFileModal').on('show.bs.modal', function(e){
          var button = $(e.relatedTarget),
              type   = button.data('type'),
              url = button.data('url');

          let title = type ? 'Requisito' : 'Adjunto';

          $('#delete-file-form button[type="submit"]').prop('disabled', !url)
          $('.text-info-requisito').toggle(!!type)
          $('.delItemType').text(title)
          $('#delete-file-form').attr('action', url);
        });
      });
    </script>
  @endpermission
@endsection
