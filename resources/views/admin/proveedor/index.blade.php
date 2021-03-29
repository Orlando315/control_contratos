@extends('layouts.app')

@section('title', 'Proveedores')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Proveedores</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Proveedores</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Proveedores</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-briefcase"></i> {{ count($proveedores) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-briefcase" aria-hidden="true"></i> Proveedores</h5>
          <div class="ibox-tools">
            @permission('proveedor-create')
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-plus" aria-hidden="false"></i> Nuevo Proveedor
                </button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="top-start">
                  <a class="dropdown-item" href="{{ route('admin.proveedor.create', ['type' => 'persona']) }}">Persona</a>
                  <a class="dropdown-item" href="{{ route('admin.proveedor.create', ['type' => 'empresa']) }}">Empresa</a>
                </div>
              </div>
            @endpermission
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
              @foreach($proveedores as $proveedor)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $proveedor->nombre }}</td>
                  <td>@nullablestring($proveedor->telefono)</td>
                  <td>{{ $proveedor->rut }}</td>
                  <td>@nullablestring($proveedor->email)</td>
                  <td class="text-center">
                    <small>
                      {!! $proveedor->tipo() !!}
                    </small>
                  </td>
                  <td class="text-center">
                    @permission('proveedor-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.proveedor.show', ['proveedor' => $proveedor->id]) }}"><i class="fa fa-search"></i></a>
                    @endpermission
                    @permission('proveedor-edit')
                      @if($proveedor->isPersona())
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.proveedor.edit', ['proveedor' => $proveedor->id]) }}"><i class="fa fa-pencil"></i></a>
                      @endif
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
