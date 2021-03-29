@extends('layouts.app')

@section('title', 'Modulo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Modulo</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.development.modulo.index') }}">Modulos</a></li>
        <li class="breadcrumb-item active"><strong>Modulo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.development.modulo.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.development.modulo.edit', ['modulo' => $modulo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox ibox-with-footer">
        <div class="ibox-title">
          <h5><i class="fa fa-info-circle"></i> Información</h5>
        </div>
        <div class="ibox-content py-2">
          <ul class="list-group list-group-flush">
            <li class="list-group-item px-0">
              <strong>Name</strong>
              <p class="m-0">{{ $modulo->name }}</p>
            </li>
            <li class="list-group-item px-0">
              <strong>Display</strong>
              <p class="m-0">@nullablestring($modulo->display_name)</p>
            </li>
            <li class="list-group-item px-0">
              <strong>Description</strong>
              <p class="m-0">@nullablestring($modulo->description)</p>
            </li>
          </ul>
        </div>
        <div class="ibox-footer text-center">
          <small class="text-muted">{{ $modulo->created_at }}</small>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-key" aria-hidden="true"></i> Permissions</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Name</th>
                <th class="text-center">Display</th>
                <th class="text-center">Description</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($modulo->permissions as $permission)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td><a href="{{ route('admin.development.permission.show', ['permission' => $permission->id]) }}">{{ $permission->name }} </a></td>
                  <td>{{ $permission->display_name }}</td>
                  <td>@nullablestring($permission->description)</td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.development.permission.show', ['permission' => $permission->id]) }}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.development.permission.edit', ['permission' => $permission->id]) }}"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div><!-- .row -->

  <div id="delModal" class="modal inmodal fade" tabindex="-1" modulo="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" modulo="document">
      <div class="modal-content">
        <form action="{{ route('admin.development.modulo.destroy', ['modulo' => $modulo->id]) }}" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Modulo</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Modulo?</h4>
            <p class="text-center">Para confirmar esta acción, introduzca su contraseña</p>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password">Contraseña: *</label>
              <input id="password" class="form-control" type="password" name="password" placeholder="Contraseña" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
