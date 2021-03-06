@extends('layouts.app')

@section('title', 'Etiqueta')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Etiquetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.etiqueta.index') }}">Etiquetas</a></li>
        <li class="breadcrumb-item active"><strong>Etiqueta</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('etiqueta-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.etiqueta.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('etiqueta-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.etiqueta.edit', ['etiqueta' => $etiqueta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('etiqueta-delete')
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
              <b>Etiqueta</b>
              <span class="pull-right">{{ $etiqueta->etiqueta }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $etiqueta->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    @permission('factura-index|gasto-index|inventario-v2-index')
      <div class="col-md-12">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            @permission('factura-index')
              <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-file"></i> Facturas</a></li>
            @endpermission
            @permission('gasto-index')
              <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-credit-card"></i> Gastos</a></li>
            @endpermission
            @permission('inventario-v2-index')
              <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-tasks"></i> Inventarios V2</a></li>
            @endpermission
          </ul>
          <div class="tab-content">
            @permission('factura-index')
              <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Contrato</th>
                        <th class="text-center">Tipo</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Pago</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($etiqueta->facturas as $factura)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            @permission('contrato-view')
                              <a href="{{ route('admin.contrato.show', ['contrato' => $factura->contrato_id]) }}">{{ $factura->contrato->nombre }}</a>
                            @else
                              {{ $factura->contrato->nombre }}
                            @endpermission
                          </td>
                          <td>{{ $factura->tipo() }}</td>
                          <td>{{ $factura->nombre }}</td>
                          <td>{{ $factura->valor() }}</td>
                          <td>{{ $factura->fecha }}</td>
                          <td><small>{!! $factura->pago() !!}</small></td>
                          <td>
                            @permission('factura-view')
                              <a class="btn btn-success btn-xs" href="{{ route('admin.factura.show', ['factura' => $factura->id]) }}"><i class="fa fa-search"></i></a>
                            @endpermission
                            @permission('factura-edit')
                              <a class="btn btn-primary btn-xs" href="{{ route('admin.factura.edit', ['factura' => $factura->id]) }}"><i class="fa fa-pencil"></i></a>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div><!-- /.tab-pane -->
            @endpermission
            @permission('gasto-index')
              <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Contrato</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($etiqueta->gastos as $gasto)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            @permission('contrato-view')
                              <a href="{{ route('admin.contrato.show', ['contrato', $gasto->contrato_id]) }}">{{ $gasto->contrato->nombre }}</a>
                            @else
                              {{ $gasto->contrato->nombre }}
                            @endpermission
                          </td>
                          <td>{{ $gasto->nombre }}</td>
                          <td>{{ $gasto->valor() }}</td>
                          <td>
                            @permission('gasto-view')
                              <a class="btn btn-success btn-xs" href="{{ route('admin.gasto.show', ['gasto' => $gasto->id]) }}"><i class="fa fa-search"></i></a>
                            @endpermission
                            @permission('gasto-edit')
                              <a class="btn btn-primary btn-xs" href="{{ route('admin.gasto.edit', ['gasto' => $gasto->id]) }}"><i class="fa fa-pencil"></i></a>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div><!-- /.tab-pane -->
            @endpermission
            @permission('inventario-v2-index')
              <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Unidad</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($etiqueta->inventariosV2 as $inventario)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $inventario->nombre }}</td>
                          <td>{{ $inventario->unidad->nombre }}</td>
                          <td class="text-right">{{ $inventario->stock() }}</td>
                          <td>
                            @permission('inventario-v2-view|inventario-v2-edit|inventario-ingreso-create|inventario-egreso-create')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('inventario-v2-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.v2.show', ['inventario' => $inventario->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('inventario-v2-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.v2.edit', ['inventario' => $inventario->id]) }}">
                                        <i class="fa fa-pencil"></i> Editar
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('inventario-ingreso-create')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.ingreso.create', ['inventario' => $inventario->id]) }}" title="Ingreso de Stock">
                                        <i class="fa fa-plus"></i> Nuevo Ingreso
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('inventario-egreso-create')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.egreso.create', ['inventario' => $inventario->id]) }}" title="Egreso de Stock">
                                        <i class="fa fa-plus"></i> Nuevo Egreso
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
    @endpermission
  </div><!-- .row -->

  @permission('etiqueta-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.etiqueta.destroy', ['etiqueta' => $etiqueta->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Etiqueta</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Etiqueta?</h4>
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
