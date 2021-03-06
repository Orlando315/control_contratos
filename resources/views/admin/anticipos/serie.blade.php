@extends('layouts.app')

@section('title', 'Anticipos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.anticipo.index') }}">Anticipos</a></li>
        <li class="breadcrumb-item active"><strong>Serie</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('anticipo-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.anticipo.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      <a class="btn btn-default btn-sm" href="{{ route('admin.anticipo.print.serie', ['serie' => $serie]) }}" target="_blank"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</a>
      @permission('anticipo-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row">
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
                @permission('contrato-view')
                  <a href="{{ route('admin.contrato.show', ['contrato' => $contrato->id]) }}">
                    {{ $contrato->nombre }}
                  </a>
                @else
                  {{ $contrato->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Total anticipo</b>
              <span class="pull-right">{{ number_format($totalAnticipos, 2, ',', '.') }}</span>
            </li>
            <li class="list-group-item">
              <b>Total bono</b>
              <span class="pull-right">{{ number_format($totalBonos, 2, ',', '.') }}</span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right">{{ $serieFecha }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-level-up"></i> Anticipos</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Empleado</th>
                <th>Anticipo</th>
                <th>Bono</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($anticipos as $anticipo)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $anticipo->empleado->nombre() }}</td>
                  <td class="text-right">{{ $anticipo->anticipo() }}</td>
                  <td class="text-right">{{ $anticipo->bono() }}</td>
                  <td class="text-center">
                    @permission('anticipo-view')
                      <a class="btn btn-success btn-xs" href="{{ route('admin.anticipo.show', ['anticipo' => $anticipo->id]) }}"><i class="fa fa-search"></i></a>
                    @endpermission
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <th class="text-right">
                  {{ number_format($anticipos->sum('anticipo'), 2, '.', ',') }}
                </th>
                <th class="text-right">
                  {{ number_format($anticipos->sum('bono'), 2, '.', ',') }}
                </th>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  @permission('anticipo-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.anticipo.destroy.serie', ['serie' => $serie]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Serie</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Serie?</h4>
              <p class="text-center">Se eliminarán todos los Anticipos de la serie</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection
