@extends('layouts.app')

@section('title', 'Modulos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Modulos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.development.modulo.index') }}">Modulos</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar modulo</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.development.modulo.store') }}" method="POST">
            @csrf

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="control-label" for="name">Name: *</label>
              <input id="name" class="form-control" type="text" name="name" maxlength="50" value="{{ old('name') }}" placeholder="Name" required>
            </div>

            <div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
              <label class="control-label" for="display_name">Display: </label>
              <input id="display_name" class="form-control" type="text" name="display_name" maxlength="50" value="{{ old('display_name') }}" placeholder="Display">
            </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              <label class="control-label" for="description">Description:</label>
              <input id="description" class="form-control" type="text" name="description" maxlength="100" value="{{ old('description') }}" placeholder="Description">
            </div>

            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input id="crud" class="custom-control-input" type="checkbox" name="crud" value="1"{{ old('crud') == '1' ? ' checked' : '' }}>
                <label class="custom-control-label" for="crud">
                  Crear permisos CRUD
                </label>
              </div>
              <small class="form-text text-muted">Se usará el name del Modulo y se creará: -index, -view, -create, -edit, -delete</small>
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

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.development.modulo.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
