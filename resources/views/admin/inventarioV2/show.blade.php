@extends('layouts.app')

@section('title', 'Inventarios V2')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Inventarios V2</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Inventarios V2</a></li>
        <li class="breadcrumb-item active"><strong>Inventario</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('inventario-v2-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.v2.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('inventario-v2-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.v2.edit', ['inventario' => $inventario->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('inventario-v2-edit')
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#stockModal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ajustar stock</button>
      @endpermission
      @permission('inventario-v2-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        @if($inventario->foto)
          <div class="ibox-content no-padding text-center border-left-right">
            <img class="img-fluid" src="{{ $inventario->foto_url }}" alt="Foto" style="max-height: 180px;margin: 0 auto;">
          </div>
        @endif
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $inventario->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Tipo código</b>
              <span class="pull-right">@nullablestring($inventario->tipo_codigo)</span>
            </li>
            <li class="list-group-item">
              <b>Código</b>
              <span class="pull-right">@nullablestring($inventario->codigo)</span>
            </li>
            <li class="list-group-item">
              <b>Unidad</b>
              <span class="pull-right">
                @permission('inventario-unidad-view')
                  <a href="{{ route('admin.unidad.show', ['unidad' => $inventario->unidad_id]) }}">
                    {{ $inventario->unidad->nombre }}
                  </a>
                @else
                  {{ $inventario->unidad->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Bodega</b>
              <span class="pull-right">
                @if($inventario->bodega)
                  @permission('bodega-view')
                    <a href="{{ route('admin.bodega.show', ['bodega' => $inventario->bodega_id]) }}">
                      {{ $inventario->bodega->nombre }}
                    </a>
                  @else
                    {{ $inventario->bodega->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Ubicación</b>
              <span class="pull-right">
                @if($inventario->ubicacion)
                  @permission('ubicacion-view')
                    <a href="{{ route('admin.ubicacion.show', ['ubicacion' => $inventario->ubicacion_id]) }}">
                      {{ $inventario->ubicacion->nombre }}
                    </a>
                  @else
                    {{ $inventario->ubicacion->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Stock</b>
              <span class="pull-right">
                {{ $inventario->stock() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Stock mínimo</b>
              <span class="pull-right">
                @if($inventario->stock_minimo)
                  {{ $inventario->stockMinimo() }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($inventario->descripcion)</span>
            </li>
            <li class="list-group-item">
              <b>Categorías</b>

              <div class="mt-2">
                @foreach($inventario->categorias as $categoria)
                  <p class="label label-default mb-1">{{ $categoria->etiqueta }}</p>
                @endforeach
              </div>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $inventario->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  @permission('inventario-ingreso-index|inventario-egreso-index')
    <div class="row">
      <div class="col-md-12">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            @permission('inventario-ingreso-index')
              <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-long-arrow-down"></i> Ingresos</a></li>
            @endpermission
            @permission('inventario-egreso-index')
              <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-long-arrow-up"></i> Egresos</a></li>
            @endpermission
          </ul>
          <div class="tab-content">
            @permission('inventario-ingreso-index')
              <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                  @permission('inventario-ingreso-create')
                    <div class="mb-3 text-right">
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.inventario.ingreso.create', ['inventario' => $inventario->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Ingreso</a>
                    </div>
                  @endpermission

                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($inventario->ingresos as $ingreso)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            @if($ingreso->proveedor)
                              @permission('proveedor-view')
                                <a href="{{ route('admin.proveedor.show', ['proveedor' => $ingreso->proveedor_id]) }}">
                                  {{ $ingreso->proveedor->nombre }}
                                </a>
                              @else
                                {{ $ingreso->proveedor->nombre }}
                              @endpermission
                            @else
                              @nullablestring(null)
                            @endif
                          </td>
                          <td class="text-right">{{ $ingreso->cantidad() }}</td>
                          <td class="text-right">
                            @if($ingreso->costo)
                              {{ $ingreso->costo() }}
                            @else
                              @nullablestring(null)
                            @endif
                          </td>
                          <td class="text-center">
                            @permission('inventario-ingreso-view|inventario-ingreso-edit')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('inventario-ingreso-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.ingreso.show', ['ingreso' => $ingreso->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('inventario-ingreso-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.ingreso.edit', ['ingreso' => $ingreso->id]) }}">
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
                  @permission('inventario-egreso-create')
                    <div class="mb-3 text-right">
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.inventario.egreso.create', ['inventario' => $inventario->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Egreso</a>
                    </div>
                  @endpermission

                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Dirigido a</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($inventario->egresos as $egreso)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
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
                                <a href="{{ route('admin.usuarios.show', ['usuario' => $egreso->user_id]) }}">
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
          </div>
        </div>
      </div>
    </div>
  @endpermission

  @permission('inventario-v2-edit')
    <div id="stockModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.inventario.v2.ajustar', ['inventario' => $inventario->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="stockModalLabel">Ajustar Stock</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center mb-3">La cantidad ingresada reemplazará al Stock disponible actualmente</h4>

              <div class="form-group{{ $errors->has('cantidad') ? ' has-error' : '' }}">
                <label for="cantidad">Cantidad: *</label>
                <input id="cantidad" class="form-control" type="number" name="cantidad" min="0" max="9999" placeholder="Cantidad" required>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Ajustar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission

  @permission('inventario-v2-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.inventario.v2.destroy', ['inventario' => $inventario->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Inventario</h4>
            </div>
            <div class="modal-body">
                  <h4 class="text-center">¿Esta seguro de eliminar este Inventario?</h4>
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
