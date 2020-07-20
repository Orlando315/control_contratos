@extends('layouts.app')

@section('title', 'Documentos')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.plantilla.documento.index') }}">Documentos</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar documento</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.plantilla.documento.store') }}" method="POST">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre del documento:</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') }}" placeholder="Nombre">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required style="width: 100%">
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ old('contrato', optional($selected)->id) == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
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

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('plantilla') ? ' has-error' : '' }}">
                  <label for="plantilla">Plantilla: *</label>
                  <select id="plantilla" class="form-control" name="plantilla" required style="width: 100%">
                    <option value="">Seleccione...</option>
                    @foreach($plantillas as $plantilla)
                      <option value="{{ $plantilla->id }}"{{ old('plantilla') == $plantilla->id ? ' selected' : '' }}>{{ $plantilla->nombre }}</option>
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
                      <option value="{{ $padre->id }}"{{ old('padre') == $padre->id ? ' selected' : '' }}>{{ $padre->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('caducidad') ? ' has-error' : '' }}">
                  <label for="caducidad">Fecha de caducidad:</label>
                  <input id="caducidad" class="form-control" type="text" name="caducidad" value="{{ old('caducidad') }}" placeholder="dd-mm-yyyy">
                </div>
              </div>
            </div>

            <h4>Completar variables</h4>

            <div class="section-variables mb-3">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.documento.index') }}"><i class="fa fa-reply"></i> Atras</a>
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
        let value = getOldValue(seccion.id, v.variable)
        vargGroups += `<div class="form-group row">
                          <label class="col-md-3" for="${v.variable}">${v.nombre}:</label>
                          <div class="col-md-9">
                            <input id="${v.variable}" class="form-control" type="${tipo}" name="secciones[${seccion.id}][${v.variable}]" maxlength="50" value="${value}" placeholder="${v.nombre}" required>
                          </div>
                        </div>`;
      })

      return `<div id="seccion-${seccion.id}">
                <h4 class="text-center">Sección #${index}: ${seccion.nombre ? seccion.nombre : '<span class="text-muted">N/A</span>'}</h4>
                ${vargGroups}
              </div>`;
    }

    const oldSectionsValues = @json(old('secciones'));

    $(document).ready( function(){
      $('#contrato, #empleado, #plantilla, #padre').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccionar...',
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
          url: '{{ route("admin.empleados.index") }}/contratos/' + contrato,
          data: {
            _token: '{{ csrf_token() }}'
          },
          dataType: 'json',
        })
        .done(function(data){
          select.empty().append(new Option('Seleccione...', '', false, false));
          if(data.length > 0){
            $.each(data, function(k, v){
              let isSelected = v.id == `{{ old('empleado', (optional($empleado)->id ?? 0)) }}`
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
      })

      $('#plantilla').change(function () {
        let plantilla = $(this).val()

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
      })

      $('#contrato, #plantilla').trigger('change')
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
