@extends('layouts.app')

@section('title', 'Empleados')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empleados</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Empleados</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          @permission('empleado-index')
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab">Empleados</a></li>
          @endpermission
          @permission('postulante-index')
            <li><a class="nav-link" href="#tab-2" data-toggle="tab">Postulantes</a></li>
          @endpermission
        </ul>
        <div class="tab-content">
          @permission('empleado-index')
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                @permission('empleado-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.empleados.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Empleado</a>
                  </div>
                @endpermission

                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Contrato</th>
                      <th class="text-center">Nombres</th>
                      <th class="text-center">Apellidos</th>
                      <th class="text-center">RUT</th>
                      <th class="text-center">Teléfono</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($empleados as $empleado)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $empleado->contrato->nombre }}</td>
                        <td>{{ $empleado->usuario->nombres }}</td>
                        <td>@nullablestring($empleado->usuario->apellidos)</td>
                        <td>{{ $empleado->usuario->rut }}</td>
                        <td>@nullablestring($empleado->usuario->telefono)</td>
                        <td>
                          @permission('empleado-view|empleado-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('empleado-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.empleados.show', ['empleado' => $empleado->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('empleado-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.empleados.edit', ['empleado' => $empleado->id]) }}">
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
          @permission('postulante-index')
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                @permission('postulante-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.postulante.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Postulante</a>
                  </div>
                @endpermission

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
                  <tbody>
                    @foreach($postulantes as $postulante)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $postulante->nombres }}</td>
                        <td>@nullablestring($postulante->apellidos)</td>
                        <td>{{ $postulante->rut }}</td>
                        <td>@nullablestring($postulante->telefono)</td>
                        <td class="text-center">
                          @permission('postulante-view|postulante-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('postulante-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.postulante.show', ['postulante' => $postulante->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('postulante-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.postulante.edit', ['postulante' => $postulante->id]) }}">
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
