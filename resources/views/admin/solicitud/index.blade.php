@extends('layouts.app')

@section('title', 'Solicitudes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Solicitudes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Solicitudes</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Solicitudes</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-archive"></i> {{ count($solicitudes) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-archive"></i> Solicitudes</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Estatus</th>
                <th class="text-center">Adjunto</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($solicitudes as $solicitud)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $solicitud->tipo() }}</td>
                  <td>@nullablestring($solicitud->descripcion)</td>
                  <td>{!! $solicitud->status() !!}</td>
                  <td>
                    @if($solicitud->adjunto)
                      <a href="{{ $solicitud->download }}" title="Descargar adjunto">Descargar</a>
                    @else
                      @nullablestring(null)
                    @endif
                  </td>
                  <td>
                    <a class="btn btn-success btn-xs" href="{{ route('admin.solicitud.show', ['solicitud' => $solicitud->id] )}}"><i class="fa fa-search"></i></a>
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
