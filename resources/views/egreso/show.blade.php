@extends('layouts.app')

@section('title', 'Egreso de Stock')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Egreso de Stock</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Inventarios V2</li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Egreso de Stock</a></li>
        <li class="breadcrumb-item active"><strong>Egreso de Stock</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('inventario.egreso.pdf', ['egreso' => $egreso->id]) }}"><i class="fa fa-file-pdf-o"></i> Descargar</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        @if($egreso->foto)
          <div class="ibox-content no-padding text-center border-left-right">
            <img class="img-fluid" src="{{ $egreso->foto_url }}" alt="Foto" style="max-height: 180px;margin: 0 auto;">
          </div>
        @endif
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Inventario</b>
              <span class="pull-right">
                {{ $egreso->inventario->nombre }}
              </span>
            </li>
            @if($egreso->isUser())
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  @nullablestring(optional($egreso->user)->nombre())
                </span>
              </li>
            @endif
            @if($egreso->isCliente())
              <li class="list-group-item">
                <b>Cliente</b>
                <span class="pull-right">
                  @nullablestring(optional($egreso->cliente)->nombre)
                </span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                @nullablestring(optional($egreso->contrato)->nombre)
              </span>
            </li>
            <li class="list-group-item">
              <b>Faena</b>
              <span class="pull-right">
                @nullablestring(optional($egreso->faena)->nombre)
              </span>
            </li>
            <li class="list-group-item">
              <b>Centro de costo</b>
              <span class="pull-right">
                @nullablestring(optional($egreso->centroCosto)->nombre)
              </span>
            </li>
            <li class="list-group-item">
              <b>Cantidad</b>
              <span class="pull-right">{{ $egreso->cantidad() }}</span>
            </li>
            <li class="list-group-item">
              <b>Costo</b>
              <span class="pull-right">
                @if($egreso->costo)
                  {{ $egreso->costo() }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($egreso->descripcion)</span>
            </li>
            @if($egreso->isUser())
              <li class="list-group-item">
                <b>Recibido</b>
                <span class="pull-right">{!! $egreso->recibido() !!}</span>
              </li>
            @endif
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $egreso->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
@endsection
