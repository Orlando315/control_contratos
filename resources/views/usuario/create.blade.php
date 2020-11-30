@extends('layouts.blank')

@section('title', 'Registro')

@section('content')
  <div class="container py-4 animated fadeInDown">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="ibox-content">
          <div class="text-center">
            <img src="{{ asset('images/logo-small.png') }}" alt="Vertrag" style="max-width: 250px">
          </div>

          <h3>Registro de empresa</h3>

          <form action="{{ route('empresas.store') }}" method="POST">

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                  <label for="nombres">Nombre: *</label>
                  <input id="nombres" class="form-control" type="text" name="nombres" maxlength="100" value="{{ old('nombres') }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dkK])$" value="{{ old('rut') }}" placeholder="RUT" required>
                  <span class="help-block">Ejemplo: 00000000-0</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('representante') ? ' has-error' : '' }}">
                  <label for="representante">Representante: *</label>
                  <input id="representante" class="form-control" type="text" name="representante" maxlength="100" value="{{ old('representante') }}" placeholder="Representante" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email: *</label>
                  <input id="email" class="form-control" type="email" name="email" maxlength="50" value="{{ old('email') }}" placeholder="Email" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono: *</label>
                  <input id="telefono" class="form-control" type="text" name="telefono" maxlength="20" value="{{ old('telefono') }}" placeholder="Teléfono" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('jornada') ? ' has-error' : '' }}">
                  <label for="jornada">Jornada: *</label>
                  <select id="jornada" class="custom-select" name="jornada" required>
                    <option value="">Seleccione...</option>
                    <option value="5x2" {{ old('jornada') == '5x2' ? 'selected' : '' }}>5x2</option>
                    <option value="4x3" {{ old('jornada') == '4x3' ? 'selected' : '' }}>4x3</option>
                    <option value="6x1" {{ old('jornada') == '6x1' ? 'selected' : '' }}>6x1</option>
                    <option value="7x7" {{ old('jornada') == '7x7' ? 'selected' : '' }}>7x7</option>
                    <option value="10x10" {{ old('jornada') == '10x10' ? 'selected' : '' }}>10x10</option>
                    <option value="12x12" {{ old('jornada') == '12x12' ? 'selected' : '' }}>12x12</option>
                    <option value="20x10" {{ old('jornada') == '20x10' ? 'selected' : '' }}>20x10</option>
                    <option value="7x14" {{ old('jornada') == '7x14' ? 'selected' : '' }}>7x14</option>
                    <option value="14x14" {{ old('jornada') == '14x14' ? 'selected' : '' }}>14x14</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="password">Contraseña: *</label>
                  <input id="password" class="form-control" type="password" name="password" minlength="6" placeholder="Contraseña" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                  <label for="password_confirmation">Verificar contraseña: *</label>
                  <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" minlength="6" placeholder="Verificar contraseña" required>
                </div>
              </div>
            </div>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <button type="submit" class="btn btn-primary block full-width m-b">Registrarse</button>

            <p class="text-muted text-center"><small>¿Ya tienes una cuenta?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="{{ route('login.view') }}">Iniciar sesión</a>
          </form>

          <p class="m-t"><small>{{ config('app.name') }} &copy; {{ date('Y') }}</small></p>
        </div>
      </div>
    </div>
  </div>
@endsection
