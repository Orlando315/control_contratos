@extends('layouts.app')

@section('title', 'Ayuda')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ayudas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.ayuda.index') }}">Ayudas</a></li>
        <li class="breadcrumb-item active"><strong>Ayuda</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('ayuda.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5>{{ $ayuda->titulo }}</h5>
        </div>
        <div class="ibox ibox-with-footer">
          <div class="ibox-content {{ $ayuda->hasVideo() ? ' sk-loading' : '' }}">
            @if($ayuda->hasVideo())
              <div class="sk-spinner sk-spinner-double-bounce">
                <div class="sk-double-bounce1"></div>
                <div class="sk-double-bounce2"></div>
              </div>

              <div class="w-100 mb-3">
                <div class="video-responsive-container">
                  <div id="video-container"></div>
                </div>
              </div>
            @endif
            <div class="w-100">
              {!! $ayuda->contenido !!}
            </div>
          </div>
        </div>
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
