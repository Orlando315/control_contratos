@extends('layouts.app')

@section('title', 'Permissions')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Permissions</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item active"><strong>Permissions</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Permissions</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-key" aria-hidden="true"></i> {{ count($permissions) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-key" aria-hidden="true"></i> Permissions</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.development.permission.create') }}">
              <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Permission
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Permission</th>
                <th class="text-center">Description</th>
                <th class="text-center">Modulo</th>
                <th class="text-center">Roles</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($permissions as $permission)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $permission->display_name ?? 'N/A' }} <small class="label label-primary">{{ $permission->name }}</small></td>
                  <td>{{ $permission->description }}</td>
                  <td>{{ $permission->modulo->name() }}</td>
                  <td class="text-center">{{ $permission->roles_count }}</td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.development.permission.show', ['permission' => $permission->id]) }}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.development.permission.edit', ['permission' => $permission->id]) }}"><i class="fa fa-pencil"></i></a>
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
