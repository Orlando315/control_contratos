@extends('layouts.app')

@section('title', 'Postulante')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Postulantes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empleados.index') }}">Postulantes</a></li>
        <li class="breadcrumb-item active"><strong>Postulante</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      @permission('empleado-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.empleados.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('postulante-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.postulante.edit', ['postulante' => $postulante->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('postulante-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
      <a class="btn btn-primary btn-sm" href="{{ route('admin.empleados.create', ['postulante' => $postulante->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Generar Empleado</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ $postulante->nombres }}</span>
            </li>
            <li class="list-group-item">
              <b>Apellidos</b>
              <span class="pull-right">@nullablestring($postulante->apellidos)</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ $postulante->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring($postulante->telefono)</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring($postulante->email)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $postulante->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-file-text-o"></i> Documentos</h5>
          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.create', ['postulante' => $postulante->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo documento</a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Documento</th>
                <th class="text-center">Padre</th>
                <th class="text-center">Caducidad</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($postulante->plantillaDocumentos as $plantillaDocumento)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>@nullablestring($plantillaDocumento->nombre)</td>
                  <td>@nullablestring(optional($plantillaDocumento->padre)->nombre)</td>
                  <td class="text-center">@nullablestring(optional($plantillaDocumento->caducidad)->format('d-m-Y'))</td>
                  <td class="text-center">
                    @permission('plantilla-documento-view|plantilla-documento-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          @permission('plantilla-documento-view')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.plantilla.documento.show', ['documento' => $plantillaDocumento->id]) }}">
                                <i class="fa fa-search"></i> Ver
                              </a>
                            </li>
                          @endpermission
                          @permission('plantilla-documento-edit')
                            <li>
                              <a class="dropdown-item" href="{{ route('admin.plantilla.documento.edit', ['documento' => $plantillaDocumento->id]) }}">
                                <i class="fa fa-pencil"></i> Editar
                              </a>
                            </li>
                          @endpermission
                        </ul>
                      </div>
                    @endpermission
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @permission('postulante-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.postulante.destroy', ['postulante' => $postulante->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Postulante</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Postulante?</h4>
              <p class="text-center">Toda la información relacionada al Postulante tambien será eliminada.</p>
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
