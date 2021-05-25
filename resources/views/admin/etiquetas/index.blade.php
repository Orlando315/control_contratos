@extends('layouts.app')

@section('title','Etiquetas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Etiquetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Etiquetas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Etiquetas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-tags"></i> {{ count($etiquetas) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-tags"></i> Etiquetas</h5>
          <div class="ibox-tools">
            @permission('etiqueta-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.etiqueta.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Etiqueta</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Etiqueta</th>
                <th class="text-center">Facturas</th>
                <th class="text-center">Gastos</th>
                <th class="text-center">Inventarios V2</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($etiquetas as $etiqueta)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $etiqueta->etiqueta }}</td>
                  <td>{{ $etiqueta->facturas_count }}</td>
                  <td>{{ $etiqueta->gastos_count }}</td>
                  <td>{{ $etiqueta->inventarios_v2_count }}</td>
                  <td>
                    @permission('etiqueta-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.etiqueta.show', ['etiqueta' => $etiqueta->id]) }}"><i class="fa fa-search"></i></a>
                    @endpermission
                    @permission('etiqueta-edit')
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.etiqueta.edit', ['etiqueta' => $etiqueta->id]) }}"><i class="fa fa-pencil"></i></a>
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
