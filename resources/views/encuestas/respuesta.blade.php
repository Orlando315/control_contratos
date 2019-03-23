@extends('layouts.app')

@section('title', 'Respuesta - '.config('app.name'))
@section('header', 'Respuesta')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('encuestas.show', ['encuesta' => $encuesta->id]) }}">Encuesta</a></li>
	  <li class="active"> Respuesta </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('encuestas.show', ['encuesta' => $encuesta->id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-success">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos de la encuesta
            </h4>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Encuesta</b>
                <span class="pull-right">
                  <a href="{{ route('encuestas.show', ['encuesta' => $encuesta->id]) }}">{{ $encuesta->titulo }}</a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  <a href="{{ route('usuarios.show', ['usuario' => $usuario->id]) }}">{{ $usuario->nombres }} {{ $usuario->apellidos }}</a>
                </span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Respuestas</h3>
          </div>
          <div class="box-body">
            <table class="table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Pregunta</th>
                  <th class="text-center">Respuesta</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($respuestas as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $d->pregunta->pregunta }}</td>
                    <td>{{ $d->opcion->opcion }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Respuesta</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('respuestas.destroy', ['encuesta' => $encuesta->id, 'usuario' => $usuario->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar esta Respuesta?</h4><br>

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
