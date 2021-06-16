@extends('layouts.app')

@section('title', 'Facturaciones')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Facturaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.compra.show', ['compra' => $compra->id]) }}">Facturaciones</a></li>
        <li class="breadcrumb-item active"><strong>Asociar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Asociar facturación</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <form action="{{ route('admin.compra.facturacion.store', ['compra' => $compra->id]) }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('factura') ? ' has-error' : '' }}">
                  <label for="factura">Factura: *</label>
                  <select id="factura" class="form-control" name="factura" required>
                    <option value="">Seleccione...</option>
                    @foreach($facturas as $factura)
                      <option
                        value="{{ $factura['codigo'] }}"{{ old('factura') == $factura['codigo'] ? ' selected' : '' }}
                        data-emisor="{{ $factura['emisor'] ?? '' }}"
                        data-razon_social="{{ $factura['razon_social'] }}"
                        data-documento="{{ $factura['documento'] }}"
                        data-folio="{{ $factura['folio'] }}"
                        data-fecha="{{ $factura['fecha'] }}"
                        data-monto="{{ $factura['monto'] }}"
                        data-estado="{{ $factura['estado'] }}"
                      >
                      {{ $factura['codigo'] }} | {{ $factura['emisor'] ?? 'N/A' }} {{ $factura['razon_social'] }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="codigo">Código:</label>
                  <input id="codigo" class="form-control" type="text" name="codigo" placeholder="Código" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="emisor">Emisor:</label>
                  <input id="emisor" class="form-control" type="text" name="emisor" placeholder="Emisor" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="razon_social">Razón social:</label>
                  <input id="razon_social" class="form-control" type="text" name="razon_social" placeholder="Razón social" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="documento">Documento:</label>
                  <input id="documento" class="form-control" type="text" name="documento" placeholder="Documento" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="folio">Folio:</label>
                  <input id="folio" class="form-control" type="text" name="folio" placeholder="Folio" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="fecha">Fecha:</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" placeholder="Fecha" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="monto">Monto:</label>
                  <input id="monto" class="form-control" type="text" name="monto" placeholder="Monto" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="estado">Estado:</label>
                  <input id="estado" class="form-control" type="text" name="estado" placeholder="Estado" readonly>
                </div>
              </div>
            </div>

            <fieldset>
              <legend class="form-legend">Productos en factura</legend>
              
              <table class="table table-bordered">
                <colgroup>
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 50%;">
                   <col span="1" style="width: 5%;">
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 15%;">
                </colgroup>
                <thead>
                  <tr class="text-center">
                    <th class="align-middle">Código</th>
                    <th class="align-middle">Nombre</th>
                    <th class="align-middle">Cantidad</th>
                    <th class="align-middle">Precio</th>
                    <th class="align-middle">Impuesto</br>adicional</th>
                    <th class="align-middle">Descuento</th>
                    <th class="align-middle">Total</th>
                  </tr>
                </thead>
                <tbody id="tbody-productos">
                  <tr><td class="text-center text-muted" colspan="7">No hay productos o no se ha seleccionado una factura.</td></tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="text-right" colspan="6">TOTAL</th>
                    <td id="total-general" class="text-right"></td>
                  </tr>
                </tfoot>
              </table>
            </fieldset>

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
              <a class="btn btn-default btn-sm" href="{{ route('admin.compra.show', ['compra' => $compra->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"{{ Auth::user()->empresa->configuracion->doesntHaveSiiAccount() ? ' disabled' : '' }}><i class="fa fa-send"></i> Guardar</button>
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
  <script type="text/javascript">
    const IBOX = $('.ibox-content');
    let URL = '{{ route("admin.compra.facturacion.productos", ["codigo" => ":codigo"]) }}';
    const TBODY_PRODUCTOS = $('#tbody-productos');

    $(document).ready(function () {
      $('#factura').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#factura').change(loadData);
      $('#factura').change();
    });

    function loadData(){
      let codigo = $(this).val();

      if(!codigo){
        $('#codigo, #emisor, #razon_social, #documento, #folio, #fecha, #monto').val('');
        return false;
      }

      let option = $(this).find(`option[value="${codigo}"]`);
      let emisor = option.data('emisor');
      let razon_social = option.data('razon_social');
      let documento = option.data('documento');
      let folio = option.data('folio');
      let fecha = option.data('fecha');
      let monto = option.data('monto');
      let estado = option.data('estado');

      $('#codigo').val(codigo);
      $('#emisor').val(emisor);
      $('#razon_social').val(razon_social);
      $('#documento').val(documento);
      $('#folio').val(folio);
      $('#fecha').val(fecha);
      $('#monto').val(monto);
      $('#estado').val(estado);

      getProductos(codigo);
    }

    function getProductos(codigo){
      let action = URL.replace(':codigo', codigo);

      IBOX.toggleClass('sk-loading', true);

      $.ajax({
        type: 'GET',
        url: action,
        cache: false,
        dataType: 'json',
      })
      .done(function (response) {
        TBODY_PRODUCTOS.empty();

        if(response.productos.length > 0){
          $.each(response.productos, function (k, producto) {
            TBODY_PRODUCTOS.append(templateProducto(producto));
          });
          calculateTotal();
        }else{
          TBODY_PRODUCTOS.append('<tr><td class="text-center text-muted" colspan="7">No hay productos o no se ha seleccionado una cotización.</td></tr>');
        }
      })
      .fail(function (response) {

      })
      .always(function () {
        IBOX.toggleClass('sk-loading', false);
      })
    }

    function formatNumbers(number){
      return (+number).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits : 2});
    }

    function calculateTotal(){
      let productos = $('.tr-producto');
      let total = 0;
      productos.each((index, producto) => {
        total += +$(producto).data('total');
      });

      $('#total-general').text(formatNumbers(total));
    }

    function calculateTotalFromProduct(textCantidad, precio){
      let cantidad = +textCantidad.split(' ')[0];
      precio = +precio;

      if(isNaN(cantidad)){
        return 0;
      }

      return Number(cantidad.toFixed(2)) * Number(precio.toFixed(2));
    }

    // Informacion del Producto
    let templateProducto = function(data) {
      let total = calculateTotalFromProduct(data.cantidad, data.precio);

      return `<tr class="tr-producto" data-total="${total}">
                <td>
                  ${data.codigo}
                </td>
                <td>
                  ${data.descripcion.nombre}
                  ${data.descripcion.descripcion ? ('<p class="m-0"><small>'+data.descripcion.descripcion+'</small></p>') : ''}
                </td>
                <td class="text-center">
                  ${data.cantidad}
                </td>
                <td class="text-right">
                  ${formatNumbers(data.precio)}
                </td>
                <td class="text-right">
                  ${formatNumbers(data.impuestoAdicional)}
                </td>
                <td class="text-right">
                  ${formatNumbers(data.descuento)}
                </td>
                <td class="text-right">
                  ${formatNumbers(total)}
                </td>
              </tr>`;
    }
  </script>
@endsection
