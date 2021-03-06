@extends('layouts.app')

@section('title', 'Editar')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <style type="text/css">
    .switch .onoffswitch-inner:before{
      content: 'Postulante';
    }
    .switch .onoffswitch-inner:after{
      content: 'Empleado';
    }
    .switch .onoffswitch{
      width: 85px;
    }
    .switch .onoffswitch-switch{
      right: 67px;
    }
  </style>
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.plantilla.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar documento</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.plantilla.documento.update', ['documento' => $documento->id]) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre del documento:</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $documento->nombre) }}" placeholder="Nombre">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="dirigido">Dirigido a:</label>
                  <div class="switch mb-3">
                    <div class="onoffswitch mx-auto">
                      <input id="check-dirigido" class="onoffswitch-checkbox" type="checkbox" name="dirigido" value="1"{{ old('dirigido', $documento->toPostulante()) == '1' ? ' checked' : '' }}>
                      <label class="onoffswitch-label" for="check-dirigido">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <fieldset id="dirigido-empleado" class="section-dirigido">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                    <label for="contrato">Contrato: *</label>
                    <select id="contrato" class="form-control" name="contrato" required style="width: 100%">
                      <option value="">Seleccione...</option>
                      @foreach($contratos as $contrato)
                        <option value="{{ $contrato->id }}"{{ old('contrato', $documento->contrato_id) == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('empleado') ? ' has-error' : '' }}">
                    <label for="empleado">Empleado: *</label>
                    <select id="empleado" class="form-control" name="empleado" required style="width: 100%" disabled>
                      <option value="">Seleccione...</option>
                    </select>
                  </div>
                </div>
              </div>
            </fieldset>

            <fieldset id="dirigido-postulante" class="section-dirigido" disabled style="display: none">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('postulante') ? ' has-error' : '' }}">
                    <label for="postulante">Postulante: *</label>
                    <select id="postulante" class="form-control" name="postulante" required style="width: 100%">
                      <option value="">Seleccione...</option>
                      @foreach($postulantes as $postulante)
                        <option value="{{ $postulante->id }}"{{ old('postulante', $documento->postulante_id) == $postulante->id ? ' selected' : '' }}>{{ $postulante->nombre() }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('plantilla') ? ' has-error' : '' }}">
                  <label for="plantilla">Plantilla: *</label>
                  <select id="plantilla" class="form-control" name="plantilla" required style="width: 100%">
                    <option value="">Seleccione...</option>
                    @foreach($plantillas as $plantilla)
                      <option value="{{ $plantilla->id }}"{{ old('plantilla', $documento->plantilla_id) == $plantilla->id ? ' selected' : '' }}>{{ $plantilla->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('padre') ? ' has-error' : '' }}">
                  <label for="padre">Documento padre:</label>
                  <select id="padre" class="form-control" name="padre" style="width: 100%">
                    <option value="">Seleccione...</option>
                    @foreach($padres as $padre)
                      <option value="{{ $padre->id }}"{{ old('padre', $documento->documento_id) == $padre->id ? ' selected' : '' }}>{{ $padre->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('caducidad') ? ' has-error' : '' }}">
                  <label for="caducidad">Fecha de caducidad:</label>
                  <input id="caducidad" class="form-control" type="text" name="caducidad" value="{{ old('caducidad', optional($documento->caducidad)->format('d-m-Y')) }}" placeholder="dd-mm-yyyy">
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('visibilidad') ? ' has-error' : '' }}">
              <label for="visibilidad">Visibilidad:</label>

              <div class="custom-control custom-checkbox">
                <input id="visibilidad" class="custom-control-input" type="checkbox" name="visibilidad" value="1"{{ old('visibilidad', $documento->visibilidad) ? ' checked' : '' }}>
                <label class="custom-control-label" for="visibilidad"><i class="icon-visibilidad fa fa-eye-slash" aria-hidden="true"></i> Permitir visibilidad</label>
              </div>
              <span class="form-text text-muted">Determina si el Empleado puede o no ver el Documento desde su perfil.</span>
            </div>

            <h4>Completar variables</h4>

            <div class="section-variables mb-1">
              <p class="text-center text-muted m-0">No hay variables a completar</p>
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

            <div class="text-right mt-2">
              <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.documento.show', ['documento' => $documento->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <script type="text/javascript">
    const sectionVariables = $('.section-variables')
    const buildGroup = function (index, seccion){
      let vargGroups = '';

      $.each(seccion.variables, function (k, v){
        let tipo = converType(v.tipo)
        let value = getOldValue(seccion.id, v.variable) ?? ''
        let isDisabled = (v.tipo == 'empleado' || v.tipo == 'postulante' ) ? ' readonly' : '';
        let helpText = v.tipo == 'empleado' ? '<small class="text-form text-muted">Se tomará automáticamente la información del Empleado/Postulante.</small>' : '';

        vargGroups += `<div class="form-group row">
                          <label class="col-md-3" for="${v.variable}">${v.nombre}:</label>
                          <div class="col-md-9">
                            <input id="${v.variable}" class="form-control" type="${tipo}" name="secciones[${seccion.id}][${v.variable}]" maxlength="50" value="${value}" placeholder="${v.nombre}" required${isDisabled}>
                            ${helpText}
                          </div>
                        </div>`;
      })

      return `<div id="seccion-${seccion.id}">
                <h4 class="text-center">Sección #${index}: ${seccion.nombre ? seccion.nombre : '<span class="text-muted">N/A</span>'}</h4>
                ${vargGroups}
              </div>`;
    }

    const oldSectionsValues = @json(old('secciones', $documento->secciones));

    $(document).ready( function(){
      $('#contrato, #empleado, #postulante, #plantilla').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#padre').select2({
        allowClear: true,
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#caducidad').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#contrato').change(function () {
        let contrato = $(this).val(),
            select = $('#empleado');

        if(contrato == '') return;

        $.ajax({
          type: 'POST',
          url: '{{ route("admin.empleado.index") }}/contratos/' + contrato,
          data: {
            _token: '{{ csrf_token() }}'
          },
          dataType: 'json',
        })
        .done(function(data){
          select.empty().append(new Option('Seleccione...', '', false, false));
          if(data.length > 0){
            $.each(data, function(k, v){
              let isSelected = v.id == `{{ old('empleado', $documento->empleado_id) }}`
              select.append(new Option(v.usuario.rut + ' | ' + v.usuario.nombres + ' ' + v.usuario.apellidos, v.id, isSelected, isSelected))
            })

            select.prop('disabled', false)
          }else{
            select.prop('disabled', true)
          }
        })
        .fail(function(){
          select.prop('disabled', false)
        });
      });

      $('#plantilla').change(function () {
        let plantilla = $(this).val();
        let isEmpleado = !$(this).is(':checked');

        if(plantilla < 1){
          return false;
        }

        $.ajax({
          type: 'GET',
          url: `{{ route("admin.plantilla.index") }}/${plantilla}/variables`,
        })
        .done(function (response) {
          if(response.response){
            sectionVariables.empty()
            $.each(response.secciones, function (k, seccion){
              if(seccion.variables.length > 0){
                sectionVariables.append(buildGroup((k + 1), seccion))
              }
            })
          }else{
            sectionVariables.empty().append('<p class="text-center text-muted m-0">No hay variable a completar</p>')
          }
        });
      });

      $('#contrato, #plantilla').trigger('change')

      $('#check-dirigido').change(function () {
        let isEmpleado = !$(this).is(':checked');
        let type = isEmpleado ? 'empleado' : 'postulante';

        $('#dirigido-empleado').closest('.section-dirigido').prop('disabled', !isEmpleado).toggle(isEmpleado);
        $('#dirigido-postulante').closest('.section-dirigido').prop('disabled', isEmpleado).toggle(!isEmpleado);
        $('#visibilidad').prop('disabled', !isEmpleado).closest('.form-group').toggle(isEmpleado);
      });
      $('#check-dirigido').change();

      $('#visibilidad').change(function () {
        let isChecked = $(this).is(':checked');

        $('.icon-visibilidad').toggleClass('fa-eye', isChecked);
        $('.icon-visibilidad').toggleClass('fa-eye-slash', !isChecked);
      });
      $('#visibilidad').change();
    });

    function converType(tipo){
      switch (tipo){
        case 'number':
          return 'number';
          break;
        case 'email':
          return 'email';
          break;
        case 'date':
          return 'date';
          break;
        case 'tel':
          return 'tel';
          break;
        case 'text':
        case 'rut':
        case 'empleado':
        case 'postulante':
        default:
          return 'text'
          break;
      }
    }

    function getOldValue(seccion, variable){
      return (oldSectionsValues && oldSectionsValues.hasOwnProperty(seccion)) ? oldSectionsValues[seccion][variable] : '';
    }
  </script>
@endsection
