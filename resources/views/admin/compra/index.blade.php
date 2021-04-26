@extends('layouts.app')

@section('title', 'Ordenes de compra')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ordenes de compra</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Ordenes de compra</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Ordenes de compra</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-plus-square"></i> {{ count($compras) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-plus-square" aria-hidden="true"></i> Ordenes de compra</h5>
          <div class="ibox-tools">
            @permission('compra-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.compra.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Orden de compra</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Código</th>
                <th class="text-center">Proveedor</th>
                <th class="text-center">Total</th>
                <th class="text-center">Facturada</th>
                <th class="text-center">Creado</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($compras as $compra)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $compra->codigo() }}</td>
                  <td>{{ $compra->proveedor->nombre }}</td>
                  <td class="text-right">{{ $compra->total() }}</td>
                  <td class="text-center"><small>{!! $compra->facturacionStatus() !!}</small></td>
                  <td class="text-center">{{ $compra->created_at->format('d-m-Y H:i:s') }}</td>
                  <td class="text-center">
                    @permission('compra-view|compra-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('compra-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.compra.show', ['compra' => $compra->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('compra-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.compra.edit', ['compra' => $compra->id]) }}">
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
