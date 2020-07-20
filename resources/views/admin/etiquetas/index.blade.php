@extends('layouts.app')

@section('title','Etiquetas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Etiquetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Etiquetas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Etiquetas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-tags text-warning"></i> {{ count($etiquetas) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-tags"></i> Etiquetas</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.etiquetas.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Etiqueta</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
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
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $d->etiqueta }}</td>
                  <td>{{ $d->facturas->count() }}</td>
                  <td>{{ $d->gastos->count() }}</td>
                  <td>
                    <a class="btn btn-success btn-xs" href="{{ route('admin.etiquetas.show', ['etiqueta' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.etiquetas.edit', ['etiqueta' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
