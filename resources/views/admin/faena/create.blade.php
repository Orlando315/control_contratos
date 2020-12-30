@extends('layouts.app')

@section('title', 'Faenas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Faenas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Faenas</a></li>
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
          <h5>Agregar faena</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.faena.store') }}" method="POST">
            @csrf

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label class="control-label" for="nombre">Nombre: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
