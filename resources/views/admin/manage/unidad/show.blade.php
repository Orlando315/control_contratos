@extends('layouts.app')

@section('title', 'Unidad')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Unidades</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.unidad.index') }}">Unidades</a></li>
        <li class="breadcrumb-item active"><strong>Unidad</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.manage.unidad.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.manage.unidad.edit', ['unidad' => $unidad->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @if(!$unidad->isPredeterminada())
        <button class="btn btn-default btn-sm" type="button" data-toggle="modal" data-target="#statusModal">
          <i class="fa fa-check-circle-o"></i> Establecer como predeterminada
        </button>
      @endif
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $unidad->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Inventarios V2</b>
              <span class="pull-right">{{ $unidad->inventarios_v2_count }}</span>
            </li>
            <li class="list-group-item">
              <b>Predeterminada</b>
              <span class="pull-right">{!! $unidad->isPredeterminada(false) !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $unidad->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div><!-- .row -->

  @if(!$unidad->isPredeterminada())
    <div id="statusModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="status-modal-form" action="{{ route('admin.manage.unidad.status', ['unidad' => $unidad->id]) }}" method="POST">
            <input id="status-modal-value" type="hidden" name="status">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="statusModalLabel">Predeterminada</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de establecer como predeterminada esta Unidad?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.manage.unidad.destroy', ['unidad' => $unidad->id]) }}" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Unidad</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Unidad?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
