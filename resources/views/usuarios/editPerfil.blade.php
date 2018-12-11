@extends( 'layouts.app' )
@section( 'title', 'Perfil - '.config( 'app.name' ) )
@section( 'header','Perfil' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route( 'usuarios.perfil' ) }}" title="Perfil"> Perfil </a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('usuarios.updatePerfil') }}" method="POST">

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar Perfil</h4>

        <div class="form-group {{ $errors->has('nombres') ? 'has-error' : '' }}">
          <label class="control-label" for="nombres">Nombres: *</label>
          <input id="nombres" class="form-control" type="text" name="nombres" value="{{ old( 'nombres' ) ? old( 'nombres' ) : Auth::user()->nombres }}" placeholder="Nombres" required>
        </div>

        <div class="form-group {{ $errors->has('apellidos') ? 'has-error' : '' }}">
          <label class="control-label" for="apellidos">Apellidos: *</label>
          <input id="apellidos" class="form-control" type="text" name="apellidos" value="{{ old( 'apellidos' ) ? old( 'apellidos' ) : Auth::user()->apellidos }}" placeholder="Apellidos" required>
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
          <label class="control-label" for="email">Email: *</label>
          <input id="email" class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : Auth::user()->email }}" placeholder="Email" required>
        </div>

        <div class="form-group {{ $errors->has('telefono') ? 'has-error' : '' }}">
          <label class="control-label" for="telefono">Teléfono: *</label>
          <input id="telefono" class="form-control" type="text" name="telefono" value="{{ old('telefono') ? old('telefono') : Auth::user()->telefono }}" placeholder="Teléfono" required>
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
          <a class="btn btn-flat btn-default" href="{{ route( 'usuarios.perfil' ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection
