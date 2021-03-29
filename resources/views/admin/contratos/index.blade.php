@extends('layouts.app')

@section('title', 'Contratos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
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
          <h2><i class="fa fa-clipboard"></i> {{ count($contratos) }}</h2>
        </div>
      </div>
    </div>
  </div>


  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          @permission('contrato-index')
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-clipboard"></i> Contratos</a></li>
          @endpermission
          @permission('faena-index')
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-file-text-o"></i> Faenas</a></li>
          @endpermission
          @permission('centro-costo-index')
            <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-bank"></i> Centros de costo</a></li>
          @endpermission
        </ul>
        <div class="tab-content">
          @permission('contrato-index')
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                @permission('contrato-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.contratos.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Contrato</a>
                  </div>
                @endpermission

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
                    @foreach($contratos as $contrato)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contrato->nombre }}</td>
                        <td>@nullablestring($contrato->descripcion)</td>
                        <td>{{ $contrato->inicio }}</td>
                        <td>{{ $contrato->fin }}</td>
                        <td class="text-right">{{ $contrato->valor() }}</td>
                        <td class="text-right">{{ $contrato->empleados_count }}</td>
                        <td>
                          @permission('contrato-view')
                            <a class="btn btn-success btn-flat btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id] )}}"><i class="fa fa-search"></i></a>
                          @endpermission
                          @permission('contrato-edit')
                            <a class="btn btn-primary btn-flat btn-xs" href="{{ route('admin.contratos.edit', ['contrato' => $contrato->id] )}}"><i class="fa fa-pencil"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
          @permission('faena-index')
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                @permission('faena-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.faena.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Faena</a>
                  </div>
                @endpermission

                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Faena</th>
                      <th class="text-center">Contratos</th>
                      <th class="text-center">Transportes</th>
                      <th class="text-center">Egresos (Inventarios V2)</th>
                      <th class="text-center">Requerimiento de Materiales</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($faenas as $faena)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $faena->nombre }}</td>
                        <td class="text-right">{{ $faena->contratos_count }}</td>
                        <td class="text-right">{{ $faena->transportes_count }}</td>
                        <td class="text-right">{{ $faena->inventarios_v2_egreso_count }}</td>
                        <td class="text-right">{{ $faena->requerimientos_materiales_count }}</td>
                        <td>
                          @permission('faena-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.faena.show', ['faena' => $faena->id] )}}"><i class="fa fa-search"></i></a>
                          @endpermission
                          @permission('faena-edit')
                            <a class="btn btn-primary btn-xs" href="{{ route('admin.faena.edit', ['faena' => $faena->id] )}}"><i class="fa fa-pencil"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
          @permission('centro-costo-index')
            <div id="tab-3" class="tab-pane">
              <div class="panel-body">
                @permission('centro-costo-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.centro.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Centro de costo</a>
                  </div>
                @endpermission

                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Egresos (Inventarios V2)</th>
                      <th class="text-center">Requerimiento de Materiales</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($centros as $centro)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $centro->nombre }}</td>
                        <td class="text-right">{{ $centro->inventarios_v2_egreso_count }}</td>
                        <td class="text-right">{{ $centro->requerimientos_materiales_count }}</td>
                        <td>
                          @permission('centro-costo-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.centro.show', ['centro' => $centro->id] )}}"><i class="fa fa-search"></i></a>
                          @endpermission
                          @permission('centro-costo-edit')
                            <a class="btn btn-primary btn-xs" href="{{ route('admin.centro.edit', ['centro' => $centro->id] )}}"><i class="fa fa-pencil"></i></a>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
        </div>
      </div>
    </div>
  </div>
@endsection
