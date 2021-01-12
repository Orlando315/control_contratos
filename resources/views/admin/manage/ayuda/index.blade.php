@extends('layouts.app')

@section('title', 'Ayudas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ayudas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active"><strong>Ayudas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Ayudas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-question-circle"></i> {{ count($ayudas) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-question-circle"></i> Ayudas</h5>

          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.ayuda.create') }}">
              <i class="fa fa-plus" aria-hidden="true"></i> Nueva Ayuda
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Roles</th>
                <th class="text-center">Título</th>
                <th class="text-center">Video</th>
                <th class="text-center">Estatus</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ayudas as $ayuda)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{!! $ayuda->allRolesNames() !!}</td>
                  <td>{{ $ayuda->titulo }}</td>
                  <td class="text-center">{!! $ayuda->video() !!}</td>
                  <td class="text-center">{!! $ayuda->status() !!}</td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.manage.ayuda.show', ['ayuda' => $ayuda->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.ayuda.edit', ['ayuda' => $ayuda->id] )}}"><i class="fa fa-pencil"></i></a>
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
