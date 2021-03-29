@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ayudas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.ayuda.index') }}">Ayudas</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar ayuda</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.manage.ayuda.update', ['ayuda' => $ayuda->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('titulo') ? ' has-error' : '' }}">
              <label for="titulo">Título: *</label>
              <input id="titulo" class="form-control" type="text" name="titulo" maxlength="250" value="{{ old('titulo', $ayuda->titulo) }}" placeholder="Título" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('video') ? ' has-error' : '' }}">
                  <label for="video">Video ID:</label>
                  <input id="video" class="form-control" type="text" name="video" minlength="11" maxlength="11" value="{{ old('video', $ayuda->video) }}" placeholder="Video ID">
                  <small class="text-muted">ID del video en Youtube: https://www.youtube.com/watch?v=<strong>ID</strong> | https://youtu.be/<strong>ID</strong></small>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Roles:</label>
              
              <div class="row">
                @foreach($roles as $role)
                  <div class="col-md-4">
                    <div class="custom-control custom-checkbox">
                      <input id="role-{{ $role->id }}" class="custom-control-input" type="checkbox" name="roles[]" value="{{ $role->id }}"{{ $ayuda->roles->contains($role->id) ? ' checked' : '' }}>
                      <label class="custom-control-label" for="role-{{ $role->id }}" title="{{ $role->name() }}">
                        {{ $role->name() }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
              <label for="status">Activar ayuda:</label>

              <div class="custom-control custom-switch">
                <input id="status" class="custom-control-input" type="checkbox" name="status" value="1"{{ old('status', $ayuda->isActive()) ? ' checked' : '' }}>
                <label class="custom-control-label" for="status">Activar ayuda</label>
              </div>
              <span class="form-text text-muted">Determina si la ayuda se mostrará a los usuarios.</span>
            </div>

            <div class="form-group{{ $errors->has('contenido') ? ' has-error' : '' }}">
              <label for="contenido">Contenido: *</label>
              <textarea id="contenido" class="form-control" name="contenido">{{ old('contenido', $ayuda->contenido) }}</textarea>
            </div>

            @if($errors->any())
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.manage.ayuda.show', ['ayuda' => $ayuda->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- CKEditor -->
  <script type="text/javascript" src="{{ asset('js/plugins/ckeditor/ckeditor.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      CKEDITOR.replace('contenido', {
        language: 'es',
      });
    });
  </script>
@endsection
