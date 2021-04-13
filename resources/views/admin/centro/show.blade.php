@extends('layouts.app')

@section('title', 'Centro de costo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Centro de costos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Centros de costo</a></li>
        <li class="breadcrumb-item active"><strong>Centro de costo</strong></li>
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
      @permission('centro-costo-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.centro.edit', ['centro' => $centro->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('centro-costo-delete')
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
              <b>Nombre</b>
              <span class="pull-right">{{ $centro->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $centro->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div><!-- .row -->

  @permission('inventario-egreso-index')
    <div class="row">
      <div class="col-md-12">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            @permission('inventario-egreso-index')
              <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-level-up"></i> Egresos (Inventarios V2)</a></li>
            @endpermission
            @permission('requerimiento-material-index')
              <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-list-ul"></i> Requerimiento de Materiales</a></li>
            @endpermission
            @permission('factura-index')
              <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-clipboard"></i> Facturas</a></li>
            @endpermission
          </ul>
          <div class="tab-content">
            @permission('inventario-egreso-index')
              <div id="tab-1" class="tab-pane active">
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
                      @foreach($centro->inventariosV2Egreso as $egreso)
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
            @permission('inventario-egreso-index')
              <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Contrato</th>
                        <th class="text-center">Faena</th>
                        <th class="text-center">Dirigido a</th>
                        <th class="text-center">Productos</th>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($centro->requerimientosMateriales as $requerimiento)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $requerimiento->contrato->nombre }}</td>
                          <td>
                            @if($requerimiento->faena)
                              {{ $requerimiento->faena->nombre }}
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
            @permission('factura-index')
              <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Contrato</th>
                        <th>Tipo</th>
                        <th>Folio</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                        <th>Pago</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($centro->facturas as $factura)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $factura->contrato->nombre }}</td>
                          <td>{{ $factura->tipo() }}</td>
                          <td>{{ $factura->nombre }}</td>
                          <td class="text-right">{{ $factura->valor() }}</td>
                          <td class="text-center">{{ $factura->fecha }}</td>
                          <td class="text-center"><small>{!! $factura->pago() !!}</small></td>
                          <td class="text-center">
                            @permission('factura-view|factura-edit')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('factura-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.facturas.show', ['factura' => $factura->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('factura-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.facturas.edit', ['factura' => $factura->id]) }}">
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
          </div><!-- /.tab-content -->
        </div>
      </div>
    </div>
  @endpermission

  @permission('centro-costo-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.centro.destroy', ['centro' => $centro->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Centro de costo</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Centro de costo?</h4>
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
