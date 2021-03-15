@extends('layouts.app')

@section('title', 'Requerimiento de Materiales')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Requerimiento de Materiales</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
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

  <div class="row">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-list-ul"></i> Requerimientos de Materiales</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <a class="btn btn-primary btn-xs" href="{{ route('requerimiento.material.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Requisito de Material</a>
              </div>

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
                        <div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                          <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                            <li>
                              <a class="dropdown-item" href="{{ route('requerimiento.material.show', ['requerimiento' => $requerimiento->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                            @if(Auth::id() == $requerimiento->solicitante || Auth::user()->hasPermission('requerimiento-material-edit'))
                              <li>
                                <a class="dropdown-item" href="{{ route('requerimiento.material.edit', ['requerimiento' => $requerimiento->id]) }}">
                                  <i class="fa fa-pencil"></i> Editar
                                </a>
                              </li>
                            @endif
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
    </div>
  </div>
@endsection
