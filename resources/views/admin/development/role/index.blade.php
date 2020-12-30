@extends('layouts.app')

@section('title', 'Roles')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Roles</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item active"><strong>Roles</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Roles</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-user-circle" aria-hidden="true"></i> {{ count($roles) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="text-right">
    <a class="btn btn-primary btn-xs" href="{{ route('admin.development.role.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Role
    </a>
  </div>

  <div class="row mt-3">
    @foreach($roles as $role)
      <div class="col-lg-4">
        <div class="ibox ibox-with-footer mb-2">
          <div class="ibox-title">
            <h5 title="{{ $role->description }}"><a href="{{ route('admin.development.role.show', ['role' => $role->id]) }}">{{ $role->display_name }}</a></h5>
            <small class="label label-primary">{{ $role->name }}</small>
            <div class="ibox-tools">
              <a class="collapse-link" href="#">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content no-padding">
            <ul class="list-group">
              @forelse($role->permissions as $permission)
                <li class="list-group-item" title="{{ $permission->description }}">@nullablestring($permission->display_name) <span class="label">{{ $permission->name }}</span></li>
              @empty
                <li class="list-group-item text-center text-muted">No se han asignado Permissions</li>
              @endforelse
            </ul>
          </div>
          <div class="ibox-footer">
            <small class="text-muted">{{ $role->created_at }}</small>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection
