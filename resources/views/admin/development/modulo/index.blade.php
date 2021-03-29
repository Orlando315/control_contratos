@extends('layouts.app')

@section('title', 'Modulos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Modulos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item active"><strong>Modulos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Modulos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-cube" aria-hidden="true"></i> {{ count($modulos) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="text-right">
    <a class="btn btn-primary btn-xs" href="{{ route('admin.development.modulo.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Modulo
    </a>
  </div>

  <div class="row mt-3">
    @foreach($modulos as $modulo)
      <div class="col-lg-4">
        <div class="ibox ibox-with-footer mb-2">
          <div class="ibox-title">
            <h5 title="{{ $modulo->description }}">
              <a href="{{ route('admin.development.modulo.show', ['modulo' => $modulo->id]) }}">{{ $modulo->name() }}</a>
            </h5>
            <div class="ibox-tools">
              <a class="collapse-link" href="#">
                <i class="fa fa-chevron-up"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content no-padding">
            <ul class="list-group">
              @forelse($modulo->permissions as $permission)
                <li class="list-group-item" title="{{ $permission->description }}">@nullablestring($permission->display_name) <span class="label">{{ $permission->name }}</span></li>
              @empty
                <li class="list-group-item text-center text-muted">No se han asignado Permissions</li>
              @endforelse
            </ul>
          </div>
          <div class="ibox-footer">
            <small class="text-muted">{{ $modulo->created_at }}</small>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection
