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
                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle" aria-expanded="false">
                  <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Proveedor
                </button>
                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                  <li><a class="dropdown-item" href="{{ route('admin.proveedor.create', ['type' => 'persona']) }}">Persona</a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.proveedor.create', ['type' => 'empresa']) }}">Empresa</a></li>
                  <li><a class="dropdown-item" href="{{ route('admin.proveedor.import.create') }}">Importar</a></li>
                </ul>
              </div>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>RUT</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Acción</th>
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
                    @permission('proveedor-view|proveedor-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('proveedor-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.proveedor.show', ['proveedor' => $proveedor->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @if(Auth::user()->hasPermission('proveedor-edit') && $proveedor->isPersona())
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.proveedor.edit', ['proveedor' => $proveedor->id]) }}">
                                <i class="fa fa-pencil"></i> Editar
                              </a>
                            </li>
                          @endif
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
