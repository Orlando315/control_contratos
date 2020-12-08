@extends('layouts.app')

@section('title', 'Consumo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Consumo</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transportes.index') }}">Transportes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transportes.show', ['transporte' => $consumo->transporte_id]) }}">Consumos</a></li>
        <li class="breadcrumb-item active"><strong>Consumo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.transportes.show', ['transporte' => $consumo->transporte_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btns-sm" href="{{ route('admin.consumos.edit', ['consumo' => $consumo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
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
                <a href="{{ route('admin.contratos.show', ['contrato' => $consumo->contrato_id]) }}">
                  {{ $consumo->contrato->nombre }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Fecha</b>
              <span class="pull-right">{{ $consumo->fecha() }}</span>
            </li>
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{{ $consumo->tipo() }}</span>
            </li>
            @if($consumo->tipo == 2)
              <li class="list-group-item">
                <b>Cantidad</b>
                <span class="pull-right">{{ $consumo->cantidad() }}</span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Valor</b>
              <span class="pull-right">{{ $consumo->valor }}</span>
            </li>
            <li class="list-group-item">
              <b>Chofer</b>
              <span class="pull-right">{{ $consumo->chofer }}</span>
            </li>
            <li class="list-group-item">
              <b>Observación</b>
              <span class="pull-right">@nullablestring($consumo->observacion)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $consumo->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Adjuntos</h5>

          @if($consumo->documentos()->count() < 10)
            <div class="ibox-tools">
              <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => 'consumos', 'id' => $consumo->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
              <a class="btn btn-primary btn-xs" href="{{ route('admin.documentos.create', ['type' => 'consumos', 'id' => $consumo->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
            </div>
          @endif
        </div>
        <div class="ibox-content">
          <div class="row icons-box icons-folder">
            @foreach($consumo->carpetas()->main()->get() as $carpeta)
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
            @forelse($consumo->documentos()->main()->get() as $documento)
              @include('partials.documentos', ['edit' => true])
            @empty
              <div class="col-12">
                <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.consumos.destroy', ['consumo' => $consumo->id]) }}" method="POST">
          @method('DELETE')
          @csrf
          
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Consumo</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Consumo?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
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
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function (){
      $('#delFileModal').on('show.bs.modal', function(e){
        let button = $(e.relatedTarget),
            action = button.data('url');

        $('#delete-file-form').attr('action', action);
      });

      $('#delete-file-form').submit(deleteFile);
    })

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
@endsection
