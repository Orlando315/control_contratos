@extends('layouts.app')

@section('title', 'Facturas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Facturas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Facturas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Facturas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-clipboard"></i> {{ count($facturas) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-clipboard"></i> Facturas</h5>
          
          <div class="ibox-tools">
            @permission('factura-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.facturas.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Factura</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Contrato</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Pago</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($facturas as $factura)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $factura->contrato->nombre }}</td>
                  <td>{{ $factura->tipo() }}</td>
                  <td>{{ $factura->nombre }}</td>
                  <td class="text-right">{{ $factura->valor() }}</td>
                  <td class="text-center">{{ $factura->fecha }}</td>
                  <td class="text-center"><small>{!! $factura->pago() !!}</small></td>
                  <td class="text-center">
                    @permission('factura-view|factura-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('factura-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.facturas.show', ['factura' => $factura->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('factura-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.facturas.edit', ['factura' => $factura->id]) }}">
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
