@extends('layouts.app')

@section('title', 'Usuarios')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Usuarios</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Usuarios</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-users"></i> {{ count($usuarios) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-users"></i> Usuarios</h5>

          <div class="ibox-tools">
            @permission('user-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.usuarios.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Usuario</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Roles</th>
                <th class="text-center">Nombres</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">RUT</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($usuarios as $usuario)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{!! $usuario->allRolesNames() !!}</td>
                  <td>{{ $usuario->nombres }}</td>
                  <td>@nullablestring($usuario->apellidos)</td>
                  <td>{{ $usuario->rut }}</td>
                  <td>@nullablestring($usuario->telefono)</td>
                  <td>
                    @permission('user-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.usuarios.show', ['usuario' => $usuario->id]) }}"><i class="fa fa-search"></i></a>
                    @endpermission
                    @permission('user-edit')
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.usuarios.edit', ['usuario' => $usuario->id]) }}"><i class="fa fa-pencil"></i></a>
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
@endsection
