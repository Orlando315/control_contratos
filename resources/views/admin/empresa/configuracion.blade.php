@extends('layouts.app')

@section('title', 'Configuración')

@section('head')
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

  <div class="row mb-3">
    <div class="col-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-cogs"></i> General</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-random"></i> Integraciones</a></li>
          <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-list"></i> Requerimientos de Materiales</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">

              <form action="{{ route('admin.empresa.configuracion.general') }}" method="POST">
                @csrf
                @method('PATCH')

                <fieldset>
                  <legend class="form-legend">General</legend>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group{{ $errors->general->has('jornada') ? ' has-error' : '' }}">
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
                      <div class="form-group{{ $errors->general->has('dias_vencimiento') ? ' has-error' : '' }}">
                        <label for="dias_vencimiento">Días antes del vencimiento: *</label>
                        <input id="dias_vencimiento" class="form-control" type="number" name="dias_vencimiento" min="1" max="255" value="{{ old('dias_vencimiento', $configuracion->dias_vencimiento) }}" placeholder="Días vencimiento" required>
                        <span class="form-text text-muted">Cantidad de días restantes al vencimiento de un Contrato / Documento</span>
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
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <form action="{{ route('admin.empresa.configuracion.sii') }}" method="POST">
                @csrf
                @method('PATCH')

                <fieldset>
                  <legend class="form-legend">Facturación Sii</legend>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group{{ $errors->sii->has('sii_clave') ? ' has-error' : '' }}">
                        <label for="sii_clave">Clave Sii:</label>
                        <input id="sii_clave" class="form-control" type="text" name="sii_clave" maxlength="120" value="{{ old('sii_clave', $configuracion->sii_clave) }}" placeholder="Clave SII">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group{{ $errors->sii->has('sii_clave_certificado') ? ' has-error' : '' }}">
                        <label for="sii_clave_certificado">Clave certificado digital:</label>
                        <input id="sii_clave_certificado" class="form-control" type="text" name="sii_clave_certificado" maxlength="150" value="{{ old('sii_clave_certificado', $configuracion->sii_clave_certificado) }}" placeholder="Clave certificado digital">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group{{ $errors->sii->has('sii_firma') ? ' has-error' : '' }}">
                        <label for="sii_firma">Firma:</label>
                        <input id="sii_firma" class="form-control" type="text" name="sii_firma" maxlength="120" value="{{ old('sii_firma', $configuracion->firma) }}" placeholder="Firma">
                      </div>
                    </div>
                  </div>
                </fieldset>

                @if(count($errors->sii) > 0)
                  <div class="alert alert-danger alert-important">
                    <ul class="m-0">
                      @foreach($errors->sii->all() as $error)
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
          <div id="tab-3" class="tab-pane">
            <div class="panel-body">
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
    </div>
  </div>
@endsection

@section('script')
  <!-- CKEditor -->
  <script type="text/javascript" src="{{ asset('js/plugins/ckeditor/ckeditor.js') }}"></script>
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    const TBODY_FIRMANTES = $('#tbody-firmantes');
    const BTN_ADD_FIRMANTE = $('#btn-add-firmante');
    const FIRMANTES = @json($configuracion->requerimientos_firmantes);

    $(document).ready(function () {
      CKEDITOR.replace('terminos-terminos', {
        language: 'es',
      });

      $('#firmante').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#add-firmante-form').submit(addFirmante);
      TBODY_FIRMANTES.on('click', '.btn-firmantes-delete', deleteFirmante);
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
  </script>
@endsection
