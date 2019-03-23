<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> 404 | {{ config( 'app.name' ) }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset( 'img/logo.png' ) }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/bootstrap.min.css' ) }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/font-awesome.min.css' ) }}">
    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/AdminLTE.min.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/glyphicons.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/_all-skins.min.css' ) }}">
  </head>

  <body class="hold-transition login-page">
    <div class="login-logo">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="{{ config('app.name') }}" style="height:120px"></center>
    </div><!-- /.login-logo -->
      <div class="error-page">
        <h2 class="headline text-yellow">404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Página no encontrada.</h3>

          <p>
            No pudimos encontrar la página que estas buscando.
            Podrías <a href="{{ route( 'dashboard' ) }}">volver al Inicio</a>.
          </p>

        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
  </body>
</html>
