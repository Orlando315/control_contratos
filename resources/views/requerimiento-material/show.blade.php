@extends('layouts.app')

@section('title', 'Requerimiento de Materiales')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Requerimiento de Materiales</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('requerimiento.material.index') }}">Solicitudes</a></li>
        <li class="breadcrumb-item">Requerimiento de Materiales</li>
        <li class="breadcrumb-item active"><strong>Requerimiento de Materiales</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('requerimiento.material.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Atras</a>
      @if((Auth::id() == $requerimiento->solicitante || $requerimiento->userIsFirmante()) && $requerimiento->isPendiente())
        <a class="btn btn-default btn-sm" href="{{ route('requerimiento.material.edit', ['requerimiento' => $requerimiento->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endif
      <a class="btn btn-default btn-sm" href="{{ route('requerimiento.material.pdf', ['requerimiento' => $requerimiento->id]) }}" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Descargar</a>
      @if(Auth::id() == $requerimiento->solicitante || Auth::user()->hasPermission('requerimiento-material-delete'))
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endif
      @if($requerimiento->isAprobado() && Auth::user()->hasPermission('compra-create') && !$requerimiento->hasCompras())
        <a class="btn btn-primary btn-sm" href="{{ route('admin.compra.requerimiento', ['requerimiento' => $requerimiento->id]) }}"><i class="fa fa-plus-square" aria-hidden="true"></i> Generar Orden de compra</a>
      @endif
    </div>
  </div>

  @if($requerimiento->userNeedsToApprove())
    <div class="ibox">
      <div class="ibox-content text-center">
        <p class="{{ $requerimiento->sessionFirmante->isObligatorio() ? 'm-0' : '' }}">Usted está configurado como usuario que debe aprobar este Requerimiento</p>
        @if($requerimiento->sessionFirmante->isObligatorio())
          <p>Su aprobación es <strong>obligatoria</strong> para este Requerimiento</p>
        @endif
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#statusModal" data-title="Rechazar" data-status="0"><i class="fa fa-close" aria-hidden="true"></i> Rechazar</button>
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#statusModal" data-title="Aprobar" data-status="1"><i class="fa fa-check" aria-hidden="true"></i> Aprobar</button>
      </div>
    </div>
  @endif

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Solicitante</b>
              <span class="pull-right">
                {{ $requerimiento->userSolicitante->nombre() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Dirigido a</b>
              <span class="pull-right">
                {{ $requerimiento->dirigidoA->nombre() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                {{ $requerimiento->contrato->nombre }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Faena</b>
              <span class="pull-right">
                @if($requerimiento->faena)
                  {{ $requerimiento->faena->nombre }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Centro costo</b>
              <span class="pull-right">
                @if($requerimiento->centroCosto)
                  {{ $requerimiento->centroCosto->nombre }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Requerido para el</b>
              <span class="pull-right">
                @nullablestring(optional($requerimiento->fecha)->format('d-m-Y'))
              </span>
            </li>
            <li class="list-group-item">
              <b>Urgencia</b>
              <span class="pull-right">
                {!! $requerimiento->urgencia() !!}
              </span>
            </li>
            <li class="list-group-item">
              <b>Estatus</b>
              <span class="pull-right">
                {!! $requerimiento->status() !!}
              </span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $requerimiento->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9">
      <div class="row">
        @foreach($requerimiento->firmantes as $firmante)
          <div class="col-md-4">
            <div class="ibox">
              <div class="ibox-content">
                <h4 class="border-bottom pb-1">{{ $firmante->pivot->texto }}</h4>
                <p class="mb-1">
                  {{ $firmante->nombre() }}
                  @if($firmante->pivot->isObligatorio())
                    <span class="text-danger">*</span>
                  @endif
                </p>
                {!! $firmante->pivot->status() !!}
                @if($firmante->pivot->observacion)
                  <p class="mb-0 mt-1"><small>{{ $firmante->pivot->observacion }}</small></p>
                @endif
              </div>
            </div>
          </div>
        @endforeach

        <div class="col-md-12">
          <div class="tabs-container">
            <ul class="nav nav-tabs">
              <li><a class="nav-link active" href="#tab-1" data-toggle="tab">Productos</a></li>
              <li><a class="nav-link" href="#tab-2" data-toggle="tab">Historial de cambios</a></li>
            </ul>
            <div class="tab-content">
              <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                  <p class="text-center">Se muestran los cambios realizaddos por los firmantes.</p>
                  <table class="table data-table table-bordered table-hover w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Tipo</br>código</th>
                        <th class="text-center">Código</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($requerimiento->productos as $producto)
                        <tr class="{{ $producto->wasAdded() ? ' table-warning' : '' }}">
                          <td class="text-center">{{ $loop->iteration }}</td>
                          <td>@nullablestring($producto->tipo_codigo)</td>
                          <td>@nullablestring($producto->codigo)</td>
                          <td>{{ $producto->nombre }}</td>
                          <td class="text-right">{{ $producto->cantidad() }}</td>
                          <td class="text-center">
                            @if(Auth::id() == $requerimiento->solicitante || Auth::user()->hasPermission('requerimiento-material-edit'))
                              <button class="btn btn-danger btn-xs" type="button" data-toggle="modal" data-target="#delProductoModal" data-url="{{ route('admin.requerimiento.material.producto.destroy', ['producto' => $producto->id]) }}">
                                <i class="fa fa-times"></i>
                              </button>
                            @endif
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
                      <tr>
                        <th class="text-center" style="width: 1%">#</th>
                        <th class="text-center" style="width: 70%">Mensaje</th>
                        <th class="text-center" style="width: 29%">Fecha</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($requerimiento->logs as $log)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{!! $log->message !!}</td>
                          <td class="text-center">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
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
    </div>
  </div>

  @if(Auth::id() == $requerimiento->solicitante || Auth::user()->hasPermission('requerimiento-material-delete'))
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('requerimiento.material.destroy', ['requerimiento' => $requerimiento->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Requerimiento</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Requerimiento?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
  
  @if(Auth::id() == $requerimiento->solicitante || $requerimiento->userIsFirmante() || Auth::user()->hasPermission('requerimiento-material-edit'))
    <div id="delProductoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delProductoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delProductoModalForm" action="#" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delDataModalLabel">Eliminar Producto</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Producto?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm btn-delete-data" type="submit" disabled>Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  @if($requerimiento->userNeedsToApprove())
    <div id="statusModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('requerimiento.material.approve', ['requerimiento' => $requerimiento->id]) }}" method="POST">
            <input id="requerimiento-status" type="hidden" name="status">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="statusModalLabel"><span class="modal-title-text"></span> Requerimiento</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de <span class="modal-title-text"></span> este Requerimiento?</h4>

              <div class="form-group">
                <label for="observacion">Observación:</label>
                <input id="observacion" class="form-control" type="text" name="observacion" maxlength="250" placeholder="Observación">
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      @if(Auth::id() == $requerimiento->solicitante || $requerimiento->userIsFirmante() || Auth::user()->hasPermission('requerimiento-material-edit'))
        $('#delProductoModal').on('show.bs.modal', function (e) {
          let btn = $(e.relatedTarget),
              url = btn.data('url');

          if(!url){
            setTimeout(function (){
              $('#delProductoModal').modal('hide');
            }, 500);
          }

          $('.btn-delete-data').prop('disabled', !url);
          $('#delProductoModalForm').attr('action', url);
        });
      @endif

      @if($requerimiento->userNeedsToApprove())
        $('#statusModal').on('show.bs.modal', function (e) {
          let btn = $(e.relatedTarget),
              status = btn.data('status'),
              text = btn.data('title');

          $('.modal-title-text').text(text);
          $('#requerimiento-status').val(status);
        });
      @endif
    });
  </script>
@endsection
