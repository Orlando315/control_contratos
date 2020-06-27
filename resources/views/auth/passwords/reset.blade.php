@extends('layouts.blank')

@section('title', 'Reestablecer contraseña')

@section('content')
  <div class="passwordBox animated fadeInDown">
    <div class="row">
      <div class="col-md-12">
        <div class="ibox-content">
          <h2 class="font-bold">Reestablecer contraseña</h2>
          <p>Ingresa su nueva contraseña.</p>
          <div class="row">
            <div class="col-lg-12">
              <form action="{{ route('password.update') }}" method="POST">
                <input type="hidden" name="token" value="{{ $token }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required autofocus>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' is-invalid' : '' }}">
                  <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required>
                </div>

                <div class="form-group">            
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar contraseña" required>
                </div>

                @if(count($errors) > 0)
                  <div class="alert alert-danger">
                    <ul class="m-0">
                    @foreach( $errors->all() as $error )
                      <li>{{ $error }}</li>
                    @endforeach
                    </ul>  
                  </div>
                @endif
                
                @if(session('status'))
                  <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                  </div>
                @endif

                @if(Session::has('flash_message'))
                  <div class="alert {{ Session::get('flash_class') }}">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong class="text-center">{{ Session::get('flash_message') }}</strong> 
                  </div>
                @endif

                <button class="btn btn-primary block full-width m-b" type="submit">Enviar</button>
                <p class="m-0 text-center"><small><a href="{{ route('login.view') }}">Volver al login</a></small></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr/>
    <div class="row">
      <div class="col-md-6">
        <p class="m-t"><small>{{ config('app.name') }} &copy; {{ date('Y') }}</small></p>
      </div>
    </div>
  </div>
@endsection
