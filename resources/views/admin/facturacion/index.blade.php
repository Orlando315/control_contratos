@extends('layouts.app')

@section('title', 'Facturaciones')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Facturaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Facturaciones</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Facturaciones</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-tasks"></i> {{ count($facturaciones) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-tasks" aria-hidden="true"></i> Facturaciones</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.facturacion.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Facturación</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Factura ID</th>
                <th class="text-center">Cotización</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">Total</th>
                <th class="text-center">Creado</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($facturaciones as $facturacion)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $facturacion->sii_factura_id }}</td>
                  <td>{{ $facturacion->cotizacion->codigo() }}</td>
                  <td>{{ $facturacion->cotizacion->cliente->nombre }}</td>
                  <td class="text-right">{{ $facturacion->cotizacion->total() }}</td>
                  <td class="text-center">{{ $facturacion->created_at->format('d-m-Y H:i:s') }}</td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.facturacion.show', ['facturacion' => $facturacion->id]) }}"><i class="fa fa-search"></i></a>
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
