@extends('layouts.app')

@section('title', 'Preguntas - '.config('app.name'))
@section('header', 'Preguntas')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('encuestas.show', ['encuesta' => $pregunta->encuesta_id]) }}">Encuesta</a></li>
    <li>Preguntas</li>
	  <li class="active"> Pregunta </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('encuestas.show', ['encuesta' => $pregunta->encuesta_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('preguntas.edit', ['pregunta' => $pregunta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos de la pregunta
            </h4>
            <p class="text-muted text-center">{{ $pregunta->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  <a href="{{route('usuarios.show', ['usuario' => $pregunta->user_id])}}" title="Ver usuario">
                    {{ $pregunta->usuario->nombres }} {{ $pregunta->usuario->apellidos }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Pregunta</b>
                <span class="pull-right">{{ $pregunta->pregunta }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Opciones</h3>
            @if($pregunta->opciones()->count() < 4)
            <span class="pull-right">
              <a class="btn btn-flat btn-success" href="{{ route('opciones.create', ['pregunta'=>$pregunta->id]) }}">Agregar Opción</a>
            </span>
            @endif
          </div>
          <div class="box-body">
            <table class="table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Opción</th>
                  <th class="text-center">Respuesta (%)</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($pregunta->opciones()->get() as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $d->opcion }}</td>
                    <td>{{ $d->respuestas->count().' ('.$d->portencaje().'%)' }}</td>
                    <td>
                      <a class="btn btn-sm btn-flat btn-success" href="{{ route('opciones.edit', ['opcion' => $d->id]) }}"><i class="fa fa-pencil"></i></a>
                      <button class="btn btn-sm btn-flat btn-danger" data-toggle="modal" data-target="#deleteOpcionModal" data-id="{{ $d->id }}"><i class="fa fa-times"></i></button>
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
          <h4 class="modal-title" id="delModalLabel">Eliminar Pregunta</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('preguntas.destroy', [$pregunta->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar esta Pregunta?</h4><br>

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

  <div id="deleteOpcionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteOpcionModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="deleteOpcionModalLabel">Eliminar Opción</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="deleteOpcionForm" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar esta Opción?</h4><br>

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

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#deleteOpcionModal').on('show.bs.modal', function (e) {
        let btn = $(e.relatedTarget),
            id  = btn.data('id');

        $('#deleteOpcionForm').attr('action', '{{ route("opciones.index") }}/' + id)
      })
    })
  </script>
@endsection
