@extends('layouts.app')

@section('title', 'Unidad')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Unidades</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Unidades</a></li>
        <li class="breadcrumb-item active"><strong>Unidad</strong></li>
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
      @permission('inventario-unidad-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.unidad.edit', ['unidad' => $unidad->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('inventario-unidad-delete')
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
              <span class="pull-right">{{ $unidad->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $unidad->created_at }}</small>
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
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Unidad</th>
                  <th class="text-center">Stock</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($unidad->inventariosV2 as $inventario)
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
      </div>
    </div>
  @endpermission

  @permission('inventario-unidad-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.unidad.destroy', ['unidad' => $unidad->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Unidad</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Unidad?</h4>
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
