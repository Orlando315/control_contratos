@extends('layouts.app')

@section('title', 'Ubicación')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ubicaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Bodegas</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.bodega.show', ['bodega' => $ubicacion->bodega_id]) }}">Ubicaciones</a></li>
        <li class="breadcrumb-item active"><strong>Bodega</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('ubicacion-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.bodega.show', ['bodega' => $ubicacion->bodega_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('ubicacion-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.ubicacion.edit', ['ubicacion' => $ubicacion->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('ubicacion-delete')
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
              <span class="pull-right">{{ $ubicacion->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Bodega</b>
              <span class="pull-right">
                @permission('bodega-view')
                  <a href="{{ route('admin.bodega.show', ['bodega' => $ubicacion->bodega_id]) }}">
                    {{ $ubicacion->bodega->nombre }}
                  </a>
                @else
                  {{ $ubicacion->bodega->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $ubicacion->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div><!-- .row -->

  @permission('inventario-v2-index')
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="ibox">
          <div class="ibox-title">
            <h5><i class="fa fa-tasks"></i> Inventarios V2</h5>
          </div>
          <div class="ibox-content">
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
                @foreach($ubicacion->inventariosV2 as $inventario)
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
      </div>
    </div>
  @endpermission

  @permission('ubicacion-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.ubicacion.destroy', ['ubicacion' => $ubicacion->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Ubicación</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Ubicación?</h4>
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
