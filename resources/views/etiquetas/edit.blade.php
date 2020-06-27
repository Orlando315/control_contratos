@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Etiquetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('etiquetas.index') }}">Etiquetas</a></li>
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
          <h5>Editar etiqueta</h5>          
        </div>
        <div class="ibox-content">
          <form action="{{ route('etiquetas.update', ['etiqueta' => $etiqueta->id]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group {{ $errors->has('etiqueta') ? 'has-error' : '' }}">
              <label class="control-label" for="etiqueta">Etiqueta: *</label>
              <input id="etiqueta" class="form-control" type="text" name="etiqueta" maxlength="50" value="{{ old('etiqueta') ?? $etiqueta->etiqueta }}" placeholder="Etiqueta" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('etiquetas.show', ['etiqueta' => $etiqueta->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
