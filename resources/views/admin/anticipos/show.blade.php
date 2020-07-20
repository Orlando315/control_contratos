@extends('layouts.app')

@section('title', 'Anticipo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.anticipos.index') }}">Anticipos</a></li>
        <li class="breadcrumb-item active"><strong>Anticipo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.anticipos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.anticipos.edit', ['anticipo' => $anticipo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                <a href="{{ route('admin.contratos.show', ['contrato' => $anticipo->contrato->id]) }}">
                  {{ $anticipo->contrato->nombre }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Empleado</b>
              <span class="pull-right">
                <a href="{{ route('admin.empleados.show', ['empleado' => $anticipo->empleado_id]) }}">
                  {{ $anticipo->empleado->usuario->nombres }} {{ $anticipo->empleado->usuario->apellidos }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right">{{ $anticipo->fecha }}</span>
            </li>
            <li class="list-group-item">
              <b>Anticipo</b>
              <span class="pull-right"> {{ $anticipo->anticipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Bono</b>
              <span class="pull-right"> {{ $anticipo->bono() }}</span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right"> {{ $anticipo->descripcion ?? 'N/A' }}</span>
            </li>
            <li class="list-group-item">
              <b>Adjunto</b>
              <span class="pull-right">
                @if($anticipo->adjunto)
                  <a href="{{ $anticipo->adjunto_download }}" title="Descargar adjunto">Descargar</a>
                @else
                  N/A
                @endif
              </span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $anticipo->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  
  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.anticipos.destroy', ['anticipo' => $anticipo->id]) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Anticipo</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Anticipo?</h4>
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
