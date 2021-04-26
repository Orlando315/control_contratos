@extends('layouts.app')

@section('title', 'Partida')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Partidas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.show', ['contrato' => $contrato->id]) }}">Partidas</a></li>
        <li class="breadcrumb-item active"><strong>Tipo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-view')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{{ ucfirst($tipo) }}</span>
            </li>
            <li class="list-group-item">
              <b>Monto</b>
              <span class="pull-right">{{ number_format($monto, 2, ',', '.') }}</span>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-content">
          <div class="row">
            <div class="col">
              <canvas id="myChart" width="200" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- .row -->

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-ellipsis-v" aria-hidden="true"></i> Partidas</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Tipo</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($partidas as $partida)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $partida->tipo() }}</td>
                  <td>{{ $partida->codigo }}</td>
                  <td>@nullablestring($partida->descripcion)</td>
                  <td class="text-right">{{ $partida->monto() }}</td>
                  <td class="text-center">
                    @permission('partida-view|partida-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('partida-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.partida.show', ['partida' => $partida->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('partida-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.partida.edit', ['partida' => $partida->id]) }}">
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
@endsection

@section('script')
  <!-- Charts.js -->
  <script type="text/javascript" src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
  <script type="text/javascript">
    const partidas = @json($partidas);
    const colors = [
      '#4bc0c0',
      '#36a2eb',
      '#ff6384',
      '#ff9f40',
      '#ffcd56',
      '#23c6c8'
    ]
    let chartData = {
      labels: [],
      datasets: [],
      colors: [],
    }

    $.each(partidas, function (k, partida){
      chartData.labels.push(partida.codigo);
      chartData.datasets.push(partida.monto);
      chartData.colors.push(colors[k]);
    })

    $(document).ready(function () {
      var chartCanvas = document.getElementById('myChart').getContext('2d');
      const config = {
        type: 'pie',
        data: {
          labels: chartData.labels,
          datasets: [{
            data: chartData.datasets,
            backgroundColor: chartData.colors
          }],
        },
        options: {
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Chart.js Pie Chart'
            }
          }
        }
      };
      
      new Chart(chartCanvas, config);
    });
  </script>
@endsection
