@extends('layouts.app')

@section('title', 'Carpetas')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <style type="text/css">
    .switch .onoffswitch-inner:before{
      content: 'Sí';
    }
    .switch .onoffswitch-inner:after{
      content: 'No';
    }
  </style>
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Carpetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('archivo.index') }}">Archivo</a></li>
        <li class="breadcrumb-item">Carpetas</li>
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
          <h5>Agregar carpeta</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.archivo.carpeta.store', ['carpeta' => optional($carpeta)->id]) }}" method="POST">
            @csrf

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label for="nombre">Nombre: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre de la carpeta" required>
            </div>

            @if(!$carpeta)
              <div class="form-group{{ $errors->has('publica') ? ' has-error' : '' }}">
                <label for="publica">Pública:</label>

                <div class="switch mb-3">
                  <div class="onoffswitch">
                    <input id="check-publica" class="onoffswitch-checkbox" type="checkbox" name="publica" value="1"{{ old('publica', 1) ? ' checked' : '' }}>
                    <label class="onoffswitch-label" for="check-publica">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                    </label>
                  </div>
                </div>
                <span class="form-text text-muted">Todos los usuarios tendrán acceso, y las carpetas y documentos en ella también serán públicos.</span>
              </div>
            @else
              @if($carpeta->isPublic())
                <p class="text-center">La carpeta será pública ya que la carpeta padre también es pública.</p>
              @endif

              @if($carpeta->isPrivate())
                <p class="text-center">La carpeta será privada ya que la carpeta padre también es privada.</p>
              @endif
            @endif

            @if(!$carpeta || ($carpeta && $carpeta->isPrivate()))
              <div class="form-group{{ $errors->has('usuarios') ? ' has-error' : '' }}">
                <label for="usuarios">Usuarios:</label>
                <select id="usuarios" class="form-control" name="usuarios[]" multiple style="width: 100%">
                  <option value="">Seleccione...</option>
                  @foreach($users as $user)
                    <option value="{{ $user->id }}"{{ in_array($user->id, old('usuarios', [])) ? ' selected' : '' }}>{{ $user->nombre() }}</option>
                  @endforeach
                </select>

                <span class="form-text text-muted">Usuarios que tendán acceso a la carpeta.</span>
                @if($carpeta)
                  <span class="form-text text-muted">Solo se mostrarán los Usuarios que tengan acceso a la carpeta padre de esta.</span>
                @endif
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
              <a class="btn btn-default btn-sm" href="{{ route(($carpeta ? 'carpeta.show' : 'archivo.index'), ($carpeta ? ['carpeta' => $carpeta->id] : [])) }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('#usuarios').select2({
        allowClear: true,
        theme: 'bootstrap4',
        placeholder: 'Seleccionar...',
      });

      @if(!$carpeta)
        $('#check-publica').change(function () {
          let isChecked = $(this).is(':checked');

          $('#usuarios').prop('disabled', isChecked).closest('.form-group').toggle(!isChecked);
        });
        $('#check-publica').change();
      @endif
    });
  </script>
@endsection
