@extends('layouts.app')

@section('title', 'Anticipos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Anticipos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6 text-center text-md-right">
      <h3 class="my-2">Información del año: {{ $actualYear }}</h3>
    </div>
    <div class="col-md-6 text-center text-md-left">
      <form id="form-years" action="{{ route('admin.anticipo.index') }}">
        <div class="form-group">
          <select id="select-years" class="custom-select form-control-sm" name="year" style="max-width: 100px">
            <option value="">Seleccione</option>
            @foreach($allYears as $year)
              <option value="{{ $year }}"{{ $year == $actualYear ? ' selected' : '' }}>{{ $year }}</option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab">Series</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab">Anticipos</a></li>
          <li><a class="nav-link" href="#tab-3" data-toggle="tab">Solicitudes</a></li>
          <li><a class="nav-link" href="#tab-4" data-toggle="tab">Rechazados</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-1">
            <div class="panel-body">
              <div class="mb-3 text-right">
                @permission('anticipo-create')
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.anticipo.masivo') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Masivo</a>
                @endpermission
              </div>

              <div class="accordion" id="accordion-anticipos-series">
                @forelse($monthlyGroupedSeries as $month)
                  <div class="card">
                    <div class="card-header p-0" id="heading-series-{{ $month->month }}">
                      <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse-series-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-series-{{ $month->month }}">
                        {{ $month->title }} ({{ $month->series->count() }})
                      </button>
                    </div>
                    <div id="collapse-series-{{ $month->month }}" class="collapse" aria-labelledby="heading-series-{{ $month->month }}" data-parent="#accordion-anticipos-series">
                      <div class="card-body">
                        <table class="table data-table table-bordered table-hover table-sm w-100">
                          <thead>
                            <tr class="text-center">
                              <th>#</th>
                              <th>Serie</th>
                              <th>Contrato</th>
                              <th>Fecha</th>
                              <th>Anticipo</th>
                              <th>Bono</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($month->series as $serie)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $serie->serie }}</td>
                                <td>{{ $serie->contrato->nombre }}</td>
                                <td>{{ $serie->fecha }}</td>
                                <td class="text-right">{{ $serie->anticipo() }}</td>
                                <td class="text-right">{{ $serie->bono() }}</td>
                                <td class="text-center">
                                  @permission('anticipo-index')
                                    <a class="btn btn-success btn-xs" href="{{ route('admin.anticipo.show.serie', ['serie' => $serie->serie]) }}"><i class="fa fa-search"></i></a>
                                  @endpermission
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <th class="text-right">
                                {{ number_format($month->series->sum('anticipo'), 2, '.', ',') }}
                              </th>
                              <th class="text-right">
                                {{ number_format($month->series->sum('bono'), 2, '.', ',') }}
                              </th>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                @empty
                  <h3 class="text-center text-muted my-3">No hay resultados</h3>
                @endforelse
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-2">
            <div class="panel-body">
              <div class="mb-3 text-right">
                @permission('anticipo-create')
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.anticipo.individual') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Individual</a>
                @endpermission
              </div>

              <div class="accordion" id="accordion-anticipos-aprobados">
                @forelse($monthlyGroupedAprobados as $month)
                  <div class="card">
                    <div class="card-header p-0" id="heading-aprobados-{{ $month->month }}">
                      <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse-aprobados-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-aprobados-{{ $month->month }}">
                        {{ $month->title }} ({{ $month->anticipos->count() }})
                      </button>
                    </div>
                    <div id="collapse-aprobados-{{ $month->month }}" class="collapse" aria-labelledby="heading-aprobados-{{ $month->month }}" data-parent="#accordion-anticipos-aprobados">
                      <div class="card-body">
                        <table class="table data-table table-bordered table-hover table-sm w-100">
                          <thead>
                            <tr class="text-center">
                              <th>#</th>
                              <th>Solicitud</th>
                              <th>Contrato</th>
                              <th>Empleado</th>
                              <th>Fecha</th>
                              <th>Anticipo</th>
                              <th>Bono</th>
                              <th>Agregado</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($month->anticipos as $aprobado)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center" title="Si el Empleado solicito o no el Anticipo"><small>{!! $aprobado->solicitud() !!}</small></td>
                                <td>
                                  @permission('contrato-view')
                                    <a href="{{ route('admin.contrato.show', ['contrato' => $aprobado->contrato->id]) }}">
                                      {{ $aprobado->contrato->nombre }}
                                    </a>
                                  @else
                                    {{ $aprobado->contrato->nombre }}
                                  @endpermission
                                </td>
                                <td>
                                  @permission('empleado-view')
                                    <a href="{{ route('admin.empleado.show', ['empleado' => $aprobado->empleado->id]) }}">
                                      {{ $aprobado->empleado->usuario->nombre() }}
                                    </a>
                                  @else
                                    {{ $aprobado->empleado->usuario->nombre() }}
                                  @endpermission
                                </td>
                                <td>{{ $aprobado->fecha }}</td>
                                <td class="text-right">{{ $aprobado->anticipo() }}</td>
                                <td class="text-right">{{ $aprobado->bono() }}</td>
                                <td>{{ optional($aprobado->created_at)->format('d-m-Y H:i:s') }}</td>
                                <td class="text-center">
                                  @permission('anticipo-view')
                                    <a class="btn btn-success btn-xs" href="{{ route('admin.anticipo.show', ['anticipo' => $aprobado->id]) }}"><i class="fa fa-search"></i></a>
                                  @endpermission
                                  @permission('anticipo-edit')
                                    <a class="btn btn-primary btn-xs" href="{{ route('admin.anticipo.edit', ['anticipo' => $aprobado->id]) }}"><i class="fa fa-pencil"></i></a>
                                  @endpermission
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <th class="text-right">
                                {{ number_format($month->anticipos->sum('anticipo'), 2, '.', ',') }}
                              </th>
                              <th class="text-right">
                                {{ number_format($month->anticipos->sum('bono'), 2, '.', ',') }}
                              </th>
                              <td></td>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                @empty
                  <h3 class="text-center text-muted my-3">No hay resultados</h3>
                @endforelse
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-3">
            <div class="panel-body">
              <div class="accordion" id="accordion-anticipos-pendientes">
                @forelse($monthlyGroupedPendientes as $month)
                  <div class="card">
                    <div class="card-header p-0" id="heading-pendientes-{{ $month->month }}">
                      <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse-pendientes-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-pendientes-{{ $month->month }}">
                        {{ $month->title }} ({{ $month->anticipos->count() }})
                      </button>
                    </div>
                    <div id="collapse-pendientes-{{ $month->month }}" class="collapse" aria-labelledby="heading-pendientes-{{ $month->month }}" data-parent="#accordion-anticipos-pendientes">
                      <div class="card-body">
                        <table class="table data-table table-bordered table-hover table-sm w-100">
                          <thead>
                            <tr class="text-center">
                              <th>#</th>
                              <th>Contrato</th>
                              <th>Empleado</th>
                              <th>Fecha</th>
                              <th>Anticipo</th>
                              <th>Bono</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($month->anticipos as $pendiente)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                  @permission('contrato-view')
                                    <a href="{{ route('admin.contrato.show', ['contrato' => $pendiente->contrato->id]) }}">
                                      {{ $pendiente->contrato->nombre }}
                                    </a>
                                  @else
                                    {{ $pendiente->contrato->nombre }}
                                  @endpermission
                                </td>
                                <td>
                                  @permission('empleado-view')
                                    <a href="{{ route('admin.empleado.show', ['empleado' => $pendiente->empleado->id]) }}">
                                      {{ $pendiente->empleado->usuario->nombre() }}
                                    </a>
                                  @else
                                    {{ $pendiente->empleado->usuario->nombre() }}
                                  @endpermission
                                </td>
                                <td>{{ $pendiente->fecha }}</td>
                                <td class="text-right">{{ $pendiente->anticipo() }}</td>
                                <td class="text-right">{{ $pendiente->bono() }}</td>
                                <td class="text-center">
                                  @permission('anticipo-view')
                                    <a class="btn btn-success btn-xs" href="{{ route('admin.anticipo.show', ['anticipo' => $pendiente->id]) }}"><i class="fa fa-search"></i></a>
                                  @endpermission
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <th class="text-right">
                                {{ number_format($month->anticipos->sum('anticipo'), 2, '.', ',') }}
                              </th>
                              <th class="text-right">
                                {{ number_format($month->anticipos->sum('bono'), 2, '.', ',') }}
                              </th>
                              <td></td>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                @empty
                  <h3 class="text-center text-muted my-3">No hay resultados</h3>
                @endforelse
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-4">
            <div class="panel-body">
              <div class="accordion" id="accordion-anticipos-rechazados">
                @forelse($monthlyGroupedRechazados as $month)
                  <div class="card">
                    <div class="card-header p-0" id="heading-rechazados-{{ $month->month }}">
                      <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse-rechazados-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-rechazados-{{ $month->month }}">
                        {{ $month->title }} ({{ $month->anticipos->count() }})
                      </button>
                    </div>
                    <div id="collapse-rechazados-{{ $month->month }}" class="collapse" aria-labelledby="heading-rechazados-{{ $month->month }}" data-parent="#accordion-anticipos-rechazados">
                      <div class="card-body">
                        <div class="row justify-content-center pb-3 mb-3 border-bottom">
                          <div class="col-md-3">
                            <div class="card">
                              <div class="card-body text-center">
                                <h3>{{ number_format($month->anticipos->sum('bono'), 2, '.', ',') }}</h3>
                                <p class="text-muted m-0">TOTAL BONO</p>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="card">
                              <div class="card-body text-center">
                                <h3>{{ number_format($month->anticipos->sum('anticipo'), 2, '.', ',') }}</h3>
                                <p class="text-muted m-0">TOTAL ANTICIPO</p>
                              </div>
                            </div>
                          </div>
                        </div>

                        <table class="table data-table table-bordered table-hover table-sm w-100">
                          <thead>
                            <tr class="text-center">
                              <th>#</th>
                              <th>Contrato</th>
                              <th>Empleado</th>
                              <th>Fecha</th>
                              <th>Anticipo</th>
                              <th>Bono</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($month->anticipos as $rechazado)
                              <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                  @permission('contrato-view')
                                    <a href="{{ route('admin.contrato.show', ['contrato' => $rechazado->contrato->id]) }}">
                                      {{ $rechazado->contrato->nombre }}
                                    </a>
                                  @else
                                    {{ $rechazado->contrato->nombre }}
                                  @endpermission
                                </td>
                                <td>
                                  @permission('empleado-view')
                                    <a href="{{ route('admin.empleado.show', ['empleado' => $rechazado->empleado->id]) }}">
                                      {{ $rechazado->empleado->usuario->nombre() }}
                                    </a>
                                  @else
                                    {{ $rechazado->empleado->usuario->nombre() }}
                                  @endpermission
                                </td>
                                <td>{{ $rechazado->fecha }}</td>
                                <td class="text-right">{{ $rechazado->anticipo() }}</td>
                                <td class="text-right">{{ $rechazado->bono() }}</td>
                                <td class="text-center">
                                  @permission('anticipo-view')
                                    <a class="btn btn-success btn-xs" href="{{ route('admin.anticipo.show', ['anticipo' => $rechazado->id]) }}"><i class="fa fa-search"></i></a>
                                  @endpermission
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                          <tfoot>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <th class="text-right">
                                {{ number_format($month->anticipos->sum('anticipo'), 2, '.', ',') }}
                              </th>
                              <th class="text-right">
                                {{ number_format($month->anticipos->sum('bono'), 2, '.', ',') }}
                              </th>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                @empty
                  <h3 class="text-center text-muted my-3">No hay resultados</h3>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#select-years').change(function () {
        $('#form-years').submit();
      });
    })
  </script>
@endsection
