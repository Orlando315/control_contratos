@extends('layouts.app')

@section('title', 'Partida')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Partidas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.show', ['contrato' => $partida->contrato_id]) }}">Partidas</a></li>
        <li class="breadcrumb-item active"><strong>Partida</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-view')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.show', ['contrato' => $partida->contrato_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('partida-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.partida.edit', ['partida' => $partida->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('partida-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                @permission('contrato-view')
                  <a href="{{ route('admin.contratos.show', ['contrato' => $partida->contrato_id]) }}">
                    {{ $partida->contrato->nombre }}
                  </a>
                @else
                  {{ $partida->contrato->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{{ $partida->tipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Código</b>
              <span class="pull-right">{{ $partida->codigo }}</span>
            </li>
            <li class="list-group-item">
              <b>monto</b>
              <span class="pull-right">{{ $partida->monto() }}</span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($partida->descripcion)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $partida->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-content">
          <canvas id="myChart" width="200" height="100"></canvas>
        </div>
      </div>
    </div>
  </div><!-- .row -->

  <div class="tabs-container">
    <div class="collapsable-tabs">
      <ul class="nav nav-tabs">
        <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-plus-square"></i> Ordenes de Compra</a></li>
        <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-clipboard"></i> Facturas</a></li>
      </ul>
    </div>
    <div class="tab-content collapse show">
      <div id="tab-1" class="tab-pane active">
        <div class="panel-body">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Código</th>
                <th class="text-center">Proveedor</th>
                <th class="text-center">Total</th>
                <th class="text-center">Facturada</th>
                <th class="text-center">Creado</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($partida->compras as $compra)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $compra->codigo() }}</td>
                  <td>{{ $compra->proveedor->nombre }}</td>
                  <td class="text-right">{{ $compra->total() }}</td>
                  <td class="text-center"><small>{!! $compra->facturacionStatus() !!}</small></td>
                  <td class="text-center">{{ $compra->created_at->format('d-m-Y H:i:s') }}</td>
                  <td class="text-center">
                    @permission('compra-view|compra-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('compra-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.compra.show', ['compra' => $compra->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('compra-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.compra.edit', ['compra' => $compra->id]) }}">
                                <i class="fa fa-pencil"></i> Editar
                              </a>
                            </li>
                          @endpermission
                        </ul>
                      </div>
                    @endpermission
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div id="tab-2" class="tab-pane">
        <div class="panel-body">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Contrato</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Pago</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($partida->facturas as $factura)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $factura->contrato->nombre }}</td>
                  <td>{{ $factura->tipo() }}</td>
                  <td>{{ $factura->nombre }}</td>
                  <td class="text-right">{{ $factura->valor() }}</td>
                  <td class="text-center">{{ $factura->fecha }}</td>
                  <td class="text-center"><small>{!! $factura->pago() !!}</small></td>
                  <td class="text-center">
                    @permission('factura-view|factura-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('factura-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.facturas.show', ['factura' => $factura->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('factura-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.facturas.edit', ['factura' => $factura->id]) }}">
                                <i class="fa fa-pencil"></i> Editar
                              </a>
                            </li>
                          @endpermission
                        </ul>
                      </div>
                    @endpermission
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @permission('partida-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.partida.destroy', ['partida' => $partida->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Partida</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Partida?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection

@section('script')
  <!-- Charts.js -->
  <script type="text/javascript" src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
  <script type="text/javascript">
    var ctx = document.getElementById('myChart').getContext('2d');
    const totalCompras = @json($partida->totalCompras);
    const totalFacturas = @json($partida->totalFacturas);

    const data = {
      datasets: [
        {
          label: 'Ordenes de Compras',
          data: [totalCompras],
          backgroundColor: ['#a3e1d4'],
        },
        {
          label: 'Facturas',
          data: [totalFacturas],
          backgroundColor: ['#9ad0f5'],
        },
      ]
    };

    const config = {
      type: 'bar',
      data: data,
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        title: {
          display: true,
          text: '{{ $partida->codigo }} - {{ $partida->monto() }}',
        }
      },
    };

    $(document).ready(function () {
      var myChart = new Chart(ctx, config);
    });
  </script>
@endsection
