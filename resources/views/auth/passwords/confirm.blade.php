@extends('layouts.blank')

@section('title', 'Confirmar contraseña')

@section('content')
  <div class="passwordBox animated fadeInDown">
    <div class="row">
      <div class="col-md-12">
        <div class="ibox-content">
          <h2 class="font-bold">Confirmar contraseña</h2>
          <p>Por favor confirme su contraseña antes de continuar.</p>
          <div class="row">
            <div class="col-lg-12">
              <form action="{{ route('password.confirm') }}" method="POST">
                @csrf

                <div class="form-group">
                  <input class="form-control" type="password" name="password" required autocomplete="current-password">
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
