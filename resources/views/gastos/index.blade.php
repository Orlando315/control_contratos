@extends('layouts.app')
@section('title','Gastos - '.config('app.name'))
@section('header','Gastos')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Gastos</li>
  </ol>
@endsection

@section('content')
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-credit-card"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Gastos</span>
          <span class="info-box-number">{{ count($gastos) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-credit-card"></i> Gastos</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('gastos.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Gasto</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Etiqueta</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($gastos as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td><a href="{{ route('contratos.show', ['id', $d->contrato_id]) }}">{{ $d->contrato->nombre }}</a></td>
                  <td><a href="{{ route('etiquetas.show', ['id', $d->etiqueta_id]) }}">{{ $d->etiqueta->etiqueta }}</a></td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->valor() }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('gastos.show', ['id' => $d->id]) }}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('gastos.edit', ['id' => $d->id]) }}"><i class="fa fa-pencil"></i></a>
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
