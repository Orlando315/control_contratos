@extends('layouts.app')

@section('title', 'Inventarios')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Inventarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Inventarios</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Inventarios</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-cubes text-danger"></i> {{ count($inventarios) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-cubes"></i> Inventarios</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('inventarios.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Inventario</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($inventarios as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <a href="{{ route('contratos.show', ['contrato' => $d->contrato_id]) }}">{{ $d->contrato->nombre }}</a>
                  </td>
                  <td>{{ $d->tipo() }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->valor() }}</td>
                  <td>{{ $d->fecha }}</td>
                  <td>{{ $d->cantidad() }}</td>
                  <td>
                    <a class="btn btn-success btn-xs" href="{{ route('inventarios.show', ['inventario' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    @if(Auth::user()->tipo <= 2 || $d->tipo == 3)
                      <a class="btn btn-primary btn-xs" href="{{ route('inventarios.edit', ['inventario' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                    @endif
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
