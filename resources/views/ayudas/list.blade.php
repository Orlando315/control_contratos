@extends('layouts.app')
@section('title','Ayudas - '.config( 'app.name'))
@section('header','Ayudas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Ayudas</li>
  </ol>
@endsection

@section('content')

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div id="accordion" class="box-group">
        @foreach($ayudas as $d)
          <div class="panel box box-default">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#ayuda{{$d->id}}" aria-expanded="false">
                  {{ $d->titulo }}
                </a>
              </h4>
            </div>
            <div id="ayuda{{ $d->id }}" class="panel-collapse collapse" aria-expanded="false">
              <div class="box-body">
                @if($d->contenido)
                  <div class="col-md-12">
                    <p class="text-justify">{{ $d->contenido }}</p>
                  </div>
                @endif
                
                @if($d->video)
                  <div class="col-md-12">
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $d->video }}"></iframe>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
