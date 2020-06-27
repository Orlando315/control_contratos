@extends('layouts.app')

@section('title', 'Inventario')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Inventarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventarios.index') }}">Inventarios</a></li>
        <li class="breadcrumb-item active"><strong>Inventario</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('inventarios.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @if(Auth::user()->tipo <= 2 || $inventario->tipo == 3)
        <a class="btn btn-default btn-sm" href="{{ route('inventarios.edit', ['inventario' => $inventario->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endif
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
                <a href="{{ route('contratos.show', ['contrato' => $inventario->contrato->id]) }}">{{ $inventario->contrato->nombre }} </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{{ $inventario->tipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $inventario->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Valor</b>
              <span class="pull-right">{{ $inventario->valor() }}</span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right"> {{ $inventario->fecha }} </span>
            </li>
            <li class="list-group-item">
              <b>Cantidad</b>
              <span class="pull-right"> {{ $inventario->cantidad() }} </span>
            </li>
            <li class="list-group-item">
              <b>Stock crítico</b>
              <span class="pull-right"> {{ $inventario->lowStock() }} </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right"> {{ $inventario->descripcion ?? 'N/A' }} </span>
            </li>
            <li class="list-group-item">
              <b>Observación</b>
              <span class="pull-right"> {{ $inventario->observacion ?? 'N/A' }} </span>
            </li>
            <li class="list-group-item">
              <b>Adjunto</b>
              <span class="pull-right">{!! $inventario->adjunto() !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $inventario->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-arrow-right"></i> Entregas</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('entregas.create', ['inventario' => $inventario->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Entrega</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Realizado por</th>
                <th class="text-center">Entregado a</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Recibido</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($inventario->entregas as $d)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $d->realizadoPor->nombres }} {{ $d->realizadoPor->apellidos }}</td>
                  <td>{{ $d->entregadoA->nombres }} {{ $d->entregadoA->apellidos }}</td>
                  <td>{{ $d->cantidad() }}</td>
                  <td>{{ $d->created_at }}</td>
                  <td>{!! $d->recibido() !!}</td>
                  <td>
                    @if($d->adjunto)
                      <a class="btn btn-default btn-xs" href="{{ $d->download }}"><i class="fa fa-download" aria-hidden="true"></i></a>
                    @endif
                    @if(!$d->recibido)
                      <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delEntregaModal" data-entrega="{{ $d->id }}"><i class="fa fa-times" aria-hidden="true"></i></button>
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

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('inventarios.destroy', ['inventario' => $inventario->id]) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Inventario</h4>
          </div>
          <div class="modal-body">
                <h4 class="text-center">¿Esta seguro de eliminar este Inventario?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="delEntregaModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delEntregaModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="delete-entrega" action="#" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delEntregaModalLabel">Eliminar Entrega</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Entrega?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#delEntregaModal').on('show.bs.modal', function(e){
        var button  = $(e.relatedTarget),
            entrega = button.data('entrega'),
            action  = '{{ route("entregas.index") }}/{{ $inventario->id }}/' + entrega;

        $('#delete-entrega').attr('action', action);
      });
    });
  </script>
@endsection
