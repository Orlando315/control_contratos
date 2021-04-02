@extends('layouts.app')

@section('title', 'Transportes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Transportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Transportes</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Transportes</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-car"></i> {{ count($transportes) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-car"></i> Transportes</h5>
          <div class="ibox-tools">
            @permission('transporte-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.transportes.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Transporte</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Patente</th>
                <th>Descripción</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Color</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($transportes as $transporte)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $transporte->patente }}</td>
                  <td>@nullablestring($transporte->vehiculo)</td>
                  <td>@nullablestring($transporte->modelo)</td>
                  <td>@nullablestring($transporte->marca)</td>
                  <td>@nullablestring($transporte->color)</td>
                  <td class="text-center">
                    @permission('transporte-view|transporte-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('transporte-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.transportes.show', ['transporte' => $transporte->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('transporte-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.transportes.edit', ['transporte' => $transporte->id]) }}">
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
