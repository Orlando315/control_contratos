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
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Contratos</a></li>
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
          <form action="{{ route('admin.contratos.store') }}" method="POST">
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
                <div class="custom-control custom-checkbox">
                  <input id="allow" class="custom-control-input check-requisitos" type="checkbox" data-type="requisitos">
                  <label class="custom-control-label" for="allow">Agregar requisitos de Documentos adjuntos</label>
                </div>

                <fieldset id="section-requisitos" class="mt-2 px-3" style="display: none;" disabled>
                  <legend>Requisitos</legend>

                  <div class="custom-control custom-checkbox">
                    <input id="allow-contratos" class="custom-control-input check-requisitos" type="checkbox" data-type="contratos">
                    <label class="custom-control-label" for="allow-contratos">Agregar requisitos para el Contrato</label>
                  </div>
                  <fieldset id="section-contratos" class="mt-2 px-3" style="display: none" disabled>
                    <legend>Contrato</legend>

                    <table class="table">
                      <tbody id="tbody-contratos">
                        <tr>
                          <td class="text-center align-middle">
                            <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                          </td>
                          <td>
                            <input class="form-control form-control-sm" type="text" name="requisitos[contratos][]" maxlength="50" value="{{ old('requisitos.contratos.0') }}" placeholder="Nombre">
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">
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

                    <table class="table">
                      <tbody id="tbody-empleados">
                        <tr>
                          <td class="text-center align-middle">
                            <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                          </td>
                          <td>
                            <input class="form-control form-control-sm" type="text" name="requisitos[empleados][]" maxlength="50" value="{{ old('requisitos.empleados.0') }}" placeholder="Nombre">
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">
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

                    <table class="table">
                      <tbody id="tbody-transportes">
                        <tr>
                          <td class="text-center align-middle">
                            <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                          </td>
                          <td>
                            <input class="form-control form-control-sm" type="text" name="requisitos[transportes][]" maxlength="50" value="{{ old('requisitos.transportes.0') }}" placeholder="Nombre">
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
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
      let requisitoField = function (index, type) {
        return `<tr>
                  <td class="text-center align-middle">
                    <button class="btn btn-danger btn-xs btn-delete-requisito" type="button"><i class="fa fa-times"></i></button>
                  </td>
                  <td>
                    <input class="form-control form-control-sm" type="text" name="requisitos[${type}][]" maxlength="50" placeholder="Nombre">
                  </td>
                </tr>
              `;
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
        $('.requisitos-container').on('change', '.check-requisitos', function () {
          let type = $(this).data('type');
          let checked = $(this).is(':checked');
          $(`#section-${type}`).toggle(checked).prop('disabled', !checked)
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
