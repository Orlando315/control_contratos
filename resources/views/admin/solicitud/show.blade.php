@extends('layouts.app')

@section('title', 'Solicitud')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Solicitudes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.solicitud.index') }}">Solicitudes</a></li>
        <li class="breadcrumb-item active"><strong>Solicitud</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.solicitud.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.solicitud.edit', ['solicitud' => $solicitud->id]) }}"><i class="fa {{ !$solicitud->isPendiente() ? 'fa-pencil' : 'fa-share' }}" aria-hidden="true"></i> {{ !$solicitud->isPendiente() ? 'Editar' : 'Responder solicitud' }}</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{{ $solicitud->tipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">{{ $solicitud->descripcion ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Adjunto</b>
              <span class="pull-right">
                @if($solicitud->adjunto)
                  <a href="{{ $solicitud->download }}" title="Descargar adjunto">Descargar</a>
                @else
                  N/A
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Observación</b>
              <span class="pull-right">{{ $solicitud->observacion ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Estatus</b>
              <span class="pull-right">{!! $solicitud->status() !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $solicitud->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.solicitud.destroy', ['solicitud' => $solicitud->id]) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Solicitud</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Solicitud?</h4>
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
