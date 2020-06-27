@extends('layouts.app')

@section('title', 'Documentos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Documentos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Documentos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-file-text-o text-warning"></i> {{ count($documentos) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-file-text-o"></i> Documento</h5>

          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('plantilla.documento.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Documento</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
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
                    <a class="btn btn-success btn-xs" href="{{ route('plantilla.documento.show', ['documento' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('plantilla.documento.edit', ['documento' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
