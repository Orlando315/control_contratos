@extends('layouts.app')

@section('title', 'Sueldo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Sueldos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Sueldo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ url()->previous() }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Mes pagado</b>
              <span class="pull-right">{{ $sueldo->mesPagado() }}</span>
            </li>
            <li class="list-group-item">
              <b>Alcance liquido</b>
              <span class="pull-right">{{ $sueldo->alcanceLiquido() }}</span>
            </li>
            <li class="list-group-item">
              <b>Asistencias</b>
              <span class="pull-right">{{ $sueldo->asistencias }}</span>
            </li>
            <li class="list-group-item">
              <b>Anticipo</b>
              <span class="pull-right">{{ $sueldo->anticipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Bono de reemplazo</b>
              <span class="pull-right"> {{ $sueldo->bonoReemplazo() }} </span>
            </li>
            <li class="list-group-item">
              <b>Sueldo liquido</b>
              <span class="pull-right"> {{ $sueldo->sueldoLiquido() }} </span>
            </li>
            <li class="list-group-item">
              <b>Adjunto</b>
              <span class="pull-right">
                @if($sueldo->adjunto)
                  <a href="{{ $sueldo->download }}">Descargar</a>
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Recibido</b>
              <span class="pull-right">{!! $sueldo->recibido() !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $sueldo->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
@endsection
