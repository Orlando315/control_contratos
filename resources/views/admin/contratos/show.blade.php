@extends('layouts.app')

@section('title', 'Contrato')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Contratos</a></li>
        <li class="breadcrumb-item active"><strong>Contrato</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('contrato-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.edit', ['contrato' => $contrato->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
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
              <span class="pull-right"> {{ $contrato->descripcion }} </span>
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
                                <a href="{{ $requisitoContrato->isFile() ? route('admin.documentos.download', ['documento' => $requisitoContrato->documento->id]) : route('admin.carpeta.show', ['carpeta' => $requisitoContrato->documento->id]) }}">
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
              <div class="mb-3">
                @if($contrato->documentos()->count() < 10 && Auth::user()->hasPermission('contrato-edit'))
                  <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => 'contratos', 'id' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.documentos.create', ['type' => 'contratos', 'id' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                @endif
              </div>
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
              <div class="mb-3">
                @permission('plantilla-documento-create')
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Documento</a>
                @endpermission
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
                  @foreach($contrato->plantillaDocumentos as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->nombre }}</td>
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
            <li><a class="nav-link{{ !Auth::user()->hasPermission('empleado-index|transporte-index') ? ' active' : '' }}" href="#tab-23" data-toggle="tab"><i class="fa fa-arrow-right"></i> Entregas de Inventarios</a></li>
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
                    <a class="btn btn-default btn-xs" href="{{ route('admin.sueldos.index', ['contrato' => $contrato->id]) }}"><i class="fa fa-money" aria-hidden="true"></i> Ver sueldos</a>
                  @endpermission
                  <a class="btn btn-default btn-xs" href="{{ route('admin.contratos.calendar', ['contrato' => $contrato->id]) }}"><i class="fa fa-calendar" aria-hidden="true"></i> Ver calendario</a>
                  @permission('empleado-create')
                    <div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo empleado</button>
                      <ul class="dropdown-menu" x-placement="bottom-start">
                        <li><a class="dropdown-item" href="{{ route('admin.empleados.create', ['contrato' => $contrato->id]) }}">Nuevo Empleado</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.empleados.import.create', ['contrato' => $contrato->id]) }}">Impotar Empleados</a></li>
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
                            <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $empleado->id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
                          @permission('empleado-edit')
                            <a class="btn btn-primary btn-xs" href="{{ route('admin.empleados.edit', ['empleado' => $empleado->id]) }}"><i class="fa fa-pencil"></i></a>
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
                      <th class="text-center">Supervisor</th>
                      <th class="text-center">Vehiculo</th>
                      <th class="text-center">Patente</th>
                      <th class="text-center">Agregado</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->transportes as $transporte)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          @permission('user-view')
                            <a href="{{ route('admin.usuarios.show', ['usuario' => $transporte->user_id]) }}">
                              {{ $transporte->usuario->nombre() }}
                            </a>
                          @else
                            {{ $transporte->usuario->nombre() }}
                          @endpermission
                        </td>
                        <td>{{ $transporte->vehiculo }}</td>
                        <td>{{ $transporte->patente }}</td>
                        <td>{{ $transporte->created_at }}</td>
                        <td>
                          @permission('transporte-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.transportes.show', ['transporte' => $transporte->id] )}}"><i class="fa fa-search"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- #tab-2 -->
          @endpermission
          <div class="tab-pane{{ !Auth::user()->hasPermission('empleado-index|transporte-index') ? ' active' : '' }}" id="tab-23">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Realizado por</th>
                    <th class="text-center">Entregado a</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Recibido</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($contrato->entregas()->get() as $entrega)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        @permission('inventario-view')
                          <a href="{{ route('admin.inventarios.show', ['inventario' => $entrega->inventario->id]) }}">
                            {{ $entrega->inventario->nombre }}
                          </a>
                        @else
                          {{ $entrega->inventario->nombre }}
                        @endpermission
                      </td>
                      <td>{{ $entrega->realizadoPor->nombres }} {{ $entrega->realizadoPor->apellidos }}</td>
                      <td>{{ $entrega->nombres }} {{ $entrega->apellidos }}</td>
                      <td>{{ $entrega->cantidad() }}</td>
                      <td>{{ $entrega->created_at }}</td>
                      <td>{!! $entrega->recibido() !!}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- #tab-3 -->
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
          <form action="{{ route('admin.contratos.destroy', ['contrato' => $contrato->id]) }}" method="POST">
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
