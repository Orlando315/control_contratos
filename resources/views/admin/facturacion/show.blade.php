@extends('layouts.app')

@section('title', 'Facturaciones')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Facturaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.facturacion.index') }}">Facturaciones</a></li>
        <li class="breadcrumb-item active"><strong>Facturación</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.facturacion.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Información</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Total</b>
              <span class="pull-right">
                {{ $facturacion->cotizacion->total() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Factura Sii ID</b>
              <span class="pull-right">
                {{ $facturacion->sii_factura_id }}
              </span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">
                {{ $facturacion->rut }}
              </span>
            </li>
            <li class="list-group-item">
              <b>DV</b>
              <span class="pull-right">
                {{ $facturacion->dv }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Firma</b>
              <span class="pull-right">
                {{ $facturacion->firma }}
              </span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $facturacion->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Cotización</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Código</b>
              <span class="pull-right">
                <a href="{{ route('admin.cotizacion.show', ['cotizacion' => $facturacion->cotizacion_id]) }}">
                  {{ $facturacion->cotizacion->codigo() }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Cliente</b>
              <span class="pull-right">
                <a href="{{ route('admin.cliente.show', ['cliente' => $facturacion->cotizacion->cliente_id]) }}">
                  {{ $facturacion->cotizacion->cliente->nombre }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Total</b>
              <span class="pull-right">{{ $facturacion->cotizacion->total() }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $facturacion->cotizacion->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
@endsection
