@extends('layouts.app')
@section('title', 'Editar - '.config('app.name'))
@section('header', 'Editar')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('plantilla.documento.index') }}">Documentos</a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('plantilla.documento.update', ['documento' => $documento->id]) }}" method="POST">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <h4>Editar documento</h4>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label class="control-label" for="nombre">Nombre del documento:</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $documento->nombre) }}" placeholder="Nombre">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group {{ $errors->has('contrato') ? 'has-error' : '' }}">
              <label class="control-label" class="form-control" for="contrato">Conrato: *</label>
              <select id="contrato" class="form-control" name="contrato" required style="width: 100%">
                <option value="">Seleccione...</option>
                @foreach($contratos as $contrato)
                  <option value="{{ $contrato->id }}"{{ old('contrato', $documento->contrato_id) == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group {{ $errors->has('empleado') ? 'has-error' : '' }}">
              <label class="control-label" class="form-control" for="empleado">Empleado: *</label>
              <select id="empleado" class="form-control" name="empleado" required style="width: 100%" disabled>
                <option value="">Seleccione...</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group {{ $errors->has('plantilla') ? 'has-error' : '' }}">
              <label class="control-label" class="form-control" for="plantilla">Plantilla: *</label>
              <select id="plantilla" class="form-control" name="plantilla" required style="width: 100%">
                <option value="">Seleccione...</option>
                @foreach($plantillas as $plantilla)
                  <option value="{{ $plantilla->id }}"{{ old('plantilla', $documento->plantilla_id) == $plantilla->id ? ' selected' : '' }}>{{ $plantilla->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group {{ $errors->has('padre') ? 'has-error' : '' }}">
              <label class="control-label" class="form-control">Documento padre:</label>
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
            <div class="form-group {{ $errors->has('caducidad') ? 'has-error' : '' }}">
              <label class="control-label" for="caducidad">Fecha de caducidad:</label>
              <input id="caducidad" class="form-control" type="text" name="caducidad" value="{{ old('caducidad', $documento->caducidad) }}" placeholder="dd-mm-yyyy">
            </div>
          </div>
        </div>

        <h4>Completar variables</h4>

        <div class="section-variables form-horizontal mb-1">
          <p class="text-center text-muted m-0">No hay variables a completar</p>
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

        <div class="form-group text-right mt-2">
          <a class="btn btn-flat btn-default" href="{{ route('plantilla.documento.show', [$plantilla->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    const sectionVariables = $('.section-variables')
    const buildGroup = function (index, seccion){
      let vargGroups = '';

      $.each(seccion.variables, function (k, v){
        let tipo = converType(v.tipo)
        let value = getOldValue(seccion.id, v.variable)
        vargGroups += `<div class="form-group">
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

    const oldSectionsValues = @json(old('secciones', $documento->secciones));

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
        case 'string':
        case 'rut':
        default:
          return 'text'
          break;
      }
    }

    function getOldValue(seccion, variable){
      return (oldSectionsValues && oldSectionsValues.hasOwnProperty(seccion)) ? oldSectionsValues[seccion][variable] : '';
    }

    $(document).ready( function(){
      $('#contrato, #empleado, #plantilla, #padre').select2({
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
          url: '{{ route("empleados.index") }}/contratos/' + contrato,
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
        })
        .always(function(){

        })
      })

      $('#plantilla').change(function () {
        let plantilla = $(this).val()

        if(plantilla < 1){
          return false;
        }

        $.ajax({
          type: 'GET',
          url: `{{ route("plantilla.index") }}/${plantilla}/variables`,
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
        })
        .fail(function () {

        })
        .always(function () {

        })
      })

      $('#contrato, #plantilla').trigger('change')
    });
  </script>
@endsection
