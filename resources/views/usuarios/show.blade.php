@extends('layouts.app')

@section('title', 'Usuario')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
        <li class="breadcrumb-item active"><strong>Usuario</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('usuarios.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('usuarios.edit', ['usuario' => $usuario->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @if(Auth::user()->tipo <= 2)
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock" aria-hidden="true"></i> Cambiar contraseña</button>
      @endif
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">
                {{ $usuario->tipo() }}
              </span>
            </li>
            @if($usuario->empleado_id)
              <li class="list-group-item">
                <b>Empleado</b>
                <span class="pull-right">
                  <a href="{{ route('empleados.show', ['empleado'=> $usuario->empleado_id]) }}">Ver pefil</a>
                </span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ $usuario->nombres }}</span>
            </li>
            <li class="list-group-item">
              <b>Apellidos</b>
              <span class="pull-right">{{ $usuario->apellidos }}</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right"> {{ $usuario->rut }} </span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right"> {{ $usuario->telefono }} </span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">{{ $usuario->email }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $usuario->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="passModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('usuarios.password', ['usuario' => $usuario->id]) }}" method="POST">
          {{ method_field('PATCH') }}
          {{ csrf_field() }}

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

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('usuarios.destroy', ['usuario' => $usuario->id]) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}

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
@endsection
