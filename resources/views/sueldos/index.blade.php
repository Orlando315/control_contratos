@extends('layouts.app')

@section('title', 'Sueldos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Sueldos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">Contratos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}">Contrato</a></li>
        <li class="breadcrumb-item active"><strong>Sueldos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>

  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Sueldos</h5>
        </div>
        <div class="ibox-content">
          <h2>
            <i class="fa fa-money text-primary"></i> {{ count($sueldos) }}
          </h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-money"></i> Sueldos</h5>

          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('sueldos.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Realizar Pagos</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Alcance líquido</th>
                <th class="text-center">Sueldo líquido</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($sueldos as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $d->mesPagado() }}</td>
                  <td>
                    <a href="{{ route('empleados.show', ['empleado' => $d->empleado_id]) }}">
                      {{ $d->nombreEmpleado() }}
                    </a>
                  </td>
                  <td class="text-right">{{ $d->alcanceLiquido() }}</td>
                  <td class="text-right">{{ $d->sueldoLiquido() }}</td>
                  <td>
                    <a class="btn btn-success btn-xs" href="{{ route('sueldos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
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
