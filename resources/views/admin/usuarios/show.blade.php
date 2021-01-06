@extends('layouts.app')

@section('title', 'Usuario')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
        <li class="breadcrumb-item active"><strong>Usuario</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      @permission('user-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.usuarios.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('user-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.usuarios.edit', ['usuario' => $usuario->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock" aria-hidden="true"></i> Cambiar contraseña</button>
      @endpermission
      @permission('user-delete')
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
              <b>Roles</b>
              <span class="pull-right">{!! $usuario->allRolesNames() !!}</span>
            </li>
            @if($usuario->empleado_id)
              <li class="list-group-item">
                <b>Empleado</b>
                <span class="pull-right">
                  <a href="{{ route('admin.empleados.show', ['empleado'=> $usuario->empleado_id]) }}">Ver perfil</a>
                </span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ $usuario->nombres }}</span>
            </li>
            <li class="list-group-item">
              <b>Apellidos</b>
              <span class="pull-right">@nullablestring($usuario->apellidos)</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ $usuario->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring($usuario->telefono)</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring($usuario->email)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $usuario->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  @permission('user-edit')
    <div id="passModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.usuarios.password', ['usuario' => $usuario->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="passModalLabel">Cambiar contraseña</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Contraseña nueva: *</label>
                <input id="password" class="form-control" type="password" pattern=".{6,}" name="password" required>
                <small class="form-text">Debe contener al menos 6 caracteres.</small>
              </div>
              <div class=" form-group">
                <label>Verificar: *</label>
                <input id="password_confirmation" class="form-control" type="password" pattern=".{6,}" name="password_confirmation" required>
                <small class="form-text">Debe contener al menos 6 caracteres.</small>
              </div>

              @if(count($errors) > 0)
                <div class="alert alert-danger alert-important">
                  <ul class="m-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                     @endforeach
                  </ul>
                </div>
              @endif

            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission

  @permission('user-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.usuarios.destroy', ['usuario' => $usuario->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Usuario</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Usuario?</h4>
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
