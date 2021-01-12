@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Covid-19</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.covid19.index') }}">Covid-19</a></li>
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
          <h5>Editar pregunta</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.manage.covid19.update', ['pregunta' => $pregunta->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('pregunta') ? ' has-error' : '' }}">
              <label for="pregunta">Pregunta: *</label>
              <input id="pregunta" class="form-control" type="text" name="pregunta" maxlength="250" value="{{ old('pregunta', $pregunta->pregunta) }}" placeholder="Pregunta" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.manage.covid19.show', ['pregunta' => $pregunta->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
