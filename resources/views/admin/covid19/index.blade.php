@extends('layouts.app')

@section('title', 'Covid-19')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Covid-19</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Covid-19</strong></li>
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
      <form id="form-years" action="{{ route('admin.empresa.covid19.index') }}">
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
          <h5><i class="fa fa-heartbeat" aria-hidden="true"></i> Covid-19</h5>
        </div>
        <div class="ibox-content">
          <div class="accordion" id="accordion-respuestas">
            @forelse($monthlyGrouped as $month)
              <div class="card">
                <div class="card-header p-0" id="heading-respuestas-{{ $month->month }}">
                  <button class="btn btn-link btn-block text-left p-3" type="button" data-toggle="collapse" data-target="#collapse-respuestas-{{ $month->month }}" aria-expanded="false" aria-controls="collapse-respuestas-{{ $month->month }}">
                    {{ $month->title }} ({{ $month->respuestas->count() }})
                  </button>
                </div>
                <div id="collapse-respuestas-{{ $month->month }}" class="collapse" aria-labelledby="heading-respuestas-{{ $month->month }}" data-parent="#accordion-respuestas">
                  <div class="card-body">
                    <table class="table data-table table-bordered table-hover w-100">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Fecha</th>
                          <th class="text-center">Nombre</th>
                          <th class="text-center">RUT</th>
                          <th class="text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($month->respuestas as $respuesta)
                          <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $respuesta->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $respuesta->user->nombre() }}</td>
                            <td>{{ $respuesta->user->rut }}</td>
                            <td class="text-center">
                              <a class="btn btn-success btn-xs" href="{{ route('admin.empresa.covid19.show', ['respuesta' => $respuesta->id]) }}"><i class="fa fa-search"></i></a>
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
      });
    })
  </script>
@endsection
