@extends('layouts.app')

@section('title', 'Empresas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active"><strong>Empresas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Empresas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-building"></i> {{ count($empresas) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-building"></i> Empresas</h5>

          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.empresa.create') }}">
              <i class="fa fa-plus" aria-hidden="true"></i> Nueva Empresa
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Razón Social</th>
                <th class="text-center">RUT</th>
                <th class="text-center">Representante</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Email</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($empresas as $empresa)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $empresa->nombre }}</td>
                  <td>@nullablestring($empresa->rut)</td>
                  <td>@nullablestring($empresa->representante)</td>
                  <td>@nullablestring($empresa->telefono)</td>
                  <td>@nullablestring($empresa->email)</td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.manage.empresa.show', ['empresa' => $empresa->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.empresa.edit', ['empresa' => $empresa->id] )}}"><i class="fa fa-pencil"></i></a>
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
