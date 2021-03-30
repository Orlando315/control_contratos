@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('perfil.edit') }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock" aria-hidden="true"></i> Cambiar contraseña</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ Auth::user()->nombres }}</span>
            </li>
            <li class="list-group-item">
              <b>Apellidos</b>
              <span class="pull-right">@nullablestring(Auth::user()->apellidos)</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ Auth::user()->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring(Auth::user()->email)</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring(Auth::user()->telefono)</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="passModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('perfil.password') }}" method="POST">
          @method('PATCH')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title">Cambiar contraseña</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="password">Contraseña nueva: *</label>
              <input id="password" class="form-control" type="password" pattern=".{6,}" name="password" required>
              <p class="form-text">Debe contener al menos 6 caracteres.</p>
            </div>

            <div class=" form-group">
              <label for="password_confirmation">Verificar: *</label>
              <input id="password_confirmation" class="form-control" type="password" pattern=".{6,}" name="password_confirmation" required>
              <p class="form-text">Debe contener al menos 6 caracteres.</p>
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
            <button class="btn btn-warning btn-sm" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection