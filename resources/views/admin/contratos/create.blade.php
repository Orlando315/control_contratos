  @extends('layouts.app')

@section('title', 'Contratos')

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
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contrato.index') }}">Contratos</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar contrato</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.contrato.store') }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
                  <label for="valor">Valor: *</label>
                  <input id="valor" class="form-control" type="number" step="1" min="1" max="9999999999999" name="valor" value="{{ old('valor') }}" placeholder="Valor" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('inicio') ? ' has-error' : '' }}">
                  <label for="inicio">Inicio: *</label>
                  <input id="inicio" class="form-control" type="text" name="inicio" value="{{ old('inicio') }}" placeholder="dd-mm-yyyy" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fin') ? ' has-error' : '' }}">
                  <label for="fin">Fin: *</label>
                  <input id="fin" class="form-control" type="text" name="fin" value="{{ old('fin') }}" placeholder="dd-mm-yyyy" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('faena') ? ' has-error' : '' }}">
                  <label for="faena">Faena:</label>
                  <select id="faena" class="form-control" name="faena">
                    <option value="">Seleccione...</option>
                    @foreach($faenas as $faena)
                      <option value="{{ $faena->id }}"{{ old('faena') == $faena->id ? ' selected' : '' }}>{{ $faena->nombre }}</option>
                    @endforeach
                  </select>

                  @permission('faena-create')
                    <button class="btn btn-simple btn-link btn-sm" type="button" data-toggle="modal" data-target="#optionModal" data-option="tipo"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Faena</button>
                  @endpermission
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="150" value="{{ old('descripcion') }}" placeholder="Descripción">
            </div>

            @permission('requisito-create')
              <div class="requisitos-container">
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input id="allow" class="custom-control-input check-requisitos" type="checkbox" data-type="requisitos">
                    <label class="custom-control-label" for="allow">Agregar requisitos de Documentos adjuntos</label>
                  </div>
                </div>

                <fieldset id="section-requisitos" class="mt-2 px-3" style="display: none;" disabled>
                  <legend>Requisitos</legend>

                  <button class="btn btn-link btn-sm mb-3" type="button" data-toggle="modal" data-target="#requisitoModal"><i class="fa fa-copy" aria-hidden="true"></i> Copiar requisitos de otro Contrato</button>

                  <div class="custom-control custom-checkbox">
                    <input id="allow-contratos" class="custom-control-input check-requisitos" type="checkbox" data-type="contratos">
                    <label class="custom-control-label" for="allow-contratos">Agregar requisitos para el Contrato</label>
                  </div>
                  <fieldset id="section-contratos" class="mt-2 px-3" style="display: none" disabled>
                    <legend>Contrato</legend>

                    <table class="table table-bordered table-sm table-sm">
                      <thead>
                        <tr>
                          <th class="text-center">-</th>
                          <th class="text-center">Requisito</th>
                          <th class="text-center" title="Seleccione si el requisito es una carpeta">¿Es carpeta?</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-contratos">
                        <tr>
                          <td class="text-center align-middle">
                            <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                          </td>
                          <td>
                            <input class="form-control form-control-sm" type="text" name="requisitos[contratos][0][requisito]" maxlength="50" value="{{ old('requisitos.contratos.0.requisito') }}" placeholder="Nombre">
                          </td>
                          <td>
                            <div class="custom-control custom-switch" title="Seleccione si el requisito es una carpeta">
                              <input id="requisitos-contratos-0-carpeta" class="custom-control-input" type="checkbox" name="requisitos[contratos][0][carpeta]" value="1"{{ old('requisitos.contratos.0.carpeta') ? ' checked' : '' }}>
                              <label class="custom-control-label" for="requisitos-contratos-0-carpeta">Sí</label>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">
                            <button class="btn btn-primary btn-block btn-xs btn-add-field" type="button" data-type="contratos">Nuevo requisito</button>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </fieldset>

                  <div class="custom-control custom-checkbox">
                    <input id="allow-empleados" class="custom-control-input check-requisitos" type="checkbox" data-type="empleados">
                    <label class="custom-control-label" for="allow-empleados">Agregar requisitos para los Empleados</label>
                  </div>
                  <fieldset id="section-empleados" class="mt-2 px-3" style="display: none" disabled>
                    <legend>Empleados</legend>

                    <table class="table table-bordered table-sm table-sm">
                      <thead>
                        <tr>
                          <th class="text-center">-</th>
                          <th class="text-center">Requisito</th>
                          <th class="text-center" title="Seleccione si el requisito es una carpeta">¿Es carpeta?</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-empleados">
                        <tr>
                          <td class="text-center align-middle">
                            <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                          </td>
                          <td>
                            <input class="form-control form-control-sm" type="text" name="requisitos[empleados][0][requisito]" maxlength="50" value="{{ old('requisitos.empleados.0.requisito') }}" placeholder="Nombre">
                          </td>
                          <td>
                            <div class="custom-control custom-switch" title="Seleccione si el requisito es una carpeta">
                              <input id="requisitos-empleados-0-carpeta" class="custom-control-input" type="checkbox" name="requisitos[empleados][0][carpeta]" value="1"{{ old('requisitos.empleados.0.carpeta') ? ' checked' : '' }}>
                              <label class="custom-control-label" for="requisitos-empleados-0-carpeta">Sí</label>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">
                            <button class="btn btn-primary btn-block btn-xs btn-add-field" type="button" data-type="empleados">Nuevo requisito</button>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </fieldset>

                  <div class="custom-control custom-checkbox">
                    <input id="allow-transportes" class="custom-control-input check-requisitos" type="checkbox" data-type="transportes">
                    <label class="custom-control-label" for="allow-transportes">Agregar requisitos para los Transportes</label>
                  </div>
                  <fieldset id="section-transportes" class="mt-2 px-3" style="display: none" disabled>
                    <legend>Transportes</legend>

                    <table class="table table-bordered table-sm table-sm">
                      <thead>
                        <tr>
                          <th class="text-center">-</th>
                          <th class="text-center">Requisito</th>
                          <th class="text-center" title="Seleccione si el requisito es una carpeta">¿Es carpeta?</th>
                        </tr>
                      </thead>
                      <tbody id="tbody-transportes">
                        <tr>
                          <td class="text-center align-middle">
                            <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                          </td>
                          <td>
                            <input class="form-control form-control-sm" type="text" name="requisitos[transportes][0][requisito]" maxlength="50" value="{{ old('requisitos.transportes.0.requisito') }}" placeholder="Nombre">
                          </td>
                          <td>
                            <div class="custom-control custom-switch" title="Seleccione si el requisito es una carpeta">
                              <input id="requisitos-transportes-0-carpeta" class="custom-control-input" type="checkbox" name="requisitos[transportes][0][carpeta]" value="1"{{ old('requisitos.transportes.0.carpeta') ? ' checked' : '' }}>
                              <label class="custom-control-label" for="requisitos-transportes-0-carpeta">Sí</label>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3">
                            <button class="btn btn-primary btn-block btn-xs btn-add-field" type="button" data-type="transportes">Nuevo requisito</button>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </fieldset>
                </fieldset>
              </div>
            @endpermission

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
              <a class="btn btn-default btn-sm" href="{{ route('admin.contrato.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="requisitoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="requisitoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="requisitos-form" action="#" method="POST">
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="requisitoModalLabel">Copiar Requisitos</h4>
          </div>
          <div id="requisito-modal-body" class="modal-body">
            <div class="form-group">
              <label class="control-label" for="contrato">Contrato:</label>
              <select id="contrato" class="form-control" name="contrato" style="width: 100%">
                <option value="">Seleccione...</option>
                @foreach($contratosWithRequisitos as $contrato)
                  <option value="{{ $contrato->id }}">{{ $contrato->nombre }}</option>
                @endforeach
              </select>
            </div>

            <fieldset id="section-copy-requisitos" class="mt-2">
              <legend>Requisitos</legend>

              <p class="text-center text-muted">Seleccione los requisitos que desea copiar</p>
              <fieldset id="section-empleados" class="mt-2">
                <legend style="font-size: 0.8rem">Contratos</legend>

                <table class="table table-bordered table-sm table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">-</th>
                      <th class="text-center">Requisito</th>
                      <th class="text-center" title="Seleccione si el requisito es una carpeta">¿Es carpeta?</th>
                    </tr>
                  </thead>
                  <tbody id="copy-tbody-contratos">

                  </tbody>
                </table>
              </fieldset>

              <fieldset id="section-empleados" class="mt-2">
                <legend style="font-size: 0.8rem">Empleados</legend>

                <table class="table table-bordered table-sm table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">-</th>
                      <th class="text-center">Requisito</th>
                      <th class="text-center" title="Seleccione si el requisito es una carpeta">¿Es carpeta?</th>
                    </tr>
                  </thead>
                  <tbody id="copy-tbody-empleados">
                  </tbody>
                </table>
              </fieldset>

              <fieldset id="section-empleados" class="mt-2">
                <legend style="font-size: 0.8rem">Transportes</legend>

                <table class="table table-bordered table-sm table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">-</th>
                      <th class="text-center">Requisito</th>
                      <th class="text-center" title="Seleccione si el requisito es una carpeta">¿Es carpeta?</th>
                    </tr>
                  </thead>
                  <tbody id="copy-tbody-transportes">
                  </tbody>
                </table>
              </fieldset>
            </fieldset>

            <button class="btn btn-primary btn-block btn-xs btn-copy-requisitos" type="submit">Copiar requisitos</button>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @permission('faena-create')
    <div id="optionModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="option-form" action="{{ route('admin.faena.store') }}" method="POST">
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="optionModalLabel">Agregar Faena</h4>
            </div>
            <div class="modal-body">
              
              <div class="form-group">
                <label class="control-label" for="faena">Nombre: *</label>
                <input id="faena" class="form-control" type="text" name="nombre" maxlength="50" required>
              </div>

              <div class="alert alert-dismissible alert-danger alert-option" role="alert" style="display: none">
                <strong class="text-center">Ha ocurrido un error</strong> 

                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm option-submit" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection

