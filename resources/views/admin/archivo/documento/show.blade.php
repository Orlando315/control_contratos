@extends('layouts.app')

@section('title', 'Documento')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('archivo.index') }}">Archivo</a></li>
        <li class="breadcrumb-item active"><strong>Documento</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ $documento->backArchivoUrl }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @permission('archivo-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.archivo.documento.edit', ['documento' => $documento->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('archivo-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $documento->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Observación</b>
              <span class="pull-right">@nullablestring($documento->observacion)</span>
            </li>
            <li class="list-group-item">
              <b>Mime</b>
              <span class="pull-right">{{ $documento->mime }}</span>
            </li>
            <li class="list-group-item">
              <b>Vencimiento</b>
              <span class="pull-right">@nullablestring($documento->vencimiento)</span>
            </li>
            <li class="list-group-item">
              <b>Público</b>
              <span class="pull-right">{!! $documento->isPublic(true) !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $documento->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9 p-0">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-users"></i> Usuarios con acceso</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombres</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">RUT</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($documento->archivoUsers as $usuario)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $usuario->nombres }}</td>
                  <td>@nullablestring($usuario->apellidos)</td>
                  <td>{{ $usuario->rut }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @permission('archivo-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.archivo.documento.destroy', ['documento' => $documento->id]) }}" method="POST">
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
