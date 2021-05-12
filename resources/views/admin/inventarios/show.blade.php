@extends('layouts.app')

@section('title', 'Inventario')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Inventarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventarios.index') }}">Inventarios</a></li>
        <li class="breadcrumb-item active"><strong>Inventario</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="widget red-bg p-lg text-center mb-3">
    <i class="fa fa-ban fa-3x"></i>
    <h1 class="m-xs">Deshabilitado</h1>
    <p class="font-bold">
      Este modulo ha sido deshabilitado y será eliminado en un futuro.
    </p>
    @permission('inventario-v2-index')
      <a class="btn btn-default text-dark" href="{{ route('admin.inventario.v2.index') }}">Ir a Inventario V2</a>
    @endpermission
  </div>

  <div class="row mb-3">
    <div class="col-12">
      @permission('inventario-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventarios.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
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
                  <a href="{{ route('admin.contratos.show', ['contrato' => $inventario->contrato->id]) }}">
                    {{ $inventario->contrato->nombre }}
                  </a>
                @else
                  {{ $inventario->contrato->nombre }}
                @endpermission
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
              <span class="pull-right">{{ $inventario->fecha }}</span>
            </li>
            <li class="list-group-item">
              <b>Cantidad</b>
              <span class="pull-right">{{ $inventario->cantidad() }}</span>
            </li>
            <li class="list-group-item">
              <b>Stock crítico</b>
              <span class="pull-right">
                @if($inventario->low_stock)
                  {{ $inventario->lowStock() }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($inventario->descripcion)</span>
            </li>
            <li class="list-group-item">
              <b>Observación</b>
              <span class="pull-right">@nullablestring($inventario->observacion)</span>
            </li>
            <li class="list-group-item">
              <b>Adjunto</b>
              <span class="pull-right">
                @if($inventario->adjunto)
                  <a href="{{ $inventario->download }}">Descargar</a>
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Requiere calibración</b>
              <span class="pull-right">{!! $inventario->calibracion() !!}</span>
            </li>
            <li class="list-group-item">
              <b>Certificado</b>
              <span class="pull-right">{!! $inventario->certificado() !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $inventario->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox mb-3">
        <div class="ibox-title">
          <h5>Adjuntos</h5>
        </div>
        <div class="ibox-content">
          <div class="row icons-box icons-folder">
            @foreach($inventario->carpetas()->main()->get() as $carpeta)
              <div class="col-md-3 col-xs-4 infont mb-3">
                <a href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}">
                  <i class="fa fa-folder" aria-hidden="true"></i>
                  <p class="m-0">{{ $carpeta->nombre }}</p>
                </a>
              </div>
            @endforeach
          </div>

          <hr class="hr-line-dashed">

          <div class="row">
            @forelse($inventario->documentos as $documento)
              @include('partials.documentos', ['edit' => false])
            @empty
              <div class="col-12">
                <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-arrow-right"></i> Entregas</h5>
          <div class="ibox-tools">
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
              @foreach($inventario->entregas as $entrega)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $entrega->realizadoPor->nombres }} {{ $entrega->realizadoPor->apellidos }}</td>
                  <td>{{ $entrega->entregadoA->nombres }} {{ $entrega->entregadoA->apellidos }}</td>
                  <td>{{ $entrega->cantidad() }}</td>
                  <td>{{ $entrega->created_at }}</td>
                  <td>{!! $entrega->recibido() !!}</td>
                  <td>
                    @if($entrega->adjunto)
                      <a class="btn btn-default btn-xs" href="{{ $entrega->download }}"><i class="fa fa-download" aria-hidden="true"></i></a>
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

  @permission('inventario-edit')
    <div id="cloneModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="cloneModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.inventarios.clone', ['inventario' => $inventario->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="cloneModalLabel">Clonar inventario</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de clonar este Inventario?</h4>
              <p class="text-center">No se clonarán las Carpetas, Adjuntos o Entregas</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-warning btn-sm" type="submit">Clonar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="delFileModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delete-file-form" action="#" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delFileModalLabel">Eliminar Adjunto</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Adjunto?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission

  @permission('inventario-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.inventarios.destroy', ['inventario' => $inventario->id]) }}" method="POST">
            @method('DELETE')
            @csrf

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
  @endpermission

  @permission('inventario-entrega-delete')
    <div id="delEntregaModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delEntregaModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delete-entrega" action="#" method="POST">
            @method('DELETE')
            @csrf

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
  @endpermission
@endsection

@section('script')
  @include('partials.preview-pdf')

  @permission('inventario-entrega-delete')
    <script type="text/javascript">
      $(document).ready(function(){
        $('#delEntregaModal').on('show.bs.modal', function(e){
          var button  = $(e.relatedTarget),
              action = button.data('url');

          if(!action){ return false; }

          $('#delete-entrega').attr('action', action);
        });

        $('#delFileModal').on('show.bs.modal', function(e){
          let button = $(e.relatedTarget),
              action = button.data('url');

          if(!action){ return false; }

          $('#delete-file-form').attr('action', action);
        });

        $('#delete-file-form').submit(deleteFile);
      });

      function deleteFile(e){
        e.preventDefault();

        let form = $(this),
            action = form.attr('action');

        $.ajax({
          type: 'POST',
          url: action,
          data: form.serialize(),
          dataType: 'json',
        })
        .done(function(r){
          if(r.response){
            $('#adjunto-' + r.id).remove();
            $('#delFileModal').modal('hide');
          }else{
            $('.alert').show().delay(7000).hide('slow');
          }
        })
        .fail(function(){
          $('.alert').show().delay(7000).hide('slow');
        })
      }
    </script>
  @endpermission
@endsection
