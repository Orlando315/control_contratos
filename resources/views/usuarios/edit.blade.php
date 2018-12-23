@extends( 'layouts.app' )
@section( 'title', 'Editar - '.config( 'app.name' ) )
@section( 'header','Editar' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('usuarios.update', ['id' => $usuario->id]) }}" method="POST">

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar usuario</h4>

        <div class="form-group {{ $errors->has('tipo') ? 'has-error' : '' }}">
          <label class="control-label" class="form-control" for="tipo">Tipo: *</label>
          <select id="tipo" class="form-control" name="tipo" required>
            <option value="">Seleccione...</option>
            <option value="3" {{ old('tipo') == '3' ? 'selected' : $usuario->tipo == 3 ? 'selected' : '' }}>Usuario</option>
            <option value="4" {{ old('tipo') == '4' ? 'selected' : $usuario->tipo == 4 ? 'selected' : '' }}>Supervisor</option>
          </select>
        </div>

        <div class="form-group {{ $errors->has('usuario') ? 'has-error' : '' }}">
          <label class="control-label" for="usuario">Usuario: *</label>
          <input id="usuario" class="form-control" type="text" name="usuario" maxlength="50" value="{{ old('usuario') ? old('usuario') : $usuario->usuario }}" placeholder="Nombres" required>
        </div>

        <div class="form-group {{ $errors->has('nombres') ? 'has-error' : '' }}">
          <label class="control-label" for="nombres">Nombres: *</label>
          <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres') ? old('nombres') : $usuario->nombres }}" placeholder="Nombres" required>
        </div>

        <div class="form-group {{ $errors->has('apellidos') ? 'has-error' : '' }}">
          <label class="control-label" for="apellidos">Apellidos: *</label>
          <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos') ? old('apellidos') : $usuario->apellidos }}" placeholder="Apellidos" required>
        </div>

        <div class="form-group {{ $errors->has('rut') ? 'has-error' : '' }}">
          <label class="control-label" for="rut">RUT: *</label>
          <input id="rut" class="form-control" type="text" name="rut" maxlength="20" pattern="^\d*$" value="{{ old('rut') ? old('rut') : $usuario->rut }}" placeholder="RUT" required>
          <span class="help-block">Solo números</span>
        </div>

        <div class="form-group {{ $errors->has('telefono') ? 'has-error' : '' }}">
          <label class="control-label" for="telefono">Teléfono: *</label>
          <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono') ? old('telefono') : $usuario->telefono }}" placeholder="Teléfono" required>
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
          <label class="control-label" for="email">Email: *</label>
          <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email') ? old('email') : $usuario->email }}" placeholder="Email" required>
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
          <a class="btn btn-flat btn-default" href="{{ route('usuarios.show', [$usuario->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection