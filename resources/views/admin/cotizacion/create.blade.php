@extends('layouts.app')

@section('title', 'Cotizaciones')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Cotizaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cotizacion.index') }}">Cotizaciones</a></li>
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
          <h5>Agregar cotización</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group{{ $errors->has('cliente') ? ' has-error' : '' }}">
                <label for="cliente">Cliente: *</label>
                <select id="cliente" class="form-control">
                  <option value="">Seleccione...</option>
                  @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}"{{ old('cliente', optional($selectedCliente)->id) == $cliente->id ? ' selected' : '' }}>{{ $cliente->nombre }}</option>
                  @endforeach
                </select>

                <button class="btn btn-simple btn-link btn-sm" type="button" data-toggle="modal" data-target="#optionModal" data-option="tipo"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Cliente</button>
              </div>
            </div>
          </div>

          <form id="add-product-form" action="#" method="POST">
            <fieldset>
              <legend class="form-legend">Productos</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="inventario">Inventario:</label>
                    <select id="inventario" class="form-control">
                      <option value="">Seleccione...</option>
                      @foreach($inventarios as $inventario)
                        <option value="{{ $inventario->id }}" data-precio="{{ $inventario->valor }}">{{ $inventario->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="tipo_codigo">Tipo código: *</label>
                    <input id="tipo_codigo" class="form-control" type="text" maxlength="20" placeholder="Tipo código">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="codigo">Código: *</label>
                    <input id="codigo" class="form-control" type="text" maxlength="50" placeholder="Código">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="nombre">Nombre: *</label>
                    <input id="nombre" class="form-control" type="text" maxlength="100" placeholder="Nombre del producto" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="cantidad">Cantidad: *</label>
                    <input id="cantidad" class="form-control" type="number" min="1" max="99999" placeholder="Cantidad" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="precio">Precio: *</label>
                    <input id="precio" class="form-control" type="number" min="1" max="99999999" step="0.01" placeholder="Precio" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="impuesto">Impuesto:</label>
                    <input id="impuesto" class="form-control" type="number" min="0" max="99999999" step="0.01" placeholder="Impuesto">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input id="descripcion" class="form-control" type="text" maxlength="200" placeholder="Descripción">
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="row justify-content-center mb-3">
              <div class="col-md-3">
                <button id="btn-add-product" class="btn btn-block btn-primary" type="submit">Agregar producto</button>
              </div>
            </div>
          </form>

          <form action="{{ route('admin.cotizacion.store') }}" method="POST">
            @csrf
            <input id="cliente-form" type="hidden" name="cliente" value="{{ old('cliente') }}">

            <table class="table table-bordered">
              <colgroup>
                 <col span="1" style="width: 5%;">
                 <col span="1" style="width: 10%;">
                 <col span="1" style="width: 10%;">
                 <col span="1" style="width: 35%;">
                 <col span="1" style="width: 5%;">
                 <col span="1" style="width: 10%;">
                 <col span="1" style="width: 10%;">
                 <col span="1" style="width: 15%;">
              </colgroup>
              <thead>
                <tr class="text-center">
                  <th class="align-middle">-</th>
                  <th class="align-middle">Tipo</br>código</th>
                  <th class="align-middle">Código</th>
                  <th class="align-middle">Nombre</th>
                  <th class="align-middle">Cantidad</th>
                  <th class="align-middle">Precio</th>
                  <th class="align-middle">Impuesto</br>adicional</th>
                  <th class="align-middle">Total</th>
                </tr>
              </thead>
              <tbody id="tbody-productos" class="is-empty">
                @forelse(old('productos', []) as $index => $producto)
                  <tr id="tr-${index}" class="tr-producto" data-total="{{ $producto['total'] }}">
                    <td class="text-center align-middle">
                      <button class="btn btn-danger btn-xs btn-delete m-0" type="button" role="button" data-id="{{ $index }}"><i class="fa fa-trash"></i></button>
                    </td>
                    <td>
                      {{ $producto['tipo_codigo'] }}
                      <input type="hidden" name="productos[{{ $index }}][tipo_codigo]" value="{{ $producto['tipo_codigo'] }}">
                    </td>
                    <td>
                      {{ $producto['codigo'] }}
                      <input type="hidden" name="productos[{{ $index }}][codigo]" value="{{ $producto['codigo'] }}">
                    </td>
                    <td>
                      {{ $producto['nombre'] }}
                      @if($producto['descripcion'])
                        <p class="m-0"><small>{{ $producto['descripcion'] }}</small></p>
                      @endif
                      <input type="hidden" name="productos[{{ $index }}][inventario]" value="{{ $producto['inventario'] }}">
                      <input type="hidden" name="productos[{{ $index }}][nombre]" value="{{ $producto['nombre'] }}">
                      <input type="hidden" name="productos[{{ $index }}][descripcion]" value="{{ $producto['descripcion'] }}">
                    </td>
                    <td class="text-center">
                      {{ $producto['cantidad'] }}
                      <input type="hidden" name="productos[{{ $index }}][cantidad]" value="{{ $producto['cantidad'] }}">
                    </td>
                    <td class="text-right">
                      {{ number_format($producto['precio'], 2, ',', '.') }}
                      <input type="hidden" name="productos[{{ $index }}][precio]" value="{{ $producto['precio'] }}">
                    </td>
                    <td class="text-right">
                      {{ number_format($producto['impuesto'], 2, ',', '.') }}
                      <input type="hidden" name="productos[{{ $index }}][impuesto]" value="{{ $producto['impuesto'] }}">
                    </td>
                    <td class="text-right">
                      {{ number_format($producto['total'], 2, ',', '.') }}
                      <input type="hidden" name="productos[{{ $index }}][total]" value="{{ $producto['total'] }}">
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center text-muted" colspan="8">No se han agregado productos.</td>
                  </tr>
                @endforelse
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-right" colspan="7">TOTAL</th>
                  <td id="total-general" class="text-right"></td>
                </tr>
              </tfoot>
            </table>

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
              <a class="btn btn-default btn-sm" href="{{ $selectedCliente ? route('admin.cliente.show', ['cliente' => $selectedCliente->id]) : route('admin.cotizacion.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="optionModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="add-cliente-form" action="#" method="POST">
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>

            <h4 class="modal-title" id="optionModalLabel">Agregar Cliente</h4>
          </div>
          <div class="modal-body">

            <div class="row justify-content-center">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="cliente-type">Tipo:</label>
                  <select id="cliente-type" class="custom-select">
                    <option value="persona">Persona</option>
                    <option value="empresa">Empresa</option>
                  </select>
                </div>
              </div>
            </div>

            <fieldset id="cliente-type-persona">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="persona-nombre">Nombre: *</label>
                    <input id="persona-nombre" class="form-control" type="text" name="nombre" maxlength="100" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="persona-telefono">Teléfono: *</label>
                    <input id="persona-telefono" class="form-control" type="telefono" name="telefono" maxlength="20" placeholder="Teléfono" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="persona-rut">RUT: *</label>
                    <input id="persona-rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dkK])$" placeholder="RUT" required>
                    <small class="form-text text-muted">Ejemplo: 00000000-0</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="persona-email">Email:</label>
                    <input id="persona-email" class="form-control" type="text" name="email" maxlength="50" placeholder="Email">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="persona-ciudad">Ciudad:</label>
                    <input id="persona-ciudad" class="form-control" type="text" name="ciudad" maxlength="50" placeholder="Ciudad">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="persona-comuna">Comuna:</label>
                    <input id="persona-comuna" class="form-control" type="text" name="comuna" maxlength="50" placeholder="Comuna">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="persona-direccion">Dirección:</label>
                <input id="persona-direccion" class="form-control" type="text" name="direccion" maxlength="200" placeholder="Dirección">
              </div>

              <div class="form-group">
                <label for="persona-descripcion">Descripción:</label>
                <input id="persona-descripcion" class="form-control" type="text" name="descripcion" maxlength="200" placeholder="Descripción">
              </div>
            </fieldset>

            <fieldset id="cliente-type-empresa" disabled>
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="empresa-rut">RUT: *</label>
                    <div class="input-group">
                      <input id="empresa-rut" class="form-control" type="text" name="rut" maxlength="9" pattern="^(\d{4,9})$" placeholder="RUT" required>
                      <div class="input-group-append">
                        <span class="input-group-addon border-right-0">-</span>
                        <input id="empresa-digito_validador" class="form-control" type="text" name="digito_validador" maxlength="1" pattern="^([\dkK])$" placeholder="DV" required style="width:75px">
                        <button class="btn btn-default btn-xs border-left-0 btn-consultar" type="button"><i class="fa fa-search"></i> Consultar</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="empresa-razon_social">Razón social:</label>
                <input id="empresa-razon_social" class="form-control" type="text" readonly>
              </div>

              <fieldset>
                <legend class="form-legend">Dirección</legend>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="empresa-ciudad">Ciudad:</label>
                      <input id="empresa-ciudad" class="form-control" type="text" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="empresa-comuna">Comuna:</label>
                      <input id="empresa-comuna" class="form-control" type="telefono" readonly>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="empresa-direccion">Dirección:</label>
                  <input id="empresa-direccion" class="form-control" type="text" readonly>
                </div>
              </fieldset><!-- direccion -->

              <fieldset>
                <legend class="form-legend">Contactos</legend>

                <div id="section-contactos">
                  <div id="contacto-0" class="border-bottom mb-3">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="nombre-0">Nombre: *</label>
                          <input id="nombre-0" class="form-control" type="text" name="contactos[0][nombre]" maxlength="100" placeholder="Nombre" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="telefono-0">Teléfono: *</label>
                          <input id="telefono-0" class="form-control" type="telefono" name="contactos[0][telefono]" maxlength="20" placeholder="Teléfono" required>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="email-0">Email:</label>
                          <input id="email-0" class="form-control" type="email" name="contactos[0][email]" maxlength="50" placeholder="Email">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="cargo-0">Cargo:</label>
                          <input id="cargo-0" class="form-control" type="text" name="contactos[0][cargo]" maxlength="50" placeholder="Cargo">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="descripcion-0">Descripción:</label>
                      <input id="descripcion-0" class="form-control" type="text" name="contactos[0][descripcion]" maxlength="100" placeholder="Descripción">
                    </div>
                  </div>
                </div>
              </fieldset><!-- contactos -->
            </fieldset><!-- #cliente-persona -->

            <div class="form-group">
              <label>Proveedor:</label>
              <div class="custom-control custom-checkbox">
                <input id="proveedor" class="custom-control-input" type="checkbox" name="proveedor" value="1">
                <label class="custom-control-label" for="proveedor">
                  Es proveedor
                </label>
              </div>
              <small class="form-text text-muted">Se creará un registro de Proveedor usando la misma información</small>
            </div>

            <div class="alert alert-danger" style="display: none">
              <ul class="m-0 form-errors-cliente">
              </ul>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm btn-add-cliente" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    const TBODY_PRODUCTOS = $('#tbody-productos');
    const BTN_ADD_PRODUCT = $('#btn-add-product');
    
    const BTN_ADD_CLIENTE = $('.btn-add-cliente');
    const BTN_CONSULTAR_EMPRESA = $('.btn-consultar');
    const INTEGRATION_COMPLETE = @json(Auth::user()->empresa->configuracion->isIntegrationComplete('sii'));

    $(document).ready(function () {
      $('#cliente').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#inventario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      $('#cliente').change(loadCliente);
      $('#inventario').change(selectInventario);
      $('#add-product-form').submit(addProduct);
      TBODY_PRODUCTOS.on('click', '.btn-delete', deleteProduct);

      $('#add-cliente-form').submit(addCliente);
      $('#cliente-type').change(selectClienteType);
      $('#cliente-type').change();
      BTN_CONSULTAR_EMPRESA.click(consultarClienteEmpresa)

      $('#inventario').change();
      $('#cliente').change();
      calculateTotal();
    });

    function loadCliente(){
      $('#cliente-form').val($(this).val());
    }

    function selectInventario(){
      let id = $(this).val();
      let option = $(this).find(`option[value="${id}"]`);
      let hasValue = id != '';
      let nombre = hasValue ? option.text() : '';
      let precio = hasValue ? option.data('precio') : '';

      $('#nombre').prop('readonly', hasValue).val(nombre);
      $('#tipo_codigo, #codigo, #cantidad, #impuesto').val('');
      $('#precio').val(precio);
    }

    function addProduct(e){
      e.preventDefault();

      BTN_ADD_PRODUCT.prop('disabled', true);

      let cantidad = +$('#cantidad').val();
      let precio = +$('#precio').val();
      let impuesto = +$('#impuesto').val();
      let total = +((cantidad * precio) + impuesto);

      let data = {
        inventario: $('#inventario').val(),
        tipoCodigo: $('#tipo_codigo').val(),
        codigo: $('#codigo').val(),
        nombre: $('#nombre').val(),
        cantidad: cantidad,
        precio: precio,
        impuesto: impuesto,
        total: total,
        descripcion: $('#descripcion').val(),
      };
      let index = Date.now();

      if(TBODY_PRODUCTOS.hasClass('is-empty')){
        TBODY_PRODUCTOS.removeClass('is-empty').empty();
      }

      TBODY_PRODUCTOS.append(producto(index, data));
      calculateTotal();
      BTN_ADD_PRODUCT.prop('disabled', false);
      $(this)[0].reset();
      $('#inventario').val(null).trigger('change');
    }

    function calculateTotal(){
      let productos = $('.tr-producto');
      let total = 0;
      productos.each((index, producto) => {
        total += +$(producto).data('total');
      });

      $('#total-general').text(formatNumbers(total));
    }

    function deleteProduct(){
      let index = $(this).data('id');
      $(`#tr-${index}`).remove();

      let productos = $('.tr-producto').length;

      if(productos > 0){
        calculateTotal(); 
      }else{
        TBODY_PRODUCTOS
          .addClass('is-empty')
          .append('<tr class="tr-empty"><td class="text-center text-muted" colspan="8">No se han agregado productos.</td></tr>');
      }
    }

    function formatNumbers(number){
      return (+number).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits : 2});
    }

    // Informacion del Producto
    let producto = function(index, data) {
      return `<tr id="tr-${index}" class="tr-producto" data-total="${data.total}">
                <td class="text-center align-middle">
                  <button class="btn btn-danger btn-xs btn-delete m-0" type="button" role="button" data-id="${index}"><i class="fa fa-trash"></i></button>
                </td>
                <td>
                  ${data.tipoCodigo}
                  <input type="hidden" name="productos[${index}][tipo_codigo]" value="${data.tipoCodigo}">
                </td>
                <td>
                  ${data.codigo}
                  <input type="hidden" name="productos[${index}][codigo]" value="${data.codigo}">
                </td>
                <td>
                  ${data.nombre}
                  ${data.descripcion ? ('<p class="m-0"><small>'+data.descripcion+'</small></p>') : ''}
                  <input type="hidden" name="productos[${index}][inventario]" value="${data.inventario}">
                  <input type="hidden" name="productos[${index}][nombre]" value="${data.nombre}">
                  <input type="hidden" name="productos[${index}][descripcion]" value="${data.descripcion}">
                </td>
                <td class="text-center">
                  ${data.cantidad}
                  <input type="hidden" name="productos[${index}][cantidad]" value="${data.cantidad}">
                </td>
                <td class="text-right">
                  ${formatNumbers(data.precio)}
                  <input type="hidden" name="productos[${index}][precio]" value="${data.precio}">
                </td>
                <td class="text-right">
                  ${formatNumbers(data.impuesto)}
                  <input type="hidden" name="productos[${index}][impuesto]" value="${data.impuesto}">
                </td>
                <td class="text-right">
                  ${formatNumbers(data.total)}
                  <input type="hidden" name="productos[${index}][total]" value="${data.total}">
                </td>
              </tr>`;
    }

    function selectClienteType(){
      let type = $(this).val();

      $('#cliente-type-empresa, #cliente-type-persona').prop('disabled', true).toggle(false);
      $(`#cliente-type-${type}`).prop('disabled', false).toggle(true);

      BTN_ADD_CLIENTE.prop('disabled', type != 'persona');
    }

    function addCliente(e){
      e.preventDefault();

      BTN_ADD_CLIENTE.prop('disabled', true);

      let form = $(this);
      let type = $('#cliente-type').val();
      let action = type == 'persona'
        ? '{{ route("admin.cliente.store", ["type" => "persona"]) }}'
        : '{{ route("admin.cliente.store", ["type" => "empresa"]) }}';

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function (response) {
        if(response.response){
          $('#cliente').append(`<option value="${response.cliente.id}">${response.cliente.nombre}</option>`)
          $('#cliente').val(response.cliente.id);
          $('#cliente').trigger('change');
          form[0].reset();
          $('#cliente-type').trigger('change');
          $('#optionModal').modal('hide');
        }else{
          showErrors(['Ha ocurrido un error.'], '.form-errors-cliente');
        }
      })
      .fail(function (response) {
        showErrors(response.responseJSON.errors, '.form-errors-cliente');
      })
      .always(function () {
        BTN_ADD_CLIENTE.prop('disabled', false);
      });
    }

    function showErrors(errors, ul){
      $(ul).empty();

      $.each(errors, function (k, v){
        if($.isArray(v)){
          $.each(v, function (k2, error){
            $(ul).append(`<li>${error}</li>`);
          })
        }else{
          $(ul).append(`<li>${v}</li>`);
        }
      });

      $(ul).parent().show().delay(7000).hide('slow');
    }

    function consultarClienteEmpresa(){
      BTN_CONSULTAR_EMPRESA.prop('disabled', true);

      if(!INTEGRATION_COMPLETE){
        showErrors(['Debe completar los datos de su integración con Facturación Sii.'], '.form-errors-cliente');
        BTN_CONSULTAR_EMPRESA.prop('disabled', false);
      }

      let rut = $('#empresa-rut').val();
      let dv = $('#empresa-digito_validador').val();

      if(rut.length < 5 || !rut || !dv){
        return false;
      }

      getDataEmpresa(rut, dv);
    }

    function getDataEmpresa(rut, dv){
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
          $('#empresa-razon_social').val(response.data.razon_social);
          $('#empresa-direccion').val(response.data.direccion);
          $('#empresa-comuna').val(response.data.comuna);
          $('#empresa-ciudad').val(response.data.ciudad);

          BTN_ADD_CLIENTE.prop('disabled', false);
        }else{
          BTN_ADD_CLIENTE.prop('disabled', true);
          showErrors([response.data], '.form-errors-cliente');
        }
      })
      .fail(function (data) {
        showErrors(['Ha ocurrido un error al consultar la información.'], '.form-errors-cliente');
        BTN_ADD_CLIENTE.prop('disabled', true);
      })
      .always(function () {
        BTN_CONSULTAR_EMPRESA.prop('disabled', false);
      });
    }

  </script>
@endsection
