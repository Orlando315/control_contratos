@extends('layouts.app')
@section('title','Etiquetas - '.config('app.name'))
@section('header','Etiquetas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Etiquetas</li>
  </ol>
@endsection

@section('content')
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-tags"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Etiquetas</span>
          <span class="info-box-number">{{ count($etiquetas) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-tags"></i> Etiquetas</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('etiquetas.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Etiqueta</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Etiqueta</th>
                <th class="text-center">Facturas</th>
                <th class="text-center">Gastos</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($etiquetas as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $d->etiqueta }}</td>
                  <td>{{ $d->facturas->count() }}</td>
                  <td>{{ $d->gastos->count() }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('etiquetas.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('etiquetas.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
