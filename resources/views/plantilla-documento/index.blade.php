@extends('layouts.app')
@section('title','Documentos - '.config('app.name'))
@section('header','Documentos')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Documentos</li>
  </ol>
@endsection

@section('content')
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-file-text-o"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Documentos</span>
          <span class="info-box-number">{{ count($documentos) }}</span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header">
          <h3 class="box-title text-right m-0" style="margin-bottom: 10px">
            Documento
          </h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('plantilla.documento.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo documento</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Documento</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Padre</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($documentos as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->contrato->nombre }}</td>
                  <td>{{ $d->empleado->nombre() }}</td>
                  <td>{{ $d->padre ? $d->padre->nombre : 'N/A' }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('plantilla.documento.show', ['documento' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('plantilla.documento.edit', ['documento' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
