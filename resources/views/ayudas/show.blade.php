@extends('layouts.app')

@section('title', 'Ayuda - '.config('app.name'))
@section('header', 'Ayuda')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('ayudas.index') }}">Ayudas</a></li>
	  <li class="active"> Ayuda </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('ayudas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('ayudas.edit', [$ayuda->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-warning">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos de la Ayuda
            </h4>
            <p class="text-muted text-center">{{ $ayuda->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  <a href="{{ route('usuarios.show', ['usuario' => $ayuda->user_id]) }}">{{ $ayuda->usuario->nombres }} {{ $ayuda->usuario->apellidos }}</a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Título</b>
                <span class="pull-right">{{ $ayuda->titulo }}</span>
              </li>
              <li class="list-group-item">
                <b>Video</b>
                <span class="pull-right">{{ $ayuda->video ?? 'N/A' }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        @if($ayuda->contenido)
          <div class="col-md-12">
            <blockquote style="background-color:#fff;border-left-color:#f39c12;border-radius:3px;">
              <p class="text-justify">{{ $ayuda->contenido }}</p>
            </blockquote>
          </div>
        @endif
        
        @if($ayuda->video)
          <div class="col-md-12">
            <!-- 16:9 aspect ratio -->
            <div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $ayuda->video }}"></iframe>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Ayuda</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('ayudas.destroy', [$ayuda->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Ayuda?</h4><br>

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
 
@endsection
