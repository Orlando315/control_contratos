@extends('layouts.app')

@section('title', 'Anticipo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.anticipos.index') }}">Anticipos</a></li>
        <li class="breadcrumb-item active"><strong>Anticipo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  @if($anticipo->isPendiente() && Auth::user()->hasPermission('anticipo-edit'))
    <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="alert alert-info alert-important text-center" role="alert">
            <h4><i class="icon fa fa-level-up"></i> Solicitud de Anticipo</h4>
            <p class="m-0"><strong>{{ $anticipo->empleado->nombre() }}</strong> ha solicitado un anticipo de <strong>{{ $anticipo->anticipo() }}</strong></p>
            <button class="btn btn-danger btn-sm mt-2" type="button" data-type="0" data-toggle="modal" data-target="#statusAnticipoModal">
              <i class="fa fa-ban"></i> Rechazar
            </button>
            <button class="btn btn-success btn-sm mt-2" type="button" data-type="1" data-toggle="modal" data-target="#statusAnticipoModal">
              <i class="fa fa-check"></i> Aprobar
            </button>
          </div>
      </div>
    </div>
  @endif

  <div class="row mb-3">
    <div class="col-12">
      @permission('anticipo-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.anticipos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @if(!$anticipo->isRechazado() && Auth::user()->hasPermission('anticipo-edit'))
        <a class="btn btn-default btn-sm" href="{{ route('admin.anticipos.edit', ['anticipo' => $anticipo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endif
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
              <b>Contrato</b>
              <span class="pull-right">
                @permission('contrato-view')
                  <a href="{{ route('admin.contratos.show', ['contrato' => $anticipo->contrato->id]) }}">
                    {{ $anticipo->contrato->nombre }}
                  </a>
                @else
                  {{ $anticipo->contrato->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Empleado</b>
              <span class="pull-right">
                @permission('contrato-view')
                  <a href="{{ route('admin.empleados.show', ['empleado' => $anticipo->empleado_id]) }}">
                    {{ $anticipo->empleado->nombre() }}
                  </a>
                @else
                  {{ $anticipo->empleado->nombre() }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Serie</b>
              <span class="pull-right">
                @if($anticipo->hasSerie())
                  @permission('anticipo-index')
                    <a href="{{ route('admin.anticipos.show.serie', ['serie' => $anticipo->serie]) }}">
                      {{ $anticipo->serie }}
                    </a>
                  @else
                    {{ $anticipo->serie }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right">{{ $anticipo->fecha }}</span>
            </li>
            <li class="list-group-item">
              <b>Anticipo</b>
              <span class="pull-right">{{ $anticipo->anticipo() }}</span>
            </li>
            <li class="list-group-item">
              <b>Bono</b>
              <span class="pull-right">{{ $anticipo->bono() }}</span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($anticipo->descripcion)</span>
            </li>
            <li class="list-group-item">
              <b>Adjunto</b>
              <span class="pull-right">
                @if($anticipo->adjunto)
                  <a href="{{ $anticipo->adjunto_download }}" title="Descargar adjunto">Descargar</a>
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item" title="Si el Empleado solicito o no el Anticipo">
              <b>Solicitud</b>
              <span class="pull-right">
                {!! $anticipo->solicitud() !!}
              </span>
            </li>
            <li class="list-group-item">
              <b>Estatus</b>
              <span class="pull-right">
                {!! $anticipo->status() !!}
              </span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $anticipo->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  
  @permission('anticipo-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.anticipos.destroy', ['anticipo' => $anticipo->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Anticipo</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Anticipo?</h4>
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
  
  @if($anticipo->isPendiente() && Auth::user()->hasPermission('anticipo-edit'))
    <div id="statusAnticipoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusAnticipoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="status-modal-form" action="{{ route('admin.anticipos.status', ['anticipo' => $anticipo->id]) }}" method="POST">
            <input id="status-modal-value" type="hidden" name="status">
            @method('PUT')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="statusAnticipoModalLabel">Cambiar estatus</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de <span id="status-modal-label"></span> este Anticipo?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('script')
  @if($anticipo->isPendiente() && Auth::user()->hasPermission('anticipo-edit'))
    <script type="text/javascript">
      $(document).ready(function () {
        $('#statusAnticipoModal').on('show.bs.modal', function (e) {
          let type = +$(e.relatedTarget).data('type');

          title = type == 1 ? 'aprobar' : 'rechazar';

          $('#status-modal-value').val(type)
          $('#status-modal-label').text(title)
        })
      })
    </script>
  @endif
@endsection
