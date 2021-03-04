@extends('layouts.app')

@section('title', 'Faena')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Faenas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Faenas</a></li>
        <li class="breadcrumb-item active"><strong>Faena</strong></li>
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
      @permission('faena-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.faena.edit', ['faena' => $faena->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('faena-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Faena</b>
              <span class="pull-right">{{ $faena->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $faena->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          @permission('contrato-index')
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-clipboard"></i> Contratos</a></li>
          @endpermission
          @permission('transporte-index')
            <li><a class="nav-link{{ !Auth::user()->hasPermission('contrato-view') ? ' active' : '' }}" href="#tab-2" data-toggle="tab"><i class="fa fa-car"></i> Transportes</a></li>
          @endpermission
          @permission('inventario-egreso-index')
            <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-level-up"></i> Egresos (Inventarios V2)</a></li>
          @endpermission
          @permission('requerimiento-material-index')
            <li><a class="nav-link" href="#tab-4" data-toggle="tab"><i class="fa fa-list-ul"></i> Requerimiento de Materiales</a></li>
          @endpermission
        </ul>
        <div class="tab-content">
          @permission('contrato-index')
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Descripción</th>
                      <th class="text-center">Inicio</th>
                      <th class="text-center">Fin</th>
                      <th class="text-center">Valor</th>
                      <th class="text-center">Empleados</th>
                      @permission('contrato-view')
                        <th class="text-center">Acción</th>
                      @endpermission
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($faena->contratos as $contrato)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contrato->nombre }}</td>
                        <td>{{ $contrato->descripcion ?? 'M/A' }}</td>
                        <td>{{ $contrato->inicio }}</td>
                        <td>{{ $contrato->fin }}</td>
                        <td>{{ $contrato->valor() }}</td>
                        <td class="text-right">{{ $contrato->empleados_count }}</td>
                        @permission('contrato-view')
                          <td>
                            <a class="btn btn-success btn-flat btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id] )}}"><i class="fa fa-search"></i></a>
                          </td>
                        @endpermission
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
          @permission('transporte-index')
            <div id="tab-2" class="tab-pane{{ !Auth::user()->hasPermission('contrato-view') ? ' active' : '' }}">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Supervisor</th>
                      <th class="text-center">Vehiculo</th>
                      <th class="text-center">Patente</th>
                      @permission('transporte-view')
                        <th class="text-center">Acción</th>
                      @endpermission
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($faena->transportes as $transporte)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transporte->usuario->nombre() }}</td>
                        <td>{{ $transporte->vehiculo }}</td>
                        <td>{{ $transporte->patente }}</td>
                        @permission('transporte-view')
                          <td>
                            <a class="btn btn-success btn-xs" href="{{ route('admin.transportes.show', ['transporte' => $transporte->id]) }}"><i class="fa fa-search"></i></a>
                          </td>
                        @endpermission
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
          @permission('inventario-egreso-index')
            <div id="tab-3" class="tab-pane">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Inventario</th>
                      <th class="text-center">Cantidad</th>
                      <th class="text-center">Costo</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($faena->inventariosV2Egreso as $egreso)
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
                        <td class="text-right">{{ $egreso->cantidad() }}</td>
                        <td class="text-right">
                          @if($egreso->costo)
                            {{ $egreso->costo() }}
                          @else
                            @nullablestring(null)
                          @endif
                        </td>
                        <td>
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
            <div id="tab-4" class="tab-pane">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Contrato</th>
                      <th class="text-center">Centro de Costo</th>
                      <th class="text-center">Dirigido a</th>
                      <th class="text-center">Productos</th>
                      <th class="text-center">Estatus</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($faena->requerimientosMateriales as $requerimiento)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $requerimiento->contrato->nombre }}</td>
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
  </div><!-- .row -->

  @permission('faena-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.faena.destroy', ['faena' => $faena->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Faena</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Faena?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection
