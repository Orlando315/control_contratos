@extends('layouts.app')

@section('title', 'Contratos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Contratos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Contratos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-clipboard text-warning"></i> {{ count($contratos) }}</h2>
        </div>
      </div>
    </div>
  </div>


  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-clipboard"></i> Contratos</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-file-text-o"></i> Faenas</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              @if(Auth::user()->tipo < 2)
                <div class="mb-3 text-right">
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.contratos.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Contrato</a>
                </div>
              @endif

              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Inicio</th>
                    <th class="text-center">Fin</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Empleados</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($contratos as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->nombre }}</td>
                      <td>{{ $d->descripcion ?? 'N/A' }}</td>
                      <td>{{ $d->inicio }}</td>
                      <td>{{ $d->fin }}</td>
                      <td>{{ $d->valor() }}</td>
                      <td>{{ $d->empleados->count() }}</td>
                      <td>
                        <a class="btn btn-success btn-flat btn-xs" href="{{ route('admin.contratos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                        @if(Auth::user()->tipo < 2)
                          <a class="btn btn-primary btn-flat btn-xs" href="{{ route('admin.contratos.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              @if(Auth::user()->tipo < 2)
                <div class="mb-3 text-right">
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.faena.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Faena</a>
                </div>
              @endif

              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Faena</th>
                    <th class="text-center">Contratos</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($faenas as $faena)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $faena->nombre }}</td>
                      <td>{{ $faena->contratos()->count() }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.faena.show', ['faena' => $faena->id] )}}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.faena.edit', ['faena' => $faena->id] )}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
