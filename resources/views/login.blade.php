<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Login | {{ config( 'app.name' ) }}</title>
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
	      <p class="login-box-msg"></p>
	      @if (count( $errors ) > 0)
	        <div class="alert alert-danger">
	        	<ul>
	          @foreach( $errors->all() as $error )
              <li>{{ $error }}</li>
	          @endforeach
	         	</ul>  
	        </div>
	      @endif

        @if(Session::has('flash_message'))
          <div class="alert {{ Session::get( 'flash_class' ) }}">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong class="text-center">{{ Session::get( 'flash_message' ) }}</strong> 
          </div>
        @endif

	      <form action="{{ route('login.auth') }}" method="POST">
	          {{ csrf_field() }}
	        <div class="form-group has-feedback">
	          <input  class="form-control" type="text" name="usuario" placeholder="RUT">
	          <span class="glyphicon glyphicon-user form-control-feedback"></span>
	        </div>
	        <div class="form-group has-feedback">
	          <input id="password" class="form-control" type="password" name="password" placeholder="Password">
	          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
	        </div>
          <p class="text-center">¿No posees una cuenta? <a href="{{ route('empresas.create') }}" title="Registro">Registrate</a>.</p>
	        <div class="form-group">
	            <button id="b-login" type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
	        </div>
	      </form> 
	    </div><!-- /.login-box-body -->
	  </div><!-- /.login-box -->
	</body>
</html>
