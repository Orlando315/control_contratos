@extends('layouts.app')

@section('title', 'Anticipos')

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
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('anticipos.index') }}">Anticipos</a></li>
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
          <h5>Agregar anticipo masivo</h5>
        </div>
        <div class="ibox-content">
          <form id="form-anticipos" action="{{ route('anticipos.storeMasivo') }}" method="POST">
            <input id="empleados" type="hidden" name="empleados">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ old('contrato') == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
                    @endforeach
                  </select>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  <label for="fecha">Fecha: *</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') }}" placeholder="dd-mm-yyyy" required>
                </div>                
              </div>
            </div>

            <fieldset>
              <legend style="border-bottom: none">Empleados</legend>

              <table class="table table-sm table-bordered table-condensed table-anticipos">
                <thead>
                  <tr>
                    <th>Empleado</th>
                    <th>Anticipo</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="tbody-empleados">
                </tbody>
              </table>
            </fieldset>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ url()->previous() }}"><i class="fa fa-reply"></i> Atras</a>
              <button id="btn-submit" class="btn btn-primary btn-sm" type="submit" disabled><i class="fa fa-send"></i> Guardar</button>
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
    let tbody = $('#tbody-empleados')
    let submit = $('#btn-submit')
    let empleados = {}
    let empleadosField = $('#empleados')
    let createElement = function(id, name, anticipo){
      return `<tr>
                <td>${name}</td>
                <td>
                  <div class="form-group m-0">
                    <input id="empleado_${id}" data-id="${id}" class="form-control input-sm input-anticipo" type="number" value="${anticipo}" required>
                  </div>
                </td>
                <td>
                  <div class="checkbox">
                    <label class="container-checkbox m-0">
                      <input type="checkbox">
                      <span class="checkmark-check"></span>
                    </label>
                  </div>
                </td>
              </tr>`
    }

    $(document).ready( function(){
      $('#fecha').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        endDate: 'today',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#contrato').change(getEmpleados)
      $('#contrato').change()

      $('#contrato').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

      $('#tbody-empleados').on('change', '.input-anticipo', updateEmpleadosInfo)

      $('#tbody-empleados').on('click', 'input[type="checkbox"]', function(){
        if($(this).is(':checked')){
          $(this).closest('div.checkbox').removeClass('has-error');
        }
      })

      $('#form-anticipos').submit(function(e){
        e.preventDefault();
        let checkboxs = $('#tbody-empleados input[type="checkbox"]');
        let counter = 0;
        $.each(checkboxs, function(k, v){
          if(!$(v).is(':checked')){
            counter++
          }

          $(v).closest('div.checkbox').toggleClass('has-error', !$(v).is(':checked'));
        })

        if(counter == 0){
          e.currentTarget.submit();
        }else{
          $('.alert ul').empty().append(`<li>Debe marcar a todos los empleados antes de continuar.</li>`)
          $('.alert').show().delay(5000).hide('slow')
        }
      })
    });

    function updateEmpleadosInfo(){
      let input = $(this),
          id = input.data('id'),
          anticipo = input.val();

      empleados[id] = anticipo * 1;
      empleadosField.val(JSON.stringify(empleados))
    }

    function getEmpleados(){
      let contrato = $(this).val();

      if(contrato == '') return;

      $.ajax({
        type: 'POST',
        url: '{{ route("anticipos.index") }}/empleados/' + contrato,
        data: {
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(data){
        tbody.empty()
        empleados = {}

        if(data.length > 0){
          $.each(data, function(k, v){
            let anticipo = v.latest_anticipo ? v.latest_anticipo.anticipo : 0;

            empleados[v.id] = anticipo

            let name = `${v.usuario.rut} | ${v.usuario.nombres} ${v.usuario.apellidos}`
            let element = createElement(v.id, name, anticipo)
            tbody.append(element)
          })

          submit.prop('disabled', false)
        }else{
          tbody.insertRow(0)
              .insertCell(0).innerHTML = 'No hay empleados registrados'
          submit.prop('disabled', true)
        }
      })
      .fail(function(){
        tbody.empty()
        empleados = {}
        submit.prop('disabled', false)
      })
      .always(function(){
        empleadosField.val(JSON.stringify(empleados))
      })
    }
  </script>
@endsection
