@extends('layouts.app')

@section('title', 'Configuración')

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
      <h2>Configuración</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Empresa</li>
        <li class="breadcrumb-item active"><strong>Configuración</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>

  <div class="accordion" id="accordionExample">
    <div class="card">
      <div class="card-header" id="headingOne">
        <h3 class="m-0">
          <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <i class="fa fa-cogs"></i> General
          </button>
        </h3>
      </div>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
          <form action="{{ route('admin.empresa.configuracion.general') }}" method="POST">
            @csrf
            @method('PATCH')

            <fieldset>
              <legend class="form-legend">General</legend>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group{{ $errors->general->has('general.jornada') ? ' has-error' : '' }}">
                    <label for="jornada">Jornada: *</label>
                    <select id="jornada" class="custom-select" name="jornada" required>
                      <option value="">Seleccione...</option>
                      <option value="5x2"{{ old('jornada', $configuracion->jornada) == '5x2' ? ' selected' : '' }}>5x2</option>
                      <option value="4x3"{{ old('jornada', $configuracion->jornada) == '4x3' ? ' selected' : '' }}>4x3</option>
                      <option value="6x1"{{ old('jornada', $configuracion->jornada) == '6x1' ? ' selected' : '' }}>6x1</option>
                      <option value="7x7"{{ old('jornada', $configuracion->jornada) == '7x7' ? ' selected' : '' }}>7x7</option>
                      <option value="10x10"{{ old('jornada', $configuracion->jornada) == '10x10' ? ' selected' : '' }}>10x10</option>
                      <option value="12x12"{{ old('jornada', $configuracion->jornada) == '12x12' ? ' selected' : '' }}>12x12</option>
                      <option value="20x10"{{ old('jornada', $configuracion->jornada) == '20x10' ? ' selected' : '' }}>20x10</option>
                      <option value="7x14"{{ old('jornada', $configuracion->jornada) == '7x14' ? ' selected' : '' }}>7x14</option>
                      <option value="14x14"{{ old('jornada', $configuracion->jornada) == '14x14' ? ' selected' : '' }}>14x14</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group{{ $errors->general->has('general.dias_vencimiento') ? ' has-error' : '' }}">
                    <label for="dias_vencimiento">Días antes del vencimiento: *</label>
                    <input id="dias_vencimiento" class="form-control" type="number" name="dias_vencimiento" min="1" max="255" value="{{ old('dias_vencimiento', $configuracion->dias_vencimiento) }}" placeholder="Días vencimiento" required>
                    <span class="form-text text-muted">Cantidad de días restantes al vencimiento de un Contrato / Documento</span>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group{{ $errors->general->has('general.contrato_principal') ? ' has-error' : '' }}">
                    <label for="contrato_principal">Contrato Principal: *</label>
                    <select id="contrato_principal" class="custom-select" name="contrato_principal" required>
                      <option value="">Seleccione...</option>
                      @foreach($contratos as $contrato)
                        <option value="{{ $contrato->id }}"{{ old('contrato_principal', ($contrato->isMain() ? $contrato->id : null)) == $contrato->id ? ' selected' : '' }}>
                          {{ $contrato->nombre }}
                        </option>
                      @endforeach
                    </select>
                    <span class="form-text text-muted">Estará seleccionado por defecto al agregar/importar Empleados.</span>
                  </div>
                </div>
              </div>
            </fieldset>

            @if(count($errors->general) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->general->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right mt-2">
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>

          <form action="{{ route('admin.empresa.configuracion.terminos') }}" method="POST">
            @csrf
            @method('PATCH')

            <fieldset>
              <legend class="form-legend">Terminos y condiciones</legend>
              <p class="text-center">Cada vez que los terminos y condiciones sean moificados, los Empleados deberán aceptarlos nuevamente.</p>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->terminos->has('terminos.status') ? ' has-error' : '' }}">
                    <label for="terminos-activo">Activar aviso:</label>

                    <div class="custom-control custom-switch">
                      <input id="terminos-activo" class="custom-control-input" type="checkbox" name="terminos[status]" value="1"{{ old('terminos.status', optional($configuracion->terminos)->status) ? ' checked' : '' }}>
                      <label class="custom-control-label" for="terminos-activo">Activar aviso de terminos</label>
                    </div>
                    <span class="form-text text-muted">Determina si el aviso para aceptar los terminos se mostrará o no a los usuarios.</span>
                  </div>
                </div>
              </div>

              <div class="form-group{{ $errors->terminos->has('terminos.terminos') ? ' has-error' : '' }}">
                <label for="terminos-terminos">Terminos:</label>
                <textarea id="terminos-terminos" class="form-control" name="terminos[terminos]">{{ old('terminos.terminos', optional($configuracion->terminos)->terminos) }}</textarea>
              </div>
            </fieldset>

            @if(count($errors->terminos) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->terminos->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right mt-2">
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>

          <form action="{{ route('admin.empresa.configuracion.covid19') }}" method="POST">
            @csrf
            @method('PATCH')

            <fieldset>
              <legend class="form-legend">Encuesta Covid-19</legend>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->covid19->has('covid19.status') ? ' has-error' : '' }}">
                    <label for="covid19-activo">Activar encuesta:</label>

                    <div class="custom-control custom-switch">
                      <input id="covid19-activo" class="custom-control-input" type="checkbox" name="covid19[status]" value="1"{{ old('covid19.status', $configuracion->covid19) ? ' checked' : '' }}>
                      <label class="custom-control-label" for="covid19-activo">Activar encuesta</label>
                    </div>
                    <span class="form-text text-muted">Determina si la encuesta se mostrará o no a los usuarios.</span>
                  </div>
                </div>
              </div>
            </fieldset>

            @if(count($errors->covid19) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->covid19->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right mt-2">
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header" id="headingTwo">
        <h3 class="m-0">
          <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#integrations" aria-expanded="false" aria-controls="integrations">
            <i class="fa fa-random"></i> Facturación Sii
          </button>
        </h3>
      </div>
      <div id="integrations" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
        <div class="card-body ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <div class="row">
            <div id="sii-account-details" class="col-md-6"{!! $configuracion->doesntHaveSiiAccount() ? ' style="display: none"' : '' !!}>
              <div class="ibox">
                <div class="ibox-title px-3">
                  <h5>Usuario Sii</h5>
                  <div class="ibox-tools">
                    <a class="btn btn-default btn-xs" href="{{ route('admin.empresa.configuracion.sii.account.edit') }}" title="Editar usuario"><i class="fa fa-pencil"></i></a>
                  </div>
                </div>
                <div class="ibox-content no-padding">
                  <ul class="list-group">
                    <li class="list-group-item">
                      <b>ID</b>
                      <span id="sii-account-id" class="pull-right">
                        {{ $configuracion->sii_account->id }}
                      </span>
                    </li>
                    <li class="list-group-item">
                      <b>Usuario</b>
                      <span id="sii-account-username" class="pull-right">
                        @nullablestring($configuracion->sii_account->username)
                      </span>
                    </li>
                    <li class="list-group-item">
                      <b>Email</b>
                      <span id="sii-account-email" class="pull-right">
                        {{ $configuracion->sii_account->email }}
                      </span>
                    </li>
                  </ul>
                </div><!-- /.ibox-content -->
              </div>
            </div>
            <div id="sii-representante-details" class="col-md-6"{!! $configuracion->doesntHaveSiiRepresentante() ? ' style="display: none"' : '' !!}>
              <div class="ibox">
                <div class="ibox-title px-3">
                  <h5>Representante Sii</h5>
                  <div class="ibox-tools">
                    <a class="btn btn-default btn-xs" href="{{ route('admin.empresa.configuracion.sii.representante.edit') }}" title="Editar representante"><i class="fa fa-pencil"></i></a>
                  </div>
                </div>
                <div class="ibox-content no-padding">
                  <ul class="list-group">
                    <li class="list-group-item">
                      <b>ID</b>
                      <span id="sii-representante-id" class="pull-right">
                        {{ $configuracion->sii_representante->id }}
                      </span>
                    </li>
                    <li class="list-group-item">
                      <b>RUT</b>
                      <span id="sii-representante-rut" class="pull-right">
                        {{ $configuracion->sii_representante->rut }}
                      </span>
                    </li>
                    <li class="list-group-item">
                      <b>Vencimiento del certificado</b>
                      <span id="sii-representante-vencimiento_certificado" class="pull-right">
                        @if($configuracion->sii_representante->vencimiento_certificado)
                          {{ $configuracion->sii_representante->vencimiento_certificado->format('d-m-Y') }}
                        @else
                          @nullablestring(null)
                        @endif
                      </span>
                    </li>
                  </ul>
                </div><!-- /.ibox-content -->
              </div>
            </div>
          </div>

          <div class="alert alert-success alert-important text-center sii-success-alert my-3" style="display: none">
          </div>

          @if($configuracion->doesntHaveSiiAccount())
            <h2 id="sii-account-message" class="text-center mb-3">
              Parce que todavía no estas conectado con Facturación Sii.</br>
              Primero debemos crear una cuenta de Usuario.
            </h2>
            <div id="btn-sii-presentantion" class="text-center">
              <button class="btn btn-primary btn-sii-toggle" data-type="register">Registrarse</button>
              <button class="btn btn-primary btn-outline btn-sii-toggle" data-type="login">Iniciar sesión</button>
            </div>

            <form id="sii-login" class="mb-3" action="{{ route('admin.empresa.configuracion.sii.account.login') }}" method="POST" style="display:none">
              @csrf

              <fieldset>
                <legend class="form-legend">Datos de su cuenta</legend>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_email">Email: *</label>
                      <input id="sii_email" class="form-control" type="email" name="email" maxlength="150" value="{{ Auth::user()->empresa->email }}" placeholder="Email" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_password">Contraseña: *</label>
                      <input id="sii_password" class="form-control" type="password" name="password" minlength="6" placeholder="Contraseña" required>
                    </div>
                  </div>
              </fieldset>

              <div class="alert alert-danger alert-important sii-error-register" style="display: none">
                <ul class="m-0">
                </ul>
              </div>

              <button class="btn btn-primary btn-sm btn-block mb-3" type="submit"><i class="fa fa-send"></i> Enlazar cuenta Sii</button>
              <div class="text-center">
                <p class="mb-0">¿No posees una cuenta?</p>
                <button class="btn btn-primary btn-sm btn-outline btn-sii-toggle" data-type="register">Registrarse</button>
              </div>
            </form>

            <form id="sii-register" class="mb-3" action="{{ route('admin.empresa.configuracion.sii.account.store') }}" method="POST" style="display:none">
              @csrf

              <fieldset>
                <legend class="form-legend">Datos del Usuario</legend>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_username">Usuario: *</label>
                      <input id="sii_username" class="form-control" type="text" name="username" minlength="3" maxlength="25" placeholder="Usuario" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_email">Email: *</label>
                      <input id="sii_email" class="form-control" type="email" name="email" maxlength="150" value="{{ Auth::user()->empresa->email }}" placeholder="Email" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_password">Contraseña: *</label>
                      <input id="sii_password" class="form-control" type="password" name="password" minlength="6" placeholder="Contraseña" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_password_confirmation">Verificar contraseña: *</label>
                      <input id="sii_password_confirmation" class="form-control" type="password" name="password_confirmation" minlength="6" placeholder="Verificar contraseña" required>
                    </div>
                  </div>
                </div>
              </fieldset>

              <div class="alert alert-danger alert-important sii-error-register" style="display: none">
                <ul class="m-0">
                </ul>
              </div>

              <button class="btn btn-primary btn-sm btn-block mb-3" type="submit"><i class="fa fa-send"></i> Crear cuenta</button>
              <div class="text-center">
                <p class="mb-0">¿Ya posees una cuenta?</p>
                <button class="btn btn-primary btn-sm btn-outline btn-sii-toggle" data-type="login">Iniciar sesión</button>
              </div>
            </form>
          @endif

          @if($configuracion->doesntHaveSiiRepresentante())
            <form id="sii-representante" class="mb-3" action="{{ route('admin.empresa.configuracion.sii.representante.store') }}" method="POST"{!! $configuracion->doesntHaveSiiAccount() ? ' style="display:none"' : '' !!}>
              @csrf
              <h2 class="text-center mb-3">
                Debe asociar la Empresa con los datos del Representante Legal.
              </h2>

              <fieldset>
                <legend class="form-legend">Datos del Representante Legal</legend>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_rut">RUT: *</label>
                      <input id="sii_rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" placeholder="RUT" required>
                      <span class="help-block">Ejemplo: 00000000-0</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_clave_sii">Clave Sii: *</label>
                      <input id="sii_clave_sii" class="form-control" type="password" name="clave_sii" placeholder="Clave Sii" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="sii_certificatePassword">Clave del certificado digital: *</label>
                      <input id="sii_certificatePassword" class="form-control" type="password" name="clave_certificado_digital" placeholder="Clave del certificado digital" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="vencimiento_certificado">Fecha de vencimiento del certificado:</label>
                      <input id="vencimiento_certificado" class="form-control" type="text" name="vencimiento_certificado" placeholder="dd-mm-yyyy">
                    </div>
                  </div>
                </div>
              </fieldset>

              <div class="alert alert-danger alert-important sii-error-representante" style="display: none">
                <ul class="m-0">
                </ul>
              </div>

              <button id="btn-sii-representante" class="btn btn-primary btn-sm btn-block" type="submit"{{ $configuracion->doesntHaveSiiAccount() ? ' disabled' : '' }}><i class="fa fa-send"></i> Asociar representante legal</button>
            </form>
          @endif
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header" id="headingThree">
        <h3 class="m-0">
          <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            <i class="fa fa-list"></i> Requerimientos de Materiales
          </button>
        </h3>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
          <form id="add-firmante-form" action="#" method="POST">
            @csrf
            @method('PATCH')

            <p class="text-center m-0">Los cambios que se realicen no afectarán a los Requerimientos de Materiales ya existentes.</p>

            <fieldset>
              <legend class="form-legend">Usuarios firmantes</legend>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="firmante">Usuario: *</label>
                    <select id="firmante" class="form-control" required style="width: 100%">
                      <option value="">Seleccione...</option>
                      @foreach($users as $user)
                        <option value="{{ $user->id }}">
                          {{ $user->nombre() }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="firmante-texto">Texto: *</label>
                    <input id="firmante-texto" class="form-control" type="text" maxlength="50" placeholder="Texto" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="">Obligatorio:</label>
                    <div class="custom-control custom-checkbox">
                      <input id="firmante-obligatorio" class="custom-control-input" type="checkbox" value="1">
                      <label class="custom-control-label" for="firmante-obligatorio">
                        Sí
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="row justify-content-center mb-3">
              <div class="col-md-3">
                <button id="btn-add-firmante" class="btn btn-block btn-sm btn-primary" type="submit">Agregar firmante</button>
              </div>
            </div>
          </form>

          <form action="{{ route('admin.empresa.configuracion.requerimientos') }}" method="POST">
            @csrf
            @method('PATCH')

            <table class="table table-bordered">
              <colgroup>
                <col span="1" style="width: 5%;">
                <col span="1" style="width: 60%;">
                <col span="1" style="width: 20%;">
                <col span="1" style="width: 15%;">
              </colgroup>
              <thead>
                <tr class="text-center">
                  <th class="align-middle">-</th>
                  <th class="align-middle">Nombre</th>
                  <th class="align-middle">Texto</th>
                  <th class="align-middle">Obligatorio</th>
                </tr>
              </thead>
              <tbody id="tbody-firmantes" class="{{ (count(old('usuarios', $configuracion->requerimientos_firmantes)) > 0) ? '' : 'is-empty' }}">
                @forelse(old('usuarios', $configuracion->requerimientos_firmantes) as $index => $userFirmante)
                  <tr id="tr-{{ $index }}" class="tr-firmante">
                    <td class="text-center align-middle">
                      <button class="btn btn-danger btn-xs btn-firmantes-delete m-0" type="button" role="button" data-id="{{ $index }}" data-usuario="{{ $userFirmante['usuario'] }}"><i class="fa fa-trash"></i></button>
                    </td>
                    <td>
                      {{ $userFirmante['nombre'] }}
                      <input type="hidden" name="usuarios[{{ $index }}][usuario]" value="{{ $userFirmante['usuario'] }}">
                      <input type="hidden" name="usuarios[{{ $index }}][nombre]" value="{{ $userFirmante['nombre'] }}">
                    </td>
                    <td>
                      {{ $userFirmante['texto'] }}
                      <input type="hidden" name="usuarios[{{ $index }}][texto]" value="{{ $userFirmante['texto'] }}">
                    </td>
                    <td class="text-center">
                      @if($userFirmante['obligatorio'])
                        <span class="label label-primary">Sí</span>
                      @else
                        <span class="label label-default">No</span>
                      @endif
                      <input type="hidden" name="usuarios[{{ $index }}][obligatorio]" value="{{ $userFirmante['obligatorio'] }}">
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center text-muted" colspan="3">No se han agregado firmantes.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <div class="alert alert-danger alert-important alert-firmantes"{!! (count($errors->firmantes) > 0) ? '' : 'style="display: none"' !!}>
              <ul class="m-0">
                @foreach($errors->firmantes->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right mt-2">
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
  <!-- CKEditor -->
  <script type="text/javascript" src="{{ asset('js/plugins/ckeditor/ckeditor.js') }}"></script>
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    const TBODY_FIRMANTES = $('#tbody-firmantes');
    const BTN_ADD_FIRMANTE = $('#btn-add-firmante');
    const FIRMANTES = @json($configuracion->requerimientos_firmantes);
    const SII_IBOX = $('#integrations .ibox-content');

    $(document).ready(function () {
      CKEDITOR.replace('terminos-terminos', {
        language: 'es',
      });

      $('#firmante, #contrato_principal').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#add-firmante-form').submit(addFirmante);
      TBODY_FIRMANTES.on('click', '.btn-firmantes-delete', deleteFirmante);

      $('#vencimiento_certificado').datepicker({
        format: 'dd-mm-yyyy',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      @if($configuracion->doesntHaveSiiAccount())
        $('.btn-sii-toggle').click(function (){
          let isRegister = $(this).data('type') == 'register';

          $('#sii-register').toggle(isRegister);
          $('#sii-login').toggle(!isRegister);
          $('#btn-sii-presentantion').hide();
        });

        $('#sii-login').submit(function (e) {
          e.preventDefault();

          let form = $(this),
          action = form.attr('action'),
          btn = $('#btn-sii-login');

          SII_IBOX.toggleClass('sk-loading');
          btn.prop('disabled', true);

          $.ajax({
            type: 'POST',
            url: action,
            data: form.serialize(),
            dataType: 'json',
          })
          .done(function (response) {
            $('#sii-account-message, #sii-register').remove();
            form.remove();

            $.each(response, function (field, value){
              $(`#sii-account-${field}`).text(value);
            });

            $('#sii-account-details, #sii-representante').show();
            $('#btn-sii-representante').prop('disabled', false);

            showAlert('.sii-success-alert', 'Cuenta Sii enlazada exitosamente!');
          })
          .fail(function (errors) {
            btn.prop('disabled', false);
            showErrors('.sii-error-register', errors);
          })
          .always(function () {
            SII_IBOX.toggleClass('sk-loading');
          });
        });

        $('#sii-register').submit(function (e) {
          e.preventDefault();

          let form = $(this),
          action = form.attr('action'),
          btn = $('#btn-sii-regiter');

          SII_IBOX.toggleClass('sk-loading');
          btn.prop('disabled', true);

          $.ajax({
            type: 'POST',
            url: action,
            data: form.serialize(),
            dataType: 'json',
          })
          .done(function (response) {
            $('#sii-account-message, #sii-register').remove();
            form.remove();

            $.each(response, function (field, value){
              $(`#sii-account-${field}`).text(value);
            });

            $('#sii-account-details, #sii-representante').show();
            $('#btn-sii-representante').prop('disabled', false);

            showAlert('.sii-success-alert', '¡Usuario creado exitosamente!');
          })
          .fail(function (errors) {
            btn.prop('disabled', false);
            showErrors('.sii-error-register', errors);
          })
          .always(function () {
            SII_IBOX.toggleClass('sk-loading');
          });
        });
      @endif

      @if($configuracion->doesntHaveSiiRepresentante())
        $('#sii-representante').submit(function (e) {
          e.preventDefault();

          let form = $(this),
          action = form.attr('action'),
          btn = $('#btn-sii-representante');

          SII_IBOX.toggleClass('sk-loading');
          btn.prop('disabled', true);

          $.ajax({
            type: 'POST',
            url: action,
            data: form.serialize(),
            dataType: 'json',
          })
          .done(function (response) {
            form.remove();

            $.each(response, function (field, value){
              $(`#sii-representante-${field}`).text(value);
            });

            $('#sii-representante-details').show();
            showAlert('.sii-success-alert', '¡Representante registrado exitosamente!');
          })
          .fail(function (errors) {
            btn.prop('disabled', false);
            showErrors('.sii-error-representante', errors);
          })
          .always(function () {
            SII_IBOX.toggleClass('sk-loading');
          });
        });
      @endif
    });

    function addFirmante(e){
      e.preventDefault();

      BTN_ADD_FIRMANTE.prop('disabled', true);

      let usuario = $('#firmante').val();
      let option = $(this).find(`option[value="${usuario}"]`);
      let nombre = option.text();
      let data = {
        usuario: usuario,
        nombre: nombre.trim(),
        texto: $('#firmante-texto').val(),
        obligatorio: $('#firmante-obligatorio').is(':checked'),
      };

      if(firmanteExist(usuario)){
        $('.alert-firmantes ul').empty().append(`<li>Firmante ya agregado.</li>`)
        $('.alert-firmantes').show().delay(5000).hide('slow');
        BTN_ADD_FIRMANTE.prop('disabled', false);

        return false;
      }else{
        FIRMANTES.push(data);
      }

      let index = Date.now();

      if(TBODY_FIRMANTES.hasClass('is-empty')){
        TBODY_FIRMANTES.removeClass('is-empty').empty();
      }

      TBODY_FIRMANTES.append(firmante(index, data));
      BTN_ADD_FIRMANTE.prop('disabled', false);
      $(this)[0].reset();
      $('#firmante').val(null).trigger('change');
    }

    function deleteFirmante(){
      let index = $(this).data('id');
      let usuario = $(this).data('usuario');
      $(`#tr-${index}`).remove();

      removeAddedFirmante(usuario);

      let firmantes = $('.tr-firmante').length;

      if(firmantes == 0){
        TBODY_FIRMANTES
          .addClass('is-empty')
          .append('<tr class="tr-empty"><td class="text-center text-muted" colspan="3">No se han agregado firmantes.</td></tr>');
      }
    }

    function firmanteExist(usuario, returnIndex = false){
      let firmante = FIRMANTES.find(firmante => firmante.usuario == usuario);
      let index = -1;
      if(firmante !== undefined){
        index = FIRMANTES.indexOf(firmante);
      }

      return returnIndex ? index : (index !== -1);
    }

    function removeAddedFirmante(usuario){
      let index = firmanteExist(usuario, true);
      FIRMANTES.splice(index, 1);
    }

    // Informacion del Producto
    let firmante = function(index, data) {
      return `<tr id="tr-${index}" class="tr-firmante">
                <td class="text-center align-middle">
                  <button class="btn btn-danger btn-xs btn-firmantes-delete m-0" type="button" role="button" data-id="${index}" data-usuario="${data.usuario}"><i class="fa fa-trash"></i></button>
                </td>
                <td>
                  ${data.nombre}
                  <input type="hidden" name="usuarios[${index}][usuario]" value="${data.usuario}">
                  <input type="hidden" name="usuarios[${index}][nombre]" value="${data.nombre}">
                </td>
                <td>
                  ${data.texto}
                  <input type="hidden" name="usuarios[${index}][texto]" value="${data.texto}">
                </td>
                <td class="text-center">
                  ${data.obligatorio ? '<span class="label label-primary">Sí</span>' : '<span class="label label-default">No</span>'}
                  <input type="hidden" name="usuarios[${index}][obligatorio]" value="${data.obligatorio ? 1 : 0}">
                </td>
              </tr>`;
    }

    function showAlert(alert, message){
      $(alert).html(`<h3>${message}</h3>`);
      $(alert).show().delay(5000).hide('slow');
    }

    function showErrors(alert, errors){
      let ul = $(alert).find('ul');
      $(ul).empty();

      errors = errors.responseJSON.hasOwnProperty('errors') ? errors.responseJSON.errors : errors.responseJSON;

      $.each(errors, function (feild, fieldErrors){
        if($.isArray(fieldErrors)){
          $.each(fieldErrors, function (keyError, errorMessage){
            $(ul).append(`<li>${errorMessage}</li>`);
          })
        }else{
          $(ul).append(`<li>${fieldErrors}</li>`);
        }
      });

      $(alert).show().delay(5000).hide('slow');
    }
  </script>
@endsection
