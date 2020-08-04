@extends('layouts.app')

@section('title', 'Contratos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Contratos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
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
            {{ csrf_field() }}

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

            <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
              <label for="descripcion">Descripción:</label>
              <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="150" value="{{ old('descripcion') }}" placeholder="Descripción">
            </div>

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
@endsection

@section('script')
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <script type="text/javascript">
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

    $(document).ready( function(){
      $('#inicio, #fin').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('.requisitos-container').on('change', '.check-requisitos', function () {
        let type = $(this).data('type');
        let checked = $(this).is(':checked');
        $(`#section-${type}`).toggle(checked).prop('disabled', !checked)
      });

      $('.check-requisitos').change()

      $('.requisitos-container').on('click', '.btn-add-field', function () {
        let type = $(this).data('type')
        let index = $(`#tbody-${type} tr`).length

        $(`#tbody-${type}`).append(requisitoField(index, type))
      })

      $('.requisitos-container').on('click', '.btn-delete-requisito', function () {
        $(this).closest('tr').remove()
      })
    });
  </script>
@endsection
