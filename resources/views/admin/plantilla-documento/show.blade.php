@extends('layouts.app')

@section('title', 'Documento')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.plantilla.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active"><strong>Documento</strong></li>
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
      @permission('plantilla-documento-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.documento.edit', ['documento' => $documento->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      <a class="btn btn-default btn-sm" href="{{ route('plantilla.documento.pdf', ['documento' => $documento->id]) }}"><i class="fa fa fa-file-pdf-o" aria-hidden="true"></i> Descargar</a>
      @permission('plantilla-documento-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">@nullablestring($documento->nombre)</span>
            </li>
            @if($documento->toEmpleado())
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  @permission('contrato-view')
                    <a href="{{ route('admin.contrato.show', ['contrato' => $documento->contrato_id]) }}">
                      {{ $documento->contrato->nombre }}
                    </a>
                  @else
                    {{ $documento->contrato->nombre }}
                  @endpermission
                </span>
              </li>
              <li class="list-group-item">
                <b>Empleado</b>
                <span class="pull-right">
                  @permission('empleado-view')
                    <a href="{{ route('admin.empleado.show', ['empleado' => $documento->empleado_id]) }}">
                      {{ $documento->empleado->nombre() }}
                    </a>
                  @else
                    {{ $documento->empleado->nombre() }}
                  @endpermission
                </span>
              </li>
            @else
              <li class="list-group-item">
                <b>Postulante</b>
                <span class="pull-right">
                  @permission('postulante-view')
                    <a href="{{ route('admin.postulante.show', ['postulante' => $documento->postulante_id]) }}">
                      {{ $documento->postulante->nombre() }}
                    </a>
                  @else
                    {{ $documento->postulante->nombre() }}
                  @endpermission
                </span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Plantilla</b>
              <span class="pull-right">
                @permission('plantilla-view')
                  <a href="{{ route('admin.plantilla.show', ['plantilla' => $documento->plantilla_id]) }}">
                    {{ $documento->plantilla->nombre }}
                  </a>
                @else
                  {{ $documento->plantilla->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Padre</b>
              <span class="pull-right">
                @if($documento->padre)
                  <a href="{{ route('admin.plantilla.documento.show', ['documento' => $documento->documento_id]) }}">
                    {{ $documento->padre->nombre }}
                  </a>
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Caducidad</b>
              <span class="pull-right">@nullablestring(optional($documento->caducidad)->format('d-m-Y'))</span>
            </li>
            @if($documento->toEmpleado())
              <li class="list-group-item" title="Establece si el Empleado puede o no puede ver el Documento">
                <b>Visible</b>
                <span class="pull-right">{!! $documento->isVisible(true) !!}</span>
              </li>
            @endif
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $documento->created_at->format('d-m-Y H:i:s') }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      @foreach($documento->plantilla->secciones as $seccion)
        <div class="ibox">
          <div class="ibox-title">
            <h5>Sección #{{ $loop->iteration }}: @nullablestring($seccion->nombre)</h5>
          </div>
          <div class="ibox-content">
            {!! $documento->fillSeccionVariables($seccion) !!}
          </div><!-- /.box-body -->
        </div>
      @endforeach
    </div>
  </div>

  @permission('plantilla-documento-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.plantilla.documento.destroy', ['documento' => $documento->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Documento</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4>
              <p class="text-center">Esta acción no se puede deshacer</p>
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
