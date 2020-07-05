@extends('layouts.app')

@section('title', 'Anticipos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Anticipos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Anticipos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-level-up text-success"></i> {{ count($anticipos) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-level-up"></i> Anticipos</h5>
          
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('anticipos.individual') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Individual</a>
            <a class="btn btn-primary btn-xs" href="{{ route('anticipos.masivo') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Masivo</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Anticipo</th>
                <th class="text-center">Agregado</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($anticipos as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td><a href="{{ route('contratos.show', ['contrato' => $d->contrato->id]) }}">{{ $d->contrato->nombre }} </a></td>
                  <td><a href="{{ route('empleados.show', ['empleado' => $d->empleado->id]) }}">{{ $d->empleado->usuario->nombres }} {{ $d->empleado->usuario->apellidos }}</a></td>
                  <td>{{ $d->fecha }}</td>
                  <td>{{ $d->anticipo() }}</td>
                  <td>{{ optional($d->created_at)->format('d-m-Y H:i:s') }}</td>
                  <td>
                    <a class="btn btn-success btn-xs" href="{{ route('anticipos.show', ['anticipo' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('anticipos.edit', ['anticipo' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