@section('script')
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    @permission('faena-create')
      const alertOption = $('.alert-option');
      const optionSubmit = $('.option-submit');
    @endpermission
    @permission('requisito-create')
      let requisitoField = function (index, type, requisito = null) {
        return `<tr>
                  <td class="text-center align-middle">
                    <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                  </td>
                  <td>
                    <input class="form-control form-control-sm" type="text" name="requisitos[${type}][${index}][requisito]" maxlength="50" value="${requisito ? requisito.nombre : ''}" placeholder="Nombre">
                  </td>
                  <td>
                    <div class="custom-control custom-switch">
                      <input id="requisitos-${type}-${index}-carpeta" class="custom-control-input" type="checkbox" name="requisitos[${type}][${index}][carpeta]" value="1"${requisito && requisito.folder ? ' checked' : ''}>
                      <label class="custom-control-label" for="requisitos-${type}-${index}-carpeta">Sí</label>
                    </div>
                  </td>
                </tr>`;
      }

      let copyRequisitoField = function (index, requisito) {
        checked = requisito.folder ? ' checked' : '';
        return `<tr>
                  <td class="text-center align-middle">
                    <div class="custom-control custom-checkbox m-0 ">
                      <input id="check-${requisito.type}-${index}" class="custom-control-input copy-requisitos" type="checkbox" data-type="${requisito.type}" data-nombre="${requisito.nombre}" data-folder="${requisito.folder}" checked>
                      <label class="custom-control-label" for="check-${requisito.type}-${index}"></label>
                    </div>
                  </td>
                  <td>
                    ${requisito.nombre}
                  </td>
                  <td>
                    <div class="custom-control custom-switch">
                      <input class="custom-control-input" type="checkbox" readonly${checked}>
                      <label class="custom-control-label">Sí</label>
                    </div>
                  </td>
                </tr>`;
      }

      const CONTRATOS_REQUISITOS = @json($contratosWithRequisitos);

      function loadRequisitos(requisitos){
        $.each(requisitos, function (k, v) {
          $('#copy-tbody-'+v.type).append(copyRequisitoField(k, v));
        });
      }
    @endpermission

    $(document).ready( function(){
      $('#inicio, #fin').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#faena').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      @permission('requisito-create')
        $('#contrato').select2({
          theme: 'bootstrap4',
          dropdownParent: $('#requisito-modal-body'),
          placeholder: 'Seleccione...',
        });

        $('#contrato').change(function () {
          let id = $(this).val();

          $('#copy-tbody-contratos, #copy-tbody-empleados, #copy-tbody-transportes').empty();

          if(id){
            let contratoFinded = CONTRATOS_REQUISITOS.find(contrato => (contrato.id == id));
            loadRequisitos(contratoFinded.requisitos); 
          }
        });
        $('#contrato').change();

        $('#requisitos-form').submit(function (e) {
          e.preventDefault();

          let id = $(this).val();
          let btn = $('.btn-copy-requisitos');
          let requisitos = $('.copy-requisitos:checked');

          btn.prop('disabled', true);

          $.each(requisitos, function (k, v) {
            requisito = {
              type: $(v).data('type'),
              nombre: $(v).data('nombre'),
              folder: $(v).data('folder'),
            };

            let index = $(`#tbody-${requisito.type} tr`).length;

            $(`#tbody-${requisito.type}`).append(requisitoField(index, requisito.type, requisito));
          });

          btn.prop('disabled', false);
          $('#requisitoModal').modal('hide');
          $('#allow-contratos, #allow-empleados, #allow-transportes').prop('checked', true).change();
        });

        $('.requisitos-container').on('change', '.check-requisitos', function () {
          let type = $(this).data('type');
          let checked = $(this).is(':checked');
          $(`#section-${type}`).toggle(checked).prop('disabled', !checked);
        });

        $('.check-requisitos').change();

        $('.requisitos-container').on('click', '.btn-add-field', function () {
          let type = $(this).data('type')
          let index = $(`#tbody-${type} tr`).length

          $(`#tbody-${type}`).append(requisitoField(index, type))
        });

        $('.requisitos-container').on('click', '.btn-delete-requisito', function () {
          $(this).closest('tr').remove();
        });
      @endpermission

      @permission('faena-create')
        $('#option-form').submit(function(e){
          e.preventDefault();

          optionSubmit.prop('disabled', true)

          let form = $(this),
              action = form.attr('action');

          $.ajax({
            type: 'POST',
            data: form.serialize(),
            url: action,
            dataType: 'json'
          })
          .done(function (response) {
            if(response.response){
              $('#faena').append(`<option value="${response.faena.id}">${response.faena.nombre}</option`);
              $('#faena').val(response.faena.id);
              $('#faena').trigger('change');
              $('#option-form')[0].reset();
              $('#optionModal').modal('hide');
            }else{
              alertOption.show().delay(7000).hide('slow');  
            }
          })
          .fail(function () {
            alertOption.show().delay(7000).hide('slow');
          })
          .always(function () {
            optionSubmit.prop('disabled', false);
          })
        });
      @endpermission
    });
  </script>
@endsection
