@extends('layouts.app')

@section('title', 'Empleados')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empleados</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Empleados</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Empleados</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-address-card text-success"></i> {{ count($empleados) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-address-card"></i> Empleados</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Nombres</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">RUT</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($empleados as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $d->contrato->nombre }}</td>
                  <td>{{ $d->usuario->nombres }}</td>
                  <td>{{ $d->usuario->apellidos }}</td>
                  <td>{{ $d->usuario->rut }}</td>
                  <td>{{ $d->usuario->telefono ?? 'N/A' }}</td>
                  <td>
                    <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.empleados.edit', ['empleado' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
