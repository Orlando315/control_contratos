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
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Pago</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($facturas as $factura)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $factura->contrato->nombre }}</td>
                  <td>{{ $factura->tipo() }}</td>
                  <td>{{ $factura->nombre }}</td>
                  <td>{{ $factura->valor() }}</td>
                  <td>{{ $factura->fecha }}</td>
                  <td>{!! $factura->pago() !!}</td>
                  <td>
                    @permission('factura-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.facturas.show', ['factura' => $factura->id] )}}"><i class="fa fa-search"></i></a>
                    @endpermission
                    @permission('factura-edit')
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.facturas.edit', ['factura' => $factura->id] )}}"><i class="fa fa-pencil"></i></a>
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