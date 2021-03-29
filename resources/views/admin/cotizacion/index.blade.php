@extends('layouts.app')

@section('title', 'Cotizaciones')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Cotizaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Cotizaciones</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Cotizaciones</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-calculator"></i> {{ count($cotizaciones) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-calculator" aria-hidden="true"></i> Cotizaciones</h5>
          <div class="ibox-tools">
            @permission('cotizacion-create')
              <a class="btn btn-primary btn-xs" href="{{ route('admin.cotizacion.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Cotización</a>
            @endpermission
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Código</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">Total</th>
                <th class="text-center">Facturada</th>
                <th class="text-center">Creado</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cotizaciones as $cotizacion)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ $cotizacion->codigo() }}</td>
                  <td>{{ $cotizacion->cliente->nombre }}</td>
                  <td class="text-right">{{ $cotizacion->total() }}</td>
                  <td class="text-center"><small>{!! $cotizacion->facturacionStatus() !!}</small></td>
                  <td class="text-center">{{ $cotizacion->created_at->format('d-m-Y H:i:s') }}</td>
                  <td class="text-center">
                    @permission('cotizacion-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.cotizacion.show', ['cotizacion' => $cotizacion->id]) }}"><i class="fa fa-search"></i></a>
                    @endpermission
                    @permission('cotizacion-edit')
                      <a class="btn btn-primary btn-xs" href="{{ route('admin.cotizacion.edit', ['cotizacion' => $cotizacion->id]) }}"><i class="fa fa-pencil"></i></a>
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
