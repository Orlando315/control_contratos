@extends('layouts.app')
@section('title', 'Carpeta - '.config('app.name'))
@section('header', 'Carpeta')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('carpeta.index') }}">Carpetas</a></li>
	  <li class="active"> Carpeta </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ $carpeta->backUrl }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    @if(Auth::user()->tipo <= 2)
      <a class="btn btn-flat btn-success" href="{{ route('carpeta.edit', ['carpeta' => $carpeta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    @endif
  </section>

  <section style="margin-top: 20px">
    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos de la Carpeta
            </h4>
            <p class="text-muted text-center">{{ $carpeta->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nombre</b>
                <span class="pull-right">{{ $carpeta->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Adjuntos</b>
                <span class="pull-right">{{ $carpeta->documentos->count() }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9" style="padding:0">
        <div class="box">
          <div class="box-header">
            <h4>
              Adjuntos
              <div class="pull-right">
                <a class="btn btn-flat btn-warning btn-sm" href="{{ route('carpeta.create', ['type' => $carpeta->type(), 'id' => $carpeta->carpetable->id, 'carpeta' => $carpeta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                @if($carpeta->carpetable->documentos()->count() < 10)
                  <a class="btn btn-flat btn-success btn-sm" href="{{ route(($carpeta->isContrato() ? 'documentos.createContrato' : 'documentos.createEmpleado'), ['id' => $carpeta->carpetable_id, 'carpeta' => $carpeta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                @endif
              </div>
            </h4>
          </div>
          <div class="box-body">
            <div class="row">
              @foreach($carpeta->subcarpetas as $subcarpeta)
                <div class="col-md-4 col-sm-6 col-xs-12">
                  {!! $subcarpeta->template() !!}
                </div>
              @endforeach
            </div>
            <div class="row">
              @forelse($carpeta->documentos as $documento)
                <div id="file-{{$documento->id}}" class="col-md-4 col-sm-6 col-xs-12">
                  {!! $documento->generateThumb() !!}
                </div>
              @empty
              <div class="col-12">
                <h4 class="text-center text-muted">No hay documetos adjuntos</h4>
              </div>
              @endforelse
            </div>
          </div><!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>

  <div id="delFileModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delFileModalLabel">Eliminar archivo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delete-file-form" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4><br>

              <center>
                <button class="btn btn-flat btn-danger" type="submit">Eliminar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(Auth::user()->tipo <= 2)
    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Carpeta</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form class="col-md-8 col-md-offset-2" action="{{ route('carpeta.destroy', [$carpeta->id]) }}" method="POST">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <h4 class="text-center">¿Esta seguro de eliminar este Carpeta?</h4>
                <p class="text-center">Tambien se eliminarán todas las Carpetas y Documentos adjuntos contenidos en ella.</p><br>

                <center>
                  <button class="btn btn-flat btn-danger" type="submit">Eliminar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('scripts')
  <script type="text/javascript">

    $(document).ready(function(){
      $('#delFileModal').on('show.bs.modal', function(e){
        var button = $(e.relatedTarget),
            file   = button.data('file'),
            action = '{{ route("documentos.index") }}/' + file;

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
          $('#file-' + r.id).remove();
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
