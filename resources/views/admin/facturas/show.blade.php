@extends('layouts.app')

@section('title', 'Factura')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Facturas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.facturas.index') }}">Facturas</a></li>
        <li class="breadcrumb-item active"><strong>Factura</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.facturas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.facturas.edit', ['factura' => $factura->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                <a href="{{ route('admin.contratos.show', ['contrato' => $factura->contrato->id]) }}">
                  {{ $factura->contrato->nombre }}
                </a>
              </span>
            </li>
            @if($factura->etiqueta)
              <li class="list-group-item">
                <b>Etiqueta</b>
                <span class="pull-right">
                  @if(Auth::user()->tipo <= 2)
                    <a href="{{ route('admin.etiquetas.show', ['etiqueta' => $factura->etiqueta_id]) }}">
                      {{ $factura->etiqueta->etiqueta }}
                    </a>
                  @else
                    {{ $factura->etiqueta->etiqueta }}
                  @endif
                </span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{{ $factura->tipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $factura->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Realizada por</b>
              <span class="pull-right">{{ $factura->realizada_por }}</span>
            </li>
            <li class="list-group-item">
              <b>Realizada para</b>
              <span class="pull-right">{{ $factura->realizada_para }}</span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right"> {{ $factura->fecha }} </span>
            </li>
            <li class="list-group-item">
              <b>Valor</b>
              <span class="pull-right">{{ $factura->valor() }}</span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right"> {{ $factura->pago_fecha }} </span>
            </li>
            <li class="list-group-item">
              <b>Pago</b>
              <span class="pull-right"> {!! $factura->pago() !!} </span>
            </li>
            <li class="list-group-item">
              <b>Adjunto #1</b>
              <span class="pull-right">{!! $factura->adjunto(1) !!}</span>
            </li>
            <li class="list-group-item">
              <b>Adjunto #2</b>
              <span class="pull-right">{!! $factura->adjunto(2) !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $factura->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.facturas.destroy', [$factura->id]) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Factura</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Factura?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
