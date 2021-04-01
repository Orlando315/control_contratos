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
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.anticipos.index') }}">Anticipos</a></li>
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
          <h5>Agregar anticipo masivo</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <form id="form-anticipos" action="{{ route('admin.anticipos.storeMasivo') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ old('contrato', ($contrato->isMain() ? $contrato->id : '')) == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
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

            <p class="text-center text-muted">Formatos permitidos para adjuntos: jpg, jpeg, png, pdf, txt, xlsx, docx</p>

            <fieldset>
              <legend style="border-bottom: none">Empleados</legend>

              <table class="table table-sm table-bordered table-condensed table-anticipos">
                <thead>
                  <tr>
                    <th></th>
                    <th class="align-middle">Empleado</th>
                    <th class="align-middle">Anticipo</th>
                    <th class="align-middle">Bono</th>
                    <th class="text-center align-middle">
                      <div class="custom-control custom-checkbox">
                        <input id="check-master" class="custom-control-input" type="checkbox">
                        <label for="check-master" class="custom-control-label"></label>
                      </div>
                    </th>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.anticipos.index') }}"><i class="fa fa-reply"></i> Atras</a>
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
    const IBOX  = $('.ibox-content');
    let tbody = $('#tbody-empleados')
    let submit = $('#btn-submit')
    let createElement = function(id, name, anticipo){
      return `<tr class="empleado-${id}">
                <td rowspan="2" class="text-center align-middle">
                  <button class="btn btn-danger btn-xs btn-delete" data-empleado="${id}" type="button" role="button"><i class="fa fa-times"></i></button>
                </td>
                <td class="align-middle">${name}</td>
                <td class="align-middle">
                  <div class="form-group m-0">
                    <input class="form-control input-sm" type="number" name="empleados[${id}][anticipo]" min="0" max="99999999" value="${anticipo}" required>
                  </div>
                </td>
                <td class="align-middle">
                  <div class="form-group m-0">
                    <input class="form-control input-sm" type="number" name="empleados[${id}][bono]" min="0" max="99999999" value="0">
                  </div>
                </td>
                <td rowspan="2" class="text-center align-middle">
                  <div class="custom-control custom-checkbox m-0">
                    <input id="check-${id}" class="custom-control-input empleados-check" type="checkbox">
                    <label class="custom-control-label" for="check-${id}"></label>
                  </div>
                </td>
              </tr>
              <tr class="empleado-${id}">
                <td colspan="2" class="align-middle">
                  <div class="form-group m-0">
                    <input class="form-control input-sm" type="text" name="empleados[${id}][descripcion]" maxlength="200" placeholder="Agregar descripción">
                  </div>
                </td>
                <td class="align-middle">
                  <div class="form-group m-0">
                    <div class="custom-file">
                      <input id="adjunto-${id}" class="custom-file-input input-sm" type="file" name="empleados[${id}][adjunto]" data-msg-placeholder="Adjunto" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                      <label class="custom-file-label" for="adjunto-${id}">Adjunto</label>
                    </div>
                  </div>
                </td>
              </tr>`
    }

    $(document).ready( function(){
      $('#fecha').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#contrato').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })

      $('#contrato').change(getEmpleados)
      $('#contrato').change()

      $('#tbody-empleados').on('click', '.empleados-check', empleadosCheck);
      $('#tbody-empleados').on('click', '.btn-delete', deleteEmpleado);
      $('#check-master').click(checkMaster);

      $('#form-anticipos').submit(function(e){
        e.preventDefault();
        let checkboxs = $('.empleados-check')

        checkboxs.filter(':checked').closest('.custom-checkbox').toggleClass('has-error', false);
        checkboxs.not(':checked').closest('.custom-checkbox').toggleClass('has-error', true);

        if(checkboxs.length == checkboxs.filter(':checked').length){
          e.currentTarget.submit();
        }else{
          $('.alert ul').empty().append(`<li>Debe marcar a todos los empleados antes de continuar.</li>`)
          $('.alert').show().delay(5000).hide('slow')
        }
      })

      $('#tbody-empleados').on('change', '.custom-file-input', function () {
        if(this.files && this.files[0]){
          let file = this.files[0];
          let id = $(this).attr('id');

          if([
              'image/png',
              'image/jpeg',
              'text/plain',
              'application/pdf',
              'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]
            .includes(file.type)) {
            changeLabel(id, file.name)
          }else{
            changeLabel(id, 'Seleccionar')
            showAlert(id, 'El archivo no es de un tipo admitido.')
          }
        }
      })

      toggleMasterState()
    });

    function checkMaster() {
      let isChecked = $(this).is(':checked')
      
      $('.empleados-check').prop('checked', isChecked)
      $('.empleados-check').closest('.custom-checkbox').toggleClass('has-error', false);
    }

    function toggleMasterState(){
      let checkboxs = $('.empleados-check')
      let checked = checkboxs.filter(':checked').length

      if(checked > 0 && checked == checkboxs.length){
        $('#check-master').prop('indeterminate', false)
        $('#check-master').prop('checked', true)
      }else if(checked > 0 && checked < checkboxs.length){
        $('#check-master').prop('checked', false)
        $('#check-master').prop('indeterminate', true)
      }else{
        $('#check-master').prop('indeterminate', false)
        $('#check-master').prop('checked', false)
      }
    }

    function getEmpleados(){
      let contrato = $(this).val();

      if(contrato == '') return;

      IBOX.toggleClass('sk-loading', true);

      $.ajax({
        type: 'POST',
        url: '{{ route("admin.anticipos.index") }}/empleados/' + contrato,
        data: {
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(data){
        tbody.empty()

        if(data.length > 0){
          $.each(data, function(k, v){
            let anticipo = v.latest_anticipo ? v.latest_anticipo.anticipo : 0;

            let name = `${v.usuario.rut} | ${v.usuario.nombres} ${v.usuario.apellidos ?? ''}`
            let element = createElement(v.id, name, anticipo)
            tbody.append(element)
          })

          submit.prop('disabled', false)
        }else{
          tbody.append('<tr><td colspan="5" class="text-center text-muted">No hay empleados registrados</td></tr>');
          submit.prop('disabled', true)
        }
      })
      .fail(function(){
        tbody.empty()
        submit.prop('disabled', false)
      })
      .always(function () {
        IBOX.toggleClass('sk-loading', false);
      })
    }

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(id, name){
      $(`#${id}`).siblings(`label[for="${id}"]`).text(name);
    }

    function showAlert(id, error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
      $(`#${id}`).val('')
    }

    function empleadosCheck() {
      if($(this).is(':checked')){
        $(this).closest('.custom-checkbox').removeClass('has-error');
      }

      toggleMasterState()
    }

    function deleteEmpleado() {
      let empleado = $(this).data('empleado');

      $(`.empleado-${empleado}`).remove();
    }
  </script>
@endsection
