@extends('layouts.app')

@section('title', 'Bodega')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Bodegas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Bodegas</a></li>
        <li class="breadcrumb-item active"><strong>Bodega</strong></li>
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
      @permission('bodega-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.bodega.edit', ['bodega' => $bodega->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('bodega-delete')
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
              <span class="pull-right">{{ $bodega->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $bodega->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div><!-- .row -->

  @permission('inventario-v2-index|inventario-egreso-index')
    <div class="row">
      <div class="col-md-12">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            @permission('inventario-v2-index')
              <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-tasks"></i> Inventarios V2</a></li>
            @endpermission
            @permission('ubicacion-index')
              <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-sitemap"></i> Ubicación</a></li>
            @endpermission
          </ul>
          <div class="tab-content">
            @permission('inventario-ingreso-index')
              <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Unidad</th>
                        <th>Stock</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($bodega->inventariosV2 as $inventario)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $inventario->nombre }}</td>
                          <td>{{ $inventario->unidad->nombre }}</td>
                          <td class="text-right">{{ $inventario->stock() }}</td>
                          <td class="text-center">
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
            @permission('ubicacion-index')
              <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                  @permission('ubicacion-create')
                    <div class="mb-3 text-right">
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.ubicacion.create', ['bodega' => $bodega->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Ubicación</a>
                    </div>
                  @endpermission

                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Inventarios V2</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($bodega->ubicaciones as $ubicacion)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $ubicacion->nombre }}</td>
                          <td class="text-right">{{ $ubicacion->inventarios_v2_count }}</td>
                          <td class="text-center">
                            @permission('ubicacion-view|ubicacion-edit')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('ubicacion-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.ubicacion.show', ['ubicacion' => $ubicacion->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('ubicacion-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.ubicacion.edit', ['ubicacion' => $ubicacion->id]) }}">
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

  @permission('bodega-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.bodega.destroy', ['bodega' => $bodega->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Bodega</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Bodega?</h4>
              <p class="text-center">Se eliminará toda la información relacionada a la Bodega</p>
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
