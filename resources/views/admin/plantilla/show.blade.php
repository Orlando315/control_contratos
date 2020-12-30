@extends('layouts.app')

@section('title', 'Plantilla')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Plantillas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.plantilla.documento.index') }}">Plantillas</a></li>
        <li class="breadcrumb-item active"><strong>Plantilla</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('plantilla-documento-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.documento.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('plantilla-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.edit', ['plantilla' => $plantilla->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('plantilla-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $plantilla->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Secciones</b>
              <span class="pull-right">{{ $plantilla->secciones->count() }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $plantilla->created_at->format('d-m-Y H:i:s') }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      @foreach($plantilla->secciones as $seccion)
        <div class="ibox">
          <div class="ibox-title">
            <h5>Sección #{{ $loop->iteration }}: @nullablestring($seccion->nombre)</h5>
          </div>
          <div class="ibox-content">
            {!! $seccion->contenido !!}
          </div><!-- /.box-body -->
        </div>
      @endforeach
    </div>
  </div>

  @permission('plantilla-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.plantilla.destroy', ['plantilla' => $plantilla->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Plantilla</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Plantilla?</h4>
              <p class="text-center m-0">Se eliminaran todos los documentos generados por esta plantilla</p>
              <p class="text-center m-0">Esta acción no se puede deshacer</p>
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
