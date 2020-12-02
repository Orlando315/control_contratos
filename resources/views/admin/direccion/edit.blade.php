@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Direcciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Direcciones</li>
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
          <h5>Editar dirección</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.direccion.update', ['direccion' => $direccion->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('ciudad') ? ' has-error' : '' }}">
                  <label for="ciudad">Ciudad:</label>
                  <input id="ciudad" class="form-control" type="text" name="ciudad" maxlength="50" value="{{ old('ciudad', $direccion->ciudad) }}" placeholder="Ciudad">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('comuna') ? ' has-error' : '' }}">
                  <label for="comuna">Comuna:</label>
                  <input id="comuna" class="form-control" type="text" name="comuna" maxlength="50" value="{{ old('comuna', $direccion->comuna) }}" placeholder="Comuna">
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
              <label for="direccion">Dirección: *</label>
              <input id="direccion" class="form-control" type="text" name="direccion" maxlength="200" value="{{ old('direccion', $direccion->direccion) }}" placeholder="Dirección" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.'.$direccion->type().'.show', [$direccion->type() => $direccion->direccionable_id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
