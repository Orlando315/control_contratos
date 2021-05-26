@extends('layouts.app')

@section('title', 'Clientes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Clientes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cliente.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      @if(sii()->isInactive())
        <div class="alert alert-danger alert-important">
          <p class="m-0">¡Integración no disponible! Comuniquese con el administrador.</p>
        </div>
      @endif

      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar cliente - Empresa</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
          <form action="{{ route('admin.cliente.store', ['type' => 'empresa']) }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-12">
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <div class="input-group">
                    <input id="rut" class="form-control" type="text" name="rut" maxlength="9" pattern="^(\d{4,9})$" value="{{ old('rut') }}" placeholder="RUT" required>
                    <div class="input-group-append">
                      <span class="input-group-addon border-right-0">-</span>
                      <input id="digito_validador" class="form-control" type="text" name="digito_validador" maxlength="1" pattern="^([\dkK])$" value="{{ old('digito_validador') }}" placeholder="DV" required style="width:75px">
                      <button class="btn btn-default btn-xs border-left-0 btn-consultar" type="button"><i class="fa fa-search"></i> Consultar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="razon_social">Razón social:</label>
              <input id="razon_social" class="form-control" type="text" readonly>
            </div>

            <fieldset>
              <legend class="form-legend">Dirección</legend>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ciudad">Ciudad:</label>
                    <input id="ciudad" class="form-control" type="text" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="comuna">Comuna:</label>
                    <input id="comuna" class="form-control" type="telefono" readonly>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input id="direccion" class="form-control" type="text" readonly>
              </div>
            </fieldset>

            <fieldset>
              <legend class="form-legend">Contactos</legend>

              {{--
                Usado para traer los valores old() de cada "contacto". Si no hay ninguno, se crea al menos 1 campo ['1' => 1].
              --}}
              <div id="section-contactos">
                @foreach(old('contactos', ['1' => 1]) as $index => $null)
                  <div class="border-bottom mb-3" id="contacto-{{ $index }}">
                    <h4>
                      <button class="btn btn-danger btn-xs btn-delete-contacto" type="button" data-index="{{ $index }}">
                        <i class="fa fa-times" aria-hidden="true"></i>
                      </button>
                      | Contacto #<span class="contacto-index">{{ $loop->iteration }}</span>
                    </h4>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group{{ $errors->has('contactos.'.$index.'.nombre') ? ' has-error' : '' }}">
                          <label for="nombre-{{ $index }}">Nombre: *</label>
                          <input id="nombre-{{ $index }}" class="form-control" type="text" name="contactos[{{ $index }}][nombre]" maxlength="100" value="{{ old('contactos.'.$index.'.nombre') }}" placeholder="Nombre" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group{{ $errors->has('contactos.'.$index.'.telefono') ? ' has-error' : '' }}">
                          <label for="telefono-{{ $index }}">Teléfono: *</label>
                          <input id="telefono-{{ $index }}" class="form-control" type="telefono" name="contactos[{{ $index }}][telefono]" maxlength="20" value="{{ old('contactos.'.$index.'.telefono') }}" placeholder="Teléfono" required>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group{{ $errors->has('contactos.'.$index.'.email') ? ' has-error' : '' }}">
                          <label for="email-{{ $index }}">Email:</label>
                          <input id="email-{{ $index }}" class="form-control" type="email" name="contactos[{{ $index }}][email]" maxlength="50" value="{{ old('contactos.'.$index.'.email') }}" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group{{ $errors->has('contactos.'.$index.'.cargo') ? ' has-error' : '' }}">
                          <label for="cargo-{{ $index }}">Cargo:</label>
                          <input id="cargo-{{ $index }}" class="form-control" type="text" name="contactos[{{ $index }}][cargo]" maxlength="50" value="{{ old('contactos.'.$index.'.cargo') }}" placeholder="Cargo">
                        </div>
                      </div>
                    </div>

                    <div class="form-group{{ $errors->has('contactos.'.$index.'.descripcion') ? ' has-error' : '' }}">
                      <label for="descripcion-{{ $index }}">Descripción:</label>
                      <input id="descripcion-{{ $index }}" class="form-control" type="text" name="contactos[{{ $index }}][descripcion]" maxlength="100" value="{{ old('contactos.'.$index.'.descripcion') }}" placeholder="Descripción">
                    </div>
                  </div>
                @endforeach
              </div>

              <button class="btn btn-default btn-block text-center btn-xs add-contacto mb-3" type="button">Agregar contacto</button>
            </fieldset>

            <div class="form-group">
              <label for="">Proveedor:</label>
              <div class="custom-control custom-checkbox">
                <input id="proveedor" class="custom-control-input" type="checkbox" name="proveedor" value="1"{{ old('proveedor') == '1' ? ' checked' : '' }}>
                <label class="custom-control-label" for="proveedor">
                  Es proveedor
                </label>
              </div>
              <small class="form-text text-muted">Se creará un registro de Proveedor usando la misma información</small>
            </div>

            <div class="alert alert-danger alert-important alert-empresa"{!! $errors->any() ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.cliente.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm btn-submit" type="submit" disabled><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    const IBOX = $('.ibox-content');
    const BTN_CONTACTO = $('.add-contacto');
    const BTN_SUBMIT = $('.btn-submit');
    const BTN_CONSULTAR = $('.btn-consultar');
    const ALERT = $('.alert-empresa');
    const INTEGRATION_COMPLETE = @json(sii()->isActive());

    $(document).ready(function () {
      BTN_CONSULTAR.click(function () {
        let rut = $('#rut').val();
        let dv = $('#digito_validador').val();

        if(rut.length < 5 || !rut || !dv){
          return false;
        }

        IBOX.toggleClass('sk-loading', true);

        getData(rut, dv);
      })
      BTN_CONSULTAR.click();

      BTN_CONTACTO.click(addContacto)
      $('#section-contactos').on('click', '.btn-delete-contacto', deleteContacto)
    });

    function getData(rut, dv){
      if(!INTEGRATION_COMPLETE){
        showAlert('Debe completar los datos de su integración con Facturación Sii.');
      }

      BTN_CONSULTAR.prop('disabled', true);

      $.ajax({
        type: 'POST',
        url: '{{ route("admin.cliente.busqueda.sii") }}',
        data: {
          rut: rut,
          dv: dv,
        },
        dataType: 'json',
      })
      .done(function (response) {
        if(response.response){
          $('#razon_social').val(response.data.razon_social);
          $('#direccion').val(response.data.direccion);
          $('#comuna').val(response.data.comuna);
          $('#ciudad').val(response.data.ciudad);

          BTN_SUBMIT.prop('disabled', false)
        }else{
          $('#razon_social, #direccion, #comuna, #ciudad').val('');

          BTN_SUBMIT.prop('disabled', true)
          showAlert(response.data);
        }
      })
      .fail(function (data) {
        showAlert('Ha ocurrido un error al consultar la información.');
        BTN_SUBMIT.prop('disabled', true);
      })
      .always(function () {
        BTN_CONSULTAR.prop('disabled', false);
        IBOX.toggleClass('sk-loading', false);
      })
    }

    function showAlert(message = ''){
      ALERT.find('ul').empty().append(`<li>${message}</li>`);
      ALERT.show().delay(5000).hide('slow');
    }

    function addContacto(){
      BTN_CONTACTO.prop('disabled', true);

      let index = Date.now();
      let iteration = $('.contacto-index').length + 1;

      $('#section-contactos').append(contactoTemplate(index, iteration));

      BTN_CONTACTO.prop('disabled', false);
    }

    function deleteContacto(){
      let index = $(this).data('index');

      if(!index || index < 0){
        showAlert('Ha ocurrido un error al intentar eliminar el contacto.');
      }

      $(`#contacto-${index}`).remove();
      fixIteration();
    }

    function fixIteration(){
      $('.contacto-index').each((index, span) => { $(span).text(index+1) });
    }

    let contactoTemplate = function (index, iteration) {
      return `<div class="border-bottom mb-3" id="contacto-${index}">
                <h4>
                  <button class="btn btn-danger btn-xs btn-delete-contacto" type="button" data-index="${index}">
                    <i class="fa fa-times" aria-hidden="true"></i>
                  </button>
                   | Contacto #<span class="contacto-index">${iteration}</span>
                </h4>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nombre-${index}">Nombre: *</label>
                      <input id="nombre-${index}" class="form-control" type="text" name="contactos[${index}][nombre]" maxlength="100" value="" placeholder="Nombre" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="telefono-${index}">Teléfono: *</label>
                      <input id="telefono-${index}" class="form-control" type="telefono" name="contactos[${index}][telefono]" maxlength="20" value="" placeholder="Teléfono" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email-${index}">Email:</label>
                      <input id="email-${index}" class="form-control" type="email" name="contactos[${index}][email]" maxlength="50" value="" placeholder="Email">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="cargo-${index}">Cargo:</label>
                      <input id="cargo-${index}" class="form-control" type="text" name="contactos[${index}][cargo]" maxlength="50" value="" placeholder="Cargo">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="descripcion-${index}">Descripción:</label>
                  <input id="descripcion-${index}" class="form-control" type="text" name="contactos[${index}][descripcion]" maxlength="100" value="" placeholder="Descripción">
                </div>
              </div>`;
    }
  </script>
@endsection
