<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Registro | {{ config( 'app.name' ) }}</title>
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
  <body class="login-page">
    <!-- Formulario -->
    <div class="row" style="margin: 30px 0">
      <div class="col-md-4 col-md-offset-4">
        <div class="register-box-body">
          <form class="" action="{{ route('empresas.store') }}" method="POST">
            {{ csrf_field() }}

            <h3>Registro de empresa</h3>

            <div class="form-group {{ $errors->has('nombres') ? 'has-error' : '' }}">
              <label class="control-label" for="nombres">Nombre: *</label>
              <input id="nombres" class="form-control" type="text" name="nombres" maxlength="100" value="{{ old( 'nombres' ) ? old( 'nombres' ) : '' }}" placeholder="Nombre" required>
            </div>

            <div class="form-group {{ $errors->has('rut') ? 'has-error' : '' }}">
              <label class="control-label" for="rut">RUT: *</label>
              <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" value="{{ old( 'rut' ) ? old( 'rut' ) : '' }}" placeholder="RUT" required>
              <span class="help-block">Ejemplo: 00000000-0</span>
            </div>

            <div class="form-group {{ $errors->has('representante') ? 'has-error' : '' }}">
              <label class="control-label" for="representante">Representante: *</label>
              <input id="representante" class="form-control" type="text" name="representante" maxlength="100" value="{{ old('representante') ? old('representante') : '' }}" placeholder="Representante" required>
            </div>

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
              <label class="control-label" for="email">Email: *</label>
              <input id="email" class="form-control" type="email" name="email" maxlength="50" value="{{ old('email') ? old('email') : '' }}" placeholder="Email" required>
            </div>

            <div class="form-group {{ $errors->has('telefono') ? 'has-error' : '' }}">
              <label class="control-label" for="telefono">Teléfono: *</label>
              <input id="telefono" class="form-control" type="text" name="telefono" maxlength="20" value="{{ old('telefono') ? old('telefono') : '' }}" placeholder="Teléfono" required>
            </div>

            <div class="form-group {{ $errors->has('jornada') ? 'has-error' : '' }}">
              <label class="control-label" class="form-control" for="jornada">Jornada: *</label>
              <select id="jornada" class="form-control" name="jornada" required>
                <option value="">Seleccione...</option>
                <option value="5x2" {{ old('jornada') == '5x2' ? 'selected' : '' }}>5x2</option>
                <option value="4x3" {{ old('jornada') == '4x3' ? 'selected' : '' }}>4x3</option>
                <option value="6x1" {{ old('jornada') == '6x1' ? 'selected' : '' }}>6x1</option>
                <option value="7x7" {{ old('jornada') == '7x7' ? 'selected' : '' }}>7x7</option>
                <option value="10x10" {{ old('jornada') == '10x10' ? 'selected' : '' }}>10x10</option>
                <option value="12x12" {{ old('jornada') == '12x12' ? 'selected' : '' }}>12x12</option>
                <option value="20x10" {{ old('jornada') == '20x10' ? 'selected' : '' }}>20x10</option>
              </select>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
              <label class="control-label" for="password">Contraseña: *</label>
              <input id="password" class="form-control" type="password" name="password" minlength="6" value="{{ old('password') ? old('password') : '' }}" placeholder="Contraseña" required>
            </div>

            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
              <label class="control-label" for="password_confirmation">Verificar contraseña: *</label>
              <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" minlength="6" value="{{ old('password_confirmation') ? old('password_confirmation') : '' }}" placeholder="Verificar contraseña" required>
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
              <a class="btn btn-flat btn-default" href="{{ route( 'login.view' ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- jQuery 2.1.4 -->
    <script type="text/javascript" src="{{ asset( 'js/jQuery-2.1.4.min.js' ) }}"></script>
    <script type="text/javascript">
      $(document).ready( function(){

      });
    </script>
  </body>
</html>
