@extends('layouts.app')

@section('title', 'Carpeta')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Carpetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ $carpeta->backUrl }}">Carpeta</a></li>
        <li class="breadcrumb-item active"><strong>Carpeta</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ $carpeta->backUrl }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @if(Auth::user()->tipo <= 2)
        <a class="btn btn-default btn-sm" href="{{ route('admin.carpeta.edit', ['carpeta' => $carpeta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endif
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $carpeta->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Adjuntos</b>
              <span class="pull-right">{{ $carpeta->documentos->count() }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $carpeta->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9" style="padding:0">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Adjuntos</h5>

          <div class="ibox-tools">
            <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => $carpeta->type(), 'id' => $carpeta->carpetable_id, 'carpeta' => $carpeta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
              @if($carpeta->carpetable->documentos()->count() < 10)
                <a class="btn btn-primary btn-xs" href="{{ route('admin.documentos.create', ['type' => $carpeta->type(), 'id' => $carpeta->carpetable_id, 'carpeta' => $carpeta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
              @endif
          </div>
        </div>
        <div class="ibox-content">
          <div class="row icons-box icons-folder">
            @foreach($carpeta->subcarpetas as $subcarpeta)
              <div class="col-md-3 col-xs-4 infont mb-3">
                <a href="{{ route('admin.carpeta.show', ['carpeta' => $subcarpeta->id]) }}">
                  <i class="fa fa-folder" aria-hidden="true"></i>
                  <p class="m-0">{{ $subcarpeta->nombre }}</p>
                </a>
              </div>
            @endforeach
          </div>
          <hr class="hr-line-dashed">
          <div class="row">
            @forelse($carpeta->documentos as $documento)
              @include('partials.documentos', ['edit' => true])
            @empty
            <div class="col-12">
              <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
            </div>
            @endforelse
          </div>
        </div><!-- /.box-body -->
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
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delFileModalLabel">Eliminar documento</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if(Auth::user()->tipo <= 2)
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.carpeta.destroy', ['carpeta' => $carpeta->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Carpeta</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Carpeta?</h4>
              <p class="text-center">Tambien se eliminarán todas las Carpetas y Documentos adjuntos contenidos en ella.</p>

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
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#delFileModal').on('show.bs.modal', function(e){
        var button = $(e.relatedTarget),
            action = button.data('url');

        $('#delete-file-form').attr('action', action);
      });

      $('#delete-file-form').submit(deleteFile);
    });

    function deleteFile(e){
      e.preventDefault();

      var form = $(this),
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
