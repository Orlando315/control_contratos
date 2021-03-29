@extends('layouts.app')

@section('title', 'Variables')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Variables</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.plantilla.documento.index') }}">Plantillas</a></li>
        <li class="breadcrumb-item">Variables</li>
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
          <h5>Agregar variable</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.variable.store') }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label class="control-label" for="nombre">Nombre de la variable: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                  <label class="control-label" for="valor">Tipo: *</label>
                  <select id="tipo" class="form-control" name="tipo" required>
                    <option value="text"{{ old('tipo') == 'text' ? ' selected' : '' }}>Texto</option>
                    <option value="date"{{ old('tipo') == 'date' ? ' selected' : '' }}>Fecha</option>
                    <option value="number"{{ old('tipo') == 'number' ? ' selected' : '' }}>Numerico</option>
                    <option value="email"{{ old('tipo') == 'email' ? ' selected' : '' }}>Email</option>
                    <option value="rut"{{ old('tipo') == 'rut' ? ' selected' : '' }}>RUT</option>
                    <option value="firma"{{ old('tipo') == 'firma' ? ' selected' : '' }}>Firma</option>
                  </select>
                </div>
              </div>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.documento.index') }}"><i class="fa fa-reply"></i> Atras</a>
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
    $(document).ready(function () {
      $('#tipo').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccionar...',
      })
    })
  </script>
@endsection
