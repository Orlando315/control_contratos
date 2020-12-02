@extends('layouts.app')

@section('title', 'Clientes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Clientes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Clientes</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Clientes</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-user-circle-o"></i> {{ count($clientes) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-user-circle-o" aria-hidden="true"></i> Clientes</h5>
          <div class="ibox-tools">
            <div class="btn-group">
              <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Cliente
              </button>
              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                <li><a class="dropdown-item" href="{{ route('admin.cliente.create', ['type' => 'persona']) }}">Persona</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.cliente.create', ['type' => 'empresa']) }}">Empresa</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">RUT</th>
                <th class="text-center">Email</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($clientes as $cliente)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $cliente->nombre }}</td>
                  <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                  <td>{{ $cliente->rut }}</td>
                  <td>{{ $cliente->email ?? 'N/A' }}</td>
                  <td class="text-center">
                    <small>
                      {!! $cliente->tipo() !!}
                    </small>
                  </td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.cliente.show', ['cliente' => $cliente->id]) }}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.cliente.edit', ['cliente' => $cliente->id]) }}"><i class="fa fa-pencil"></i></a>
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
