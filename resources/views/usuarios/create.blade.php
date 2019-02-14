@extends( 'layouts.app' )
@section( 'title', 'Usuarios - '.config( 'app.name' ) )
@section( 'header','Usuarios' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('usuarios.store') }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar administrador</h4>

        <div class="form-group {{ $errors->has('nombres') ? 'has-error' : '' }}">
          <label class="control-label" for="nombres">Nombres: *</label>
          <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres') ? old('nombres') : '' }}" placeholder="Nombres" required>
        </div>

        <div class="form-group {{ $errors->has('apellidos') ? 'has-error' : '' }}">
          <label class="control-label" for="apellidos">Apellidos: *</label>
          <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos') ? old('apellidos') : '' }}" placeholder="Apellidos" required>
        </div>

        <div class="form-group {{ $errors->has('rut') ? 'has-error' : '' }}">
          <label class="control-label" for="rut">RUT: *</label>
          <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" value="{{ old('rut') ? old('rut') : '' }}" placeholder="RUT" required>
          <span class="help-block">Ejemplo: 00000000-0</span>
        </div>

        <div class="form-group {{ $errors->has('telefono') ? 'has-error' : '' }}">
          <label class="control-label" for="telefono">Teléfono: *</label>
          <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono') ? old('telefono') : '' }}" placeholder="Teléfono">
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
          <label class="control-label" for="email">Email: *</label>
          <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email') ? old('email') : '' }}" placeholder="Email">
        </div>

        @if (count($errors) > 0)
        <div class="alert alert-danger alert-important">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>  
        </div>
        @endif

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ route('usuarios.index') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection