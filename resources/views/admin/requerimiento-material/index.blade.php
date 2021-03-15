@extends('layouts.app')

@section('title', 'Requerimiento de Materiales')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Requerimiento de Materiales</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Solicitudes</li>
        <li class="breadcrumb-item active"><strong>Requerimiento de Materiales</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Requerimientos de Materiales</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-list-ul"></i> {{ count($requerimientosMateriales) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-list-ul"></i> Requerimientos de Materiales</h5>

          <div class="ibox-tools">
            @permission('requerimiento-material-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.requerimiento.material.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Requisito de Material</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Urgencia</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Faena</th>
                <th class="text-center">Centro de Costo</th>
                <th class="text-center">Dirigido a</th>
                <th class="text-center">Productos</th>
                <th class="text-center">Estatus</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($requerimientosMateriales as $requerimiento)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td class="text-center"><small>{!! $requerimiento->urgencia() !!}</small></td>
                  <td>{{ $requerimiento->contrato->nombre }}</td>
                  <td>
                    @if($requerimiento->faena)
                      {{ $requerimiento->faena->nombre }}
                    @else
                      @nullablestring(null)
                    @endif
                  </td>
                  <td>
                    @if($requerimiento->centroCosto)
                      {{ $requerimiento->centroCosto->nombre }}
                    @else
                      @nullablestring(null)
                    @endif
                  </td>
                  <td>{{ $requerimiento->dirigidoA->nombre() }}</td>
                  <td class="text-right">{{ $requerimiento->productos_count }}</td>
                  <td class="text-center"><small>{!! $requerimiento->status() !!}</small></td>
                  <td>
                    @permission('requerimiento-material-view|requerimiento-material-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('requerimiento-material-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.requerimiento.material.show', ['requerimiento' => $requerimiento->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('requerimiento-material-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.requerimiento.material.edit', ['requerimiento' => $requerimiento->id]) }}">
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
    </div>
  </div>
@endsection
