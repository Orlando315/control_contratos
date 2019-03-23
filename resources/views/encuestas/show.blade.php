@extends('layouts.app')

@section('title', 'Encuesta - '.config('app.name'))
@section('header', 'Encuesta')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('encuestas.index') }}">Encuestas</a></li>
	  <li class="active"> Encuesta </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('encuestas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('encuestas.edit', [$encuesta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos de la encuesta
            </h4>
            <p class="text-muted text-center">{{ $encuesta->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  <a href="{{ route('usuarios.show', ['usuario' => $encuesta->user_id]) }}">{{ $encuesta->usuario->nombres }} {{ $encuesta->usuario->apellidos }}</a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Título</b>
                <span class="pull-right">{{ $encuesta->titulo }}</span>
              </li>
              <li class="list-group-item">
                <b>Preguntas</b>
                <span class="pull-right">{{ $encuesta->preguntas->count() }}</span>
              </li>
              <li class="list-group-item">
                <b>Respuestas</b>
                <span class="pull-right">{{ $encuesta->respuestas()->groupBy('user_id')->get()->count() }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Preguntas</h3>

            <span class="pull-right">
              <a class="btn btn-success btn-flat" href="{{ route('preguntas.create', ['encuesta' => $encuesta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Pregunta</a>
            </span>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-striped table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Pregunta</th>
                  <th class="text-center">Respuestas</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($encuesta->preguntas()->get() as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $d->pregunta }}</td>
                    <td style="padding: 0">
                      <table class="table table-condensed" style="width: 100%; margin: 0; background-color: transparent;">
                        @foreach($d->opciones()->get() as $opcion)
                        <tr>
                          <td class="text-left">{{ $opcion->opcion }}</td>
                          <td class="text-right">{{ $opcion->respuestas->count().' ('.$opcion->portencaje().'%)' }}</td>
                        </tr>
                        @endforeach
                      </table>
                    </td>
                    <td>
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('preguntas.show', ['pregunta' => $d->id] )}}" title="Ver pregunta"><i class="fa fa-search"></i></a>
                      <a class="btn btn-success btn-flat btn-sm" href="{{ route('preguntas.edit', ['pregunta' => $d->id] )}}" title="Editar pregunta"><i class="fa fa-pencil"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Respuestas por Usuarios</h3>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Usuario</th>
                  <th class="text-center">Agregada</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($respuestas as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="text-center">
                      <a href="{{route('usuarios.show', ['usuario' => $d->user_id])}}" title="Ver usuario">
                        {{ $d->usuario->nombres }} {{ $d->usuario->apellidos }}
                      </a>
                    </td>
                    <td>{{ $d->created_at }}</td>
                    <td>
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('respuestas.show', ['encuesta' => $encuesta->id, 'usuario' => $d->user_id] )}}"><i class="fa fa-search"></i></a>
                    </td>
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
          <h4 class="modal-title" id="delModalLabel">Eliminar Encuesta</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('encuestas.destroy', [$encuesta->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar esta Encuesta?</h4><br>

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
