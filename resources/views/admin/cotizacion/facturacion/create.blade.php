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
        <li class="breadcrumb-item"><a href="{{ route('admin.cotizacion.facturacion.index') }}">Facturaciones</a></li>
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
          <h5>Agregar facturación</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <form action="{{ route('admin.cotizacion.facturacion.store') }}" method="POST">
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('cotizacion') ? ' has-error' : '' }}">
                  <label for="cotizacion">Cotización: *</label>
                  <select id="cotizacion" class="form-control" name="cotizacion" required>
                    <option value="">Seleccione...</option>
                    @foreach($cotizaciones as $cotizacion)
                      <option
                        value="{{ $cotizacion->id }}"{{ old('cotizacion', optional($selectedCotizacion)->id) == $cotizacion->id ? ' selected' : '' }}
                        data-rut="{{ $cotizacion->cliente->getRut() }}"
                        data-dv="{{ $cotizacion->cliente->getRutDv() }}"
                      >
                      {{ $cotizacion->codigo() }} | {{ $cotizacion->cliente->rut }} {{ $cotizacion->cliente->nombre }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <div class="input-group">
                    <input id="rut" class="form-control" type="text" name="rut" maxlength="9" pattern="^(\d{4,9})$" value="{{ old('rut', $selectedCotizacion ? $selectedCotizacion->cliente->getRut() : '') }}" placeholder="RUT" required>
                    <div class="input-group-append">
                      <span class="input-group-addon border-right-0">-</span>
                      <input id="digito_validador" class="form-control" type="text" name="digito_validador" maxlength="1" pattern="^([\dkK])$" value="{{ old('digito_validador', $selectedCotizacion ? $selectedCotizacion->cliente->getRutDv() : '') }}" placeholder="DV" required style="width:50px">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <fieldset>
              <legend class="form-legend">Productos</legend>
              
              <table class="table table-bordered">
                <colgroup>
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 40%;">
                   <col span="1" style="width: 5%;">
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 10%;">
                   <col span="1" style="width: 15%;">
                </colgroup>
                <thead>
                  <tr class="text-center">
                    <th class="align-middle">Tipo</br>código</th>
                    <th class="align-middle">Código</th>
                    <th class="align-middle">Nombre</th>
                    <th class="align-middle">Cantidad</th>
                    <th class="align-middle">Precio</th>
                    <th class="align-middle">Impuesto</br>adicional</th>
                    <th class="align-middle">Total</th>
                  </tr>
                </thead>
                <tbody id="tbody-productos">
                  @if($selectedCotizacion)
                    @foreach($selectedCotizacion->productos as $producto)
                      <tr>
                        <td>
                          {{ $producto->tipo_codigo }}
                        </td>
                        <td>
                          {{ $producto->codigo }}
                        </td>
                        <td>
                          {{ $producto->nombre }}
                          @if($producto->descripcion)
                            <p class="m-0"><small>{{ $producto->descripcion }}</small></p>
                          @endif
                        </td>
                        <td class="text-center">
                          {{ $producto->cantidad() }}
                        </td>
                        <td class="text-right">
                          {{ $producto->precio() }}
                        </td>
                        <td class="text-right">
                          {{ $producto->impuesto() }}
                        </td>
                        <td class="text-right">
                          {{ $producto->total() }}
                        </td>
                      </tr>
                    @endforeach
                  @else
                  <tr>
                    <td class="text-center text-muted" colspan="7">No hay productos o no se ha seleccionado una cotización.</td>
                  </tr>
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <th class="text-right" colspan="6">TOTAL</th>
                    <td id="total-general" class="text-right">
                      @if($selectedCotizacion)
                        {{ $selectedCotizacion->total() }}
                      @endif
                    </td>
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
              <a class="btn btn-default btn-sm" href="{{ $selectedCotizacion ? route('admin.cotizacion.show', ['cotizacion' => $selectedCotizacion->id]) : route('admin.cotizacion.facturacion.index') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"{{ Auth::user()->empresa->configuracion->isIntegrationIncomplete('sii') ? ' disabled' : '' }}><i class="fa fa-send"></i> Guardar</button>
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
    let URL = '{{ route("admin.cotizacion.productos", ["cotizacion" => ":id"]) }}';
    const TBODY_PRODUCTOS = $('#tbody-productos');

    $(document).ready(function () {
      $('#cotizacion').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#cotizacion').change(loadData);
      $('#cotizacion').change();
    });

    function loadData(){
      let cotizacion = $(this).val();

      if(!cotizacion){
        $('#rut, #digito_validador').val('');
        return false;
      }

      let option = $(this).find(`option[value="${cotizacion}"]`);
      let rut = option.data('rut');
      let dv = option.data('dv');

      $('#rut').val(rut);
      $('#digito_validador').val(dv);

      getProductos(cotizacion);
    }

    function getProductos(cotizacion){
      let action = URL.replace(':id', cotizacion);

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

    // Informacion del Producto
    let templateProducto = function(data) {
      return `<tr class="tr-producto" data-total="${data.total}">
                <td>
                  ${data.tipo_codigo}
                </td>
                <td>
                  ${data.codigo}
                </td>
                <td>
                  ${data.nombre}
                  ${data.descripcion ? ('<p class="m-0"><small>'+data.descripcion+'</small></p>') : ''}
                </td>
                <td class="text-center">
                  ${data.cantidad}
                </td>
                <td class="text-right">
                  ${formatNumbers(data.precio)}
                </td>
                <td class="text-right">
                  ${formatNumbers(data.impuesto_adicional)}
                </td>
                <td class="text-right">
                  ${formatNumbers(data.total)}
                </td>
              </tr>`;
    }
  </script>
@endsection
