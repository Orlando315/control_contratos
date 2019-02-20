<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Recuperar contraseña | {{ config( 'app.name' ) }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset( 'css/bootstrap.min.css' ) }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset( 'css/font-awesome.css' ) }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset( 'css/AdminLTE.min.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/glyphicons.css' ) }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset( 'css/_all-skins.min.css' ) }}">
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <h3 class="text-center login-box-msg">
          Ingrese su nueva contraseña
        </h3>
        
        @if (count( $errors ) > 0)
          <div class="alert alert-danger">
            <ul>
            @foreach( $errors->all() as $error )
              <li>{{ $error }}</li>
            @endforeach
            </ul>  
          </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
          <input type="hidden" name="token" value="{{ $token }}">
          {{ csrf_field() }}

          <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required autofocus>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>

          <div class="form-group has-feedback{{ $errors->has('password') ? ' is-invalid' : '' }}">
            <input id="password" type="password" class="form-control" name="password" placeholder="Contraseña" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>

          <div class="form-group has-feedback">            
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirmar contraseña" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>

          <div class="form-group">
            <button id="b-login" type="submit" class="btn btn-primary btn-block btn-flat">Enviar</button>
          </div>
        </form> 
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
  </body>
</html>
