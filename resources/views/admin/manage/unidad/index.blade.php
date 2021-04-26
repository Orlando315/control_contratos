@extends('layouts.app')

@section('title', 'Unidades')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Unidades</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><strong>Unidades</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Unidades</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-file-text-o"></i> {{ count($unidades) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-file-text-o"></i> Unidades</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.unidad.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Unidad</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Nombre</th>
                <th>Inventarios V2</th>
                <th>Predeterminada</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($unidades as $unidad)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $unidad->nombre }}</td>
                  <td class="text-right">{{ $unidad->inventarios_v2_count }}</td>
                  <td class="text-center">{!! $unidad->isPredeterminada(false) !!}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                        <li>
                          <a class="dropdown-item" href="{{ route('admin.manage.unidad.show', ['unidad' => $unidad->id]) }}">
                            <i class="fa fa-search"></i> Ver
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="{{ route('admin.manage.unidad.edit', ['unidad' => $unidad->id]) }}">
                            <i class="fa fa-pencil"></i> Editar
                          </a>
                        </li>
                      </ul>
                    </div>
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
