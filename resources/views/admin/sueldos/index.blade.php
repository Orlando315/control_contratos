@extends('layouts.app')

@section('title', 'Sueldos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Sueldos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Sueldos</li>
        <li class="breadcrumb-item active"><strong>Sueldos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @if($contrato && Auth::user()->hasPermission('contrato-view'))
        <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endif
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-6 text-center text-md-right">
      <h3 class="my-2">Información del año: {{ $actualYear }}</h3>
    </div>
    <div class="col-md-6 text-center text-md-left">
      <form id="form-years" action="{{ route('admin.sueldos.index', ['contrato' => optional($contrato)->id]) }}">
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
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-money"></i> Sueldos</h5>

          <div class="ibox-tools">
            @if($contrato && Auth::user()->hasPermission('sueldo-create'))
              <a class="btn btn-primary btn-xs" href="{{ route('admin.sueldos.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Realizar Pagos</a>
            @endif
          </div>
        </div>
        <div class="ibox-content">
          <div class="accordion" id="accordion-sueldos">
            @forelse($monthlyGroupedSueldos as $month)
              <div class="card">
                <div class="card-header p-0" id="heading-{{ $month->month }}">
                  <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-{{ $month->month }}">
                    {{ $month->title }} ({{ $month->sueldos->count() }})
                  </button>
                </div>
                <div id="collapse-{{ $month->month }}" class="collapse" aria-labelledby="heading-{{ $month->month }}" data-parent="#accordion-sueldos">
                  <div class="card-body">
                    <table class="table data-table table-bordered table-hover table-sm w-100">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Contrato</th>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Empleado</th>
                          <th class="text-center">Alcance líquido</th>
                          <th class="text-center">Sueldo líquido</th>
                          <th class="text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        @foreach($month->sueldos as $sueldo)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sueldo->contrato->nombre }}</td>
                            <td>{{ $sueldo->mesPagado() }}</td>
                            <td>
                              @permission('empleado-view')
                                <a href="{{ route('admin.empleados.show', ['empleado' => $sueldo->empleado_id]) }}">
                                  {{ $sueldo->nombreEmpleado() }}
                                </a>
                              @else
                                {{ $sueldo->nombreEmpleado() }}
                              @endpermission
                            </td>
                            <td class="text-right">{{ $sueldo->alcanceLiquido() }}</td>
                            <td class="text-right">{{ $sueldo->sueldoLiquido() }}</td>
                            <td>
                              @permission('sueldo-view')
                                <a class="btn btn-success btn-xs" href="{{ route('admin.sueldos.show', ['sueldo' => $sueldo->id] )}}"><i class="fa fa-search"></i></a>
                              @endpermission
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
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
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#select-years').change(function () {
        $('#form-years').submit();
      })
    })
  </script>
@endsection

