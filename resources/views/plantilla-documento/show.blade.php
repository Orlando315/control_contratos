@extends('layouts.app')

@section('title', 'Documento')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('perfil') }}">Documentos</a></li>
        <li class="breadcrumb-item active"><strong>Documento</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('perfil') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('plantilla.documento.pdf', ['documento' => $documento->id]) }}"><i class="fa fa fa-file-pdf-o" aria-hidden="true"></i> Descargar</a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">@nullablestring($documento->nombre)</span>
            </li>
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                {{ $documento->contrato->nombre }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Empleado</b>
              <span class="pull-right">
                {{ $documento->empleado->nombre() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Plantilla</b>
              <span class="pull-right">
                {{ $documento->plantilla->nombre }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Padre</b>
              <span class="pull-right">
                @nullablestring(optional($documento->padre)->nombre)
              </span>
            </li>
            <li class="list-group-item">
              <b>Caducidad</b>
              <span class="pull-right">@nullablestring(optional($documento->caducidad)->format('d-m-Y'))</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $documento->created_at->format('d-m-Y H:i:s') }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      @foreach($documento->plantilla->secciones as $seccion)
        <div class="ibox">
          <div class="ibox-title">
            <h5>Sección #{{ $loop->iteration }}: @nullablestring($seccion->nombre)</h5>
          </div>
          <div class="ibox-content">
            {!! $documento->fillSeccionVariables($seccion) !!}
          </div><!-- /.box-body -->
        </div>
      @endforeach
    </div>
  </div>
@endsection
