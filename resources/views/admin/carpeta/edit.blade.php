@extends('layouts.app')

@section('title', 'Editar')

@section('head')
  @if($carpeta->isType('App\Empleado') || $carpeta->isType('App\Contrato') || $carpeta->isType('App\Transporte'))
    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  @endif
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Carpetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ $carpeta->backUrl }}">Carpeta</a></li>
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
          <h5>Editar carpeta</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.carpeta.update', ['carpeta' => $carpeta->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            @if($carpeta->isType('App\Empleado') || $carpeta->isType('App\Contrato') || $carpeta->isType('App\Transporte'))
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('requisito') ? ' has-error' : '' }}">
                    <label for="requisito">Requisitos faltantes:</label>
                    <select id="requisito" class="form-control" name="requisito" style="width: 100%">
                      <option value="">Seleccione...</option>
                      @foreach($requisitos as $requisito)
                        <option value="{{ $requisito->id }}"{{ old('requisito', $carpeta->requisito_id) == $requisito->id ? ' selected' : '' }}>{{ $requisito->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            @endif

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label for="nombre">Nombre: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $carpeta->nombre) }}" placeholder="Nombre de la carpeta" {{ $carpeta->isRequisito() ? 'readonly' : 'required' }}>
            </div>

            @if($carpeta->isTypeEmpleado())
              <div class="form-group{{ $errors->has('visibilidad') ? ' has-error' : '' }}">
                <label for="visibilidad">Visibilidad:</label>

                <div class="custom-control custom-checkbox">
                  <input id="visibilidad" class="custom-control-input" type="checkbox" name="visibilidad" value="1"{{ old('visibilidad', $carpeta->visibilidad) ? ' checked' : '' }}>
                  <label class="custom-control-label" for="visibilidad"><i class="icon-visibilidad fa fa-eye-slash" aria-hidden="true"></i> Permitir visibilidad</label>
                </div>
                <span class="form-text text-muted">Determina si el Empleado puede o no ver la Carpeta y su contenido desde su perfil.</span>
              </div>
            @endif

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
              <a class="btn btn-default btn-sm" href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if($carpeta->isType('App\Empleado') || $carpeta->isType('App\Contrato') || $carpeta->isType('App\Transporte'))
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript">
      $(document).ready( function(){
        $('#requisito').select2({
          allowClear: true,
          theme: 'bootstrap4',
          placeholder: 'Seleccionar...',
        });

        $('#requisito').change(function (){
          $('#nombre').prop('disabled', $(this).val() != '');
        })

        $('#requisito').change();

        @if($carpeta->isTypeEmpleado())
          $('#visibilidad').change(function () {
            let isChecked = $(this).is(':checked');

            $('.icon-visibilidad').toggleClass('fa-eye', isChecked);
            $('.icon-visibilidad').toggleClass('fa-eye-slash', !isChecked);
          });
          $('#visibilidad').change();
        @endif
      });
    </script>
  @endif
@endsection
