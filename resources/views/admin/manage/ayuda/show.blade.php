@extends('layouts.app')

@section('title', 'Ayuda')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ayudas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.ayuda.index') }}">Ayudas</a></li>
        <li class="breadcrumb-item active"><strong>Ayuda</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.manage.ayuda.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.manage.ayuda.edit', ['ayuda' => $ayuda->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox ibox-with-footer">
        <div class="ibox-title">
          <h5><i class="fa fa-info"></i> Información</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Título</b>
              <span class="pull-right">@nullablestring($ayuda->titulo)</span>
            </li>
            <li class="list-group-item">
              <b>Roles</b>
              <span class="pull-right">{!! $ayuda->allRolesNames() !!}</span>
            </li>
            <li class="list-group-item">
              <b>Video</b>
              <span class="pull-right">{!! $ayuda->video() !!}</span>
            </li>
            <li class="list-group-item">
              <b>Status</b>
              <span class="pull-right">{!! $ayuda->status() !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $ayuda->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      @if($ayuda->hasVideo())
        <div class="ibox mb-3">
          <div class="ibox-title">
            <h5>Video</h5>
          </div>
          <div class="ibox-content sk-loading">
            <div class="sk-spinner sk-spinner-double-bounce">
              <div class="sk-double-bounce1"></div>
              <div class="sk-double-bounce2"></div>
            </div>
            <div class="video-responsive-container">
              <div id="video-container"></div>
            </div>
          </div>
        </div>
      @endif

      <div class="ibox">
        <div class="ibox-title">
          <h5>Contenido</h5>
        </div>
        <div class="ibox ibox-with-footer">
          <div class="ibox-content">
            {!! $ayuda->contenido !!}
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.manage.ayuda.destroy', ['ayuda' => $ayuda->id]) }}" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>

            <h4 class="modal-title" id="delModalLabel">Eliminar Ayuda</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Ayuda?</h4>
            <p class="text-center">Se eliminará toda la información asociada a esta Ayuda</p>
            <p class="text-center">Para confirmar esta acción, introduzca su contraseña</p>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password">Contraseña: *</label>
              <input id="password" class="form-control" type="password" name="password" placeholder="Contraseña" required>
            </div>
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

@if($ayuda->hasVideo())
  <script type="text/javascript" src="https://www.youtube.com/iframe_api"></script>
  <script type="text/javascript">
    function onYouTubeIframeAPIReady() {
      player = new YT.Player('video-container', {
        height: '443',
        width: '800',
        videoId: '{{ $ayuda->video }}',
        events: {
          onReady: function () {
            $('#video-container').closest('.ibox-content').removeClass('sk-loading');
          }
        }
      });
    }
  </script>
@endif
