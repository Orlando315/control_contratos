@extends('layouts.app')

@section('title', 'Gastos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Gastos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Gastos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Gastos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-credit-card"></i> {{ count($gastos) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-credit-card"></i> Gastos</h5>
          <div class="ibox-tools">
            @permission('gasto-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.gastos.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Gasto</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Etiqueta</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($gastos as $gasto)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $gasto->contrato->nombre }}</td>
                  <td>{{ $gasto->etiqueta->etiqueta }}</td>
                  <td>{{ $gasto->nombre }}</td>
                  <td>{{ $gasto->valor() }}</td>
                  <td>
                    @permission('gasto-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.gastos.show', ['gasto' => $gasto->id]) }}"><i class="fa fa-search"></i></a>
                    @endpermission
                    @permission('gasto-edit')
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.gastos.edit', ['gasto' => $gasto->id]) }}"><i class="fa fa-pencil"></i></a>
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
