@extends('layouts.blank')

@section('title', 'Anticipos')

@section('head')
  <!-- App css -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
  <div class="container">
    <div class="row mb-3 no-print">
      <div class="col-12">
        <a class="btn btn-default btn-sm" href="{{ route('admin.anticipos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-12">
        <h2>Anticipos</h2>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-3">
        <div class="ibox">
          <div class="ibox-content no-padding">
            <ul class="list-group">
              <li class="list-group-item">
                <b>Serie</b>
                <span class="pull-right"> {{ $serie }}</span>
              </li>
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  {{ $contrato->nombre }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Total anticipo</b>
                <span class="pull-right"> {{ number_format($totalAnticipos, 2, ',', '.') }}</span>
              </li>
              <li class="list-group-item">
                <b>Total bono</b>
                <span class="pull-right"> {{ number_format($totalBonos, 2, ',', '.') }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right"> {{ $serieFecha }}</span>
              </li>
            </ul>
          </div><!-- /.ibox-content -->
        </div>
      </div>
      <div class="col-md-9">
        <table class="table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">Empleado</th>
                <th class="text-center">Anticipo</th>
                <th class="text-center">Bono</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($anticipos as $anticipo)
                <tr>
                  <td>{{ $anticipo->empleado->nombre() }}</td>
                  <td class="text-right">{{ $anticipo->anticipo() }}</td>
                  <td class="text-right">{{ $anticipo->bono() }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
      </div>
    </div>

    <p class="text-center text-muted">{{ config('app.name') }} - {{ date('Y') }}</p>
  </div>
@endsection

@section('script')
 	<script type="text/javascript">
    setTimeout(function () { window.print(); }, 500);
    window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
 	</script>
@endsection
