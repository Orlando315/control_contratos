@extends('layouts.app')

@section('title', 'Respuesta')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Covid-19</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empresa.covid19.index') }}">Covid-19</a></li>
        <li class="breadcrumb-item active"><strong>Respuesta</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.empresa.covid19.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @permission('covid19-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
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
              <b>Fecha</b>
              <span class="pull-right">{{ $respuesta->created_at->format('d-m-Y H:i:s') }}</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ $respuesta->user->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $respuesta->user->nombre() }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox-title">
        <h5><i class="fa fa-heartbeat"></i> Preguntas</h5>
      </div>
      <div class="ibox-content">
        <table class="table table-bordered table-striped">
          <tbody>
            @foreach($preguntas as $pregunta)
              <tr>
                <td>{{ $pregunta->pregunta }}</td>
                <td>
                  {!! $respuesta->getRespuesta($pregunta->id) !!}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @permission('covid19-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.empresa.covid19.destroy', ['respuesta' => $respuesta->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Respuesta</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Respuesta a la encuesta Covid-19?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection
