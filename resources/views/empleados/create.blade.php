@extends('layouts.app')

@section('title', 'Empleados')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empleados</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}">Empleados</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar empleado</h5>          
        </div>
        <div class="ibox-content">
          <form action="{{ route('empleados.store', ['contrato' => $contrato->id]) }}" method="POST">
            {{ csrf_field() }}


            <fieldset class="mb-3">
              <legend>Datos del empleado</legend>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                    <label for="nombres">Nombres: *</label>
                    <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres') }}" placeholder="Nombres" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                    <label for="apellidos">Apellidos: *</label>
                    <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos') }}" placeholder="Apellidos" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                    <label for="rut">RUT: *</label>
                    <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dkK])$" value="{{ old('rut') }}" placeholder="RUT" required>
                    <small class="form-text">Ejemplo: 00000000-0</small>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('fecha_nacimiento') ? ' has-error' : '' }}">
                    <label for="fecha_nacimiento">Fecha de nacimiento: *</label>
                    <input id="fecha_nacimiento" class="form-control" type="text" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" placeholder="dd-mm-yyyy" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                    <label for="telefono">Teléfono:</label>
                    <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono') }}" placeholder="Teléfono">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email:</label>
                    <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email') }}" placeholder="Email">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
                    <label for="direccion">Dirección: *</label>
                    <input id="direccion" class="form-control" type="text" name="direccion" maxlength="100" value="{{ old('direccion') }}" placeholder="Dirección" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('profesion') ? ' has-error' : '' }}">
                    <label for="profesion">Profesión:</label>
                    <input id="profesion" class="form-control" type="text" name="profesion" maxlength="100" value="{{ old('profesion') }}" placeholder="Prefesión">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group{{ $errors->has('sexo') ? ' has-error' : '' }}">
                    <label for="sexo">Sexo: *</label>
                    <select id="sexo" class="form-control" name="sexo" required>
                      <option value="">Seleccione...</option>
                      <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                      <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group{{ $errors->has('talla_camisa') ? ' has-error' : '' }}">
                    <label for="talla_camisa">Talla de camisa:</label>
                    <input id="talla_camisa" class="form-control" type="text" maxlength="3" name="talla_camisa" value="{{ old('talla_camisa') }}" placeholder="Taalla de camisa">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group{{ $errors->has('talla_zapato') ? ' has-error' : '' }}">
                    <label for="talla_zapato">Talla de zapato:</label>
                    <input id="talla_zapato" class="form-control" type="number" step="0.5" max="99" min="1" name="talla_zapato" value="{{ old('talla_zapato') }}" placeholder="Talla de zapato">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group{{ $errors->has('talla_pantalon') ? ' has-error' : '' }}">
                    <label for="talla_pantalon">Talla de pantalon:</label>
                    <input id="talla_pantalon" class="form-control" type="text" maxlength="3" name="talla_pantalon" value="{{ old('talla_pantalon') }}" placeholder="Talla de pantalón">
                  </div>
                </div>
              </div>
            </fieldset>

            <fieldset class="mb-3">
              <legend>Contacto de emergencia</legend>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('nombre_emergencia') ? ' has-error' : '' }}">
                    <label for="nombre_emergencia">Nombre:</label>
                    <input id="nombre_emergencia" class="form-control" type="text" name="nombre_emergencia" maxlength="50" value="{{ old('nombre_emergencia') }}" placeholder="Nombre">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('telefono_emergencia') ? ' has-error' : '' }}">
                    <label for="telefono_emergencia">Teléfono:</label>
                    <input id="telefono_emergencia" class="form-control" type="telefono_emergencia" name="telefono_emergencia" maxlength="20" value="{{ old('telefono_emergencia') }}" placeholder="Teléfono">
                  </div>
                </div>
              </div>
            </fieldset>

            <fieldset class="mb-3">
              <legend>Datos bancarios</legend>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label for="nombre">Nombre del banco: *</label>
                    <input id="nombre" class="form-control" type="text" maxlength="50" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre del banco" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('tipo_cuenta') ? ' has-error' : '' }}">
                    <label for="tipo_cuenta">Tipo de cuenta: *</label>
                    <input id="tipo_cuenta" class="form-control" type="text" maxlength="10" name="tipo_cuenta" value="{{ old('tipo_cuenta') }}" placeholder="Tipo de cuenta" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('cuenta') ? ' has-error' : '' }}">
                    <label for="cuenta">N° de Cuenta: *</label>
                    <input id="cuenta" class="form-control" type="number" step="1" min="1" max="9999999999999999999999999" name="cuenta" value="{{ old('cuenta') }}" placeholder="N° de cuenta" required>
                  </div>
                </div>
              </div>
            </fieldset>

            <fieldset class="mb-3">
              <legend>Contrato</legend>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('inicio') ? ' has-error' : '' }}">
                    <label for="inicio">Inicio: *</label>
                    <input id="inicio" class="form-control" type="text" name="inicio" value="{{ old('inicio') }}" placeholder="dd-mm-yyyy" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('inicio_jornada') ? ' has-error' : '' }}">
                    <label for="inicio_jornada">Inicio de Jornada: *</label>
                    <input id="inicio_jornada" class="form-control" type="text" name="inicio_jornada" value="{{ old('inicio_jornada') }}" placeholder="dd-mm-yyyy" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('fin') ? ' has-error' : '' }}">
                    <label for="fin">Fin:</label>
                    <input id="fin" class="form-control" type="text" name="fin" value="{{ old('fin') }}" placeholder="dd-mm-yyyy">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('sueldo') ? ' has-error' : '' }}">
                    <label for="sueldo">Sueldo: *</label>
                    <input id="sueldo" class="form-control" type="number" step="1" min="1" max="9999999999999" name="sueldo" value="{{ old('sueldo') }}" placeholder="Sueldo" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('jornada') ? ' has-error' : '' }}">
                    <label for="jornada">Jornada:</label>
                    <select id="jornada" class="form-control" name="jornada">
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
                    <small class="form-text">Si no se selecciona, se colocara la jornada de la empresa</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                    <label for="descripcion">Descripción:</label>
                    <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion') }}" placeholder="Descripción">
                  </div>
                </div>
              </div>
            </fieldset>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      var endDate = new Date();
      endDate.setFullYear(new Date().getFullYear()-18);

      $('#fecha_nacimiento').datepicker({
        format: 'dd-mm-yyyy',
        endDate: endDate,
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#inicio, #fin, #inicio_jornada').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#sexo, #jornada').select2({
        allowClear: true,
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })
    });
  </script>
@endsection
