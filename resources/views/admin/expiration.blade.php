@extends('layouts.app')

@section('title', ucfirst($type))

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>{{ ucfirst($type) }}</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Vencimiento</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div id="porvencer" class="row mb-3">
    <div class="col-md-12">
      <h1 class="text-center">{{ ucfirst($type) }}</h1>
      <p class="text-center text-muted">Contratos / Documentos por vencer ({{ $days }} días o menos)</p>
    </div>
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
          @if($type != 'transportes')
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab" aria-expanded="true"><i class="fa fa-clipboard"></i> Contratos</a></li>
          @endif
          <li><a class="nav-link{{ $type == 'transportes' ? ' active' : '' }}" href="#tab-2" data-toggle="tab" aria-expanded="{{ $type == 'transportes' ? 'true' : 'false' }}"><i class="fa fa-clone"></i> Documentos</a></li>
        </ul>
        <div class="tab-content">
          @if($type != 'transportes')
            <div class="tab-pane active" id="tab-1" role="tabpanel" aria-labelledby="contratos-tab">
              <div class="panel-body">
                @if($type == 'contratos')
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Empleados</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($contratosPorVencer as $contrato)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $contrato->nombre }}</td>
                          <td>{{ $contrato->inicio }}</td>
                          <td>{{ $contrato->fin }}</td>
                          <td>{{ $contrato->valor() }}</td>
                          <td>{{ $contrato->empleados()->count() }}</td>
                          <td>
                            @permission('contrato-view')
                              <a class="btn btn-success btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-search"></i></a>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                @else
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Empleado</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Jornada</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($contratosPorVencer as $contrato)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $contrato->empleado->nombre() }}</td>
                          <td>{{ $contrato->inicio }}</td>
                          <td>{{ $contrato->fin }}</td>
                          <td>{{ $contrato->jornada }}</td>
                          <td>
                            @permission('empleado-view')
                              <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $contrato->empleado_id]) }}"><i class="fa fa-search"></i></a>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                @endif
              </div>
            </div>
          @endif
          <div class="tab-pane{{ $type == 'transportes' ? ' active' : '' }}" id="tab-2" role="tabpanel" aria-labelledby="documentos-tab">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">
                      @if($type == 'contratos')
                        Contrato
                      @elseif($type == 'empleados')
                        Empleado
                      @else
                        Vehículo (Patente)
                      @endif
                    </th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Vencimiento</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($documentosPorVencer as $documento)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        @if($type == 'contratos')
                          {{ $documento->documentable->nombre }}
                        @elseif($type == 'empleados')
                          {{ $documento->documentable->usuario->nombre() }}
                        @else
                          {{ $documento->documentable->vehiculo }} ({{ $documento->documentable->patente }}) 
                        @endif
                      </td>
                      <td>{{ $documento->nombre }}</td>
                      <td>{{ $documento->vencimiento }}</td>
                      <td>
                        @if($type == 'contratos')
                          @permission('contrato-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $documento->documentable_id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
                        @elseif($type == 'empleados')
                          @permission('empleado-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $documento->documentable_id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
                        @else
                          @permission('transporte-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.transportes.show', ['transporte' => $documento->documentable_id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
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
    </div>
  </div>

  <div id="vencidos" class="row">
    <div class="col-md-12">
      <h1 class="text-center">Vencidos</h1>
      <p class="text-center text-muted">Contratos / Documentos vencidos</p>
    </div>
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
          @if($type != 'transportes')
            <li><a class="nav-link active" href="#tab-11" data-toggle="tab" aria-expanded="true"><i class="fa fa-clipboard"></i> Contratos</a></li>
          @endif
          <li><a class="nav-link{{ $type == 'transportes' ? ' active' : '' }}" href="#tab-22" data-toggle="tab" aria-expanded="{{ $type == 'transportes' ? 'true' : 'false' }}"><i class="fa fa-clone"></i> Documentos</a></li>
        </ul>
        <div class="tab-content">
          @if($type != 'transportes')
            <div class="tab-pane active" id="tab-11" role="tabpanel" aria-labelledby="contratos-tab">
              <div class="panel-body">
                @if($type == 'contratos')
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Empleados</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($contratosVencidos as $contratoVencido)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $contratoVencido->nombre }}</td>
                          <td>{{ $contratoVencido->inicio }}</td>
                          <td>{{ $contratoVencido->fin }}</td>
                          <td>{{ $contratoVencido->valor() }}</td>
                          <td>{{ $contratoVencido->empleados()->count() }}</td>
                          <td>
                            @permission('contrato-view')
                              <a class="btn btn-success btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $contratoVencido->id]) }}"><i class="fa fa-search"></i></a>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                @else
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Empleado</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Fin</th>
                        <th class="text-center">Jornada</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($contratosVencidos as $contratoVencido)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $contratoVencido->empleado->nombre() }}</td>
                          <td>{{ $contratoVencido->inicio }}</td>
                          <td>{{ $contratoVencido->fin }}</td>
                          <td>{{ $contratoVencido->jornada }}</td>
                          <td>
                            @permission('empleado-view')
                              <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $contratoVencido->empleado_id]) }}"><i class="fa fa-search"></i></a>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                @endif
              </div>
            </div>
          @endif
          <div class="tab-pane{{ $type == 'transportes' ? ' active' : '' }}" id="tab-22" role="tabpanel" aria-labelledby="documentos-tab">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">
                      @if($type == 'contratos')
                        Contrato
                      @elseif($type == 'empleados')
                        Empleado
                      @else
                        Vehículo (Patente)
                      @endif
                    </th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Vencimiento</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($documentosVencidos as $documentoVencido)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        @if($type == 'contratos')
                          {{ $documentoVencido->documentable->nombre }}
                        @elseif($type == 'empleados')
                          {{ $documentoVencido->documentable->usuario->nombre() }}
                        @else
                          {{ $documentoVencido->documentable->vehiculo }} ({{ $documentoVencido->documentable->patente }}) 
                        @endif
                      </td>
                      <td>{{ $documentoVencido->nombre }}</td>
                      <td>{{ $documentoVencido->vencimiento }}</td>
                      <td>
                        @if($type == 'contratos')
                          @permission('contrato-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $documentoVencido->documentable_id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
                        @elseif($type == 'empleados')
                          @permission('empleado-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.empleados.show', ['empleado' => $documentoVencido->documentable_id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
                        @else
                          @permission('transporte-view')
                            <a class="btn btn-success btn-xs" href="{{ route('admin.transportes.show', ['transporte' => $documentoVencido->documentable_id]) }}"><i class="fa fa-search"></i></a>
                          @endpermission
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
    </div>
  </div>
@endsection
