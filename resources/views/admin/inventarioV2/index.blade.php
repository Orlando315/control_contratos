@extends('layouts.app')

@section('title', 'Inventarios V2')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Inventarios V2</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Inventarios V2</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Inventarios V2</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-tasks"></i> {{ count($inventarios) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          @permission('inventario-v2-index')
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-tasks"></i> Inventarios</a></li>
          @endpermission
          @permission('unidad-index')
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-file-text-o"></i> Unidades</a></li>
          @endpermission
          @permission('bodega-index')
            <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-cube"></i> Bodegas</a></li>
          @endpermission
        </ul>
        <div class="tab-content">
          @permission('inventario-v2-index')
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                @permission('inventario-v2-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-default btn-xs" href="{{ route('admin.inventario.v2.export') }}"><i class="fa fa-download" aria-hidden="true"></i> Exportar</a>
                    <a class="btn btn-default btn-xs" href="{{ route('admin.inventario.v2.mass.edit') }}"><i class="fa fa-edit" aria-hidden="true"></i> Editar masivo</a>
                    <div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Inventario</button>
                      <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                        <li><a class="dropdown-item" href="{{ route('admin.inventario.v2.create') }}">Nuevo Inventario</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.inventario.v2.import.create') }}">Importar Inventario</a></li>
                      </ul>
                    </div>
                  </div>
                @endpermission

                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Bodega</th>
                      <th>Ubicación</th>
                      <th>Stock</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($inventarios as $inventario)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $inventario->nombre }}</td>
                        <td>@nullablestring(optional($inventario->bodega)->nombre)</td>
                        <td>@nullablestring(optional($inventario->ubicacion)->nombre)</td>
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
          @permission('inventario-unidad-index')
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                @permission('inventario-unidad-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.unidad.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Unidad</a>
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
                    @foreach($unidades as $unidad)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unidad->nombre }}</td>
                        <td class="text-right">{{ $unidad->inventarios_v2_count }}</td>
                        <td class="text-center">
                          @permission('inventario-unidad-view|inventario-unidad-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('inventario-unidad-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.unidad.show', ['unidad' => $unidad->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @if(Auth::user()->hasPermission('inventario-unidad-edit') && $unidad->isNotGlobal())
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.unidad.edit', ['unidad' => $unidad->id]) }}">
                                      <i class="fa fa-pencil"></i> Editar
                                    </a>
                                  </li>
                                @endif
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
          @permission('bodega-index')
            <div id="tab-3" class="tab-pane">
              <div class="panel-body">
                @permission('bodega-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.bodega.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Bodega</a>
                  </div>
                @endpermission

                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr class="text-center">
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Ubicaciones</th>
                      <th>Inventarios V2</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bodegas as $bodega)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bodega->nombre }}</td>
                        <td class="text-right">{{ $bodega->ubicaciones_count }}</td>
                        <td class="text-right">{{ $bodega->inventarios_v2_count }}</td>
                        <td class="text-center">
                          @permission('bodega-view|bodega-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('bodega-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.bodega.show', ['bodega' => $bodega->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('bodega-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.bodega.edit', ['bodega' => $bodega->id]) }}">
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
@endsection
