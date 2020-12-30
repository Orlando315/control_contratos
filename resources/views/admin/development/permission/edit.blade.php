@extends('layouts.app')

@section('title', 'Editar')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Permissions</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.development.permission.index') }}">Permissions</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar permission</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.development.permission.update', ['permission' => $permission->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('modulo') ? ' has-error' : '' }}">
              <label for="modulo">Modulo: *</label>
              <select id="modulo" class="form-control" name="modulo" required>
                <option value="">Seleccione...</option>
                @foreach($modulos as $modulo)
                  <option value="{{ $modulo->id }}"{{ old('modulo', $permission->modulo_id) == $modulo->id ? ' selected' : '' }}>{{ $modulo->name() }} ({{ $modulo->description ?? 'N/A' }})</option>
                @endforeach
              </select>
            </div>

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="control-label" for="name">Name: *</label>
              <input id="name" class="form-control" type="text" name="name" maxlength="50" value="{{ old('name', $permission->name) }}" placeholder="Name" required>
              <mall class="form-text text-muted">modulo-accion</mall>
            </div>

            <div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
              <label class="control-label" for="display_name">Display: </label>
              <input id="display_name" class="form-control" type="text" name="display_name" maxlength="50" value="{{ old('display_name', $permission->display_name) }}" placeholder="Display">
            </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              <label class="control-label" for="description">Description: </label>
              <input id="description" class="form-control" type="text" name="description" maxlength="100" value="{{ old('description', $permission->description) }}" placeholder="Description">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.development.permission.show', ['permission' => $permission->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#modulo').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#modulo').change();
    });
  </script>
@endsection 
