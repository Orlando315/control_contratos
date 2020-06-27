@extends('layouts.blank')

@section('title', 'Login')

@section('content')
  <div class="middle-box loginscreen animated fadeInDown">
    <div>
      <div class="text-center">
        <img src="{{ asset('images/logo-small.png') }}" alt="Vertrag" style="max-width: 250px">
      </div>
      <h3 class="text-center">Bienvenido a {{ config('app.name') }}</h3>
      <p class="text-center">Iniciar Sesión</p>

      <form class="m-t" role="form" action="{{ route('login.auth') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
          <input class="form-control" name="usuario" type="text" placeholder="RUT" required>
        </div>
        <div class="form-group">
          <input class="form-control" name="password" type="password" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary block full-width m-b">Ingresar</button>

        @if(count($errors) > 0)
          <div class="alert alert-danger">
            <ul class="m-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>  
          </div>
        @endif

        @if(Session::has('flash_message'))
          <div class="alert {{ Session::get('flash_class') }}">
            <strong class="text-center">{{ Session::get('flash_message') }}</strong> 
          </div>
        @endif

        <div class="text-center">
          <a href="{{ route('password.request') }}"><small>Recuperar contraseña</small></a>
          <p class="text-muted"><small>¿No tienes cuenta?</small></p>
          <a class="btn btn-sm btn-white btn-block" href="{{ route('empresas.create') }}">¡Registrate!</a>
        </div>
      </form>
      <p class="m-t"><small>{{ config('app.name') }} &copy; {{ date('Y') }}</small></p>
    </div>
  </div>
@endsection
