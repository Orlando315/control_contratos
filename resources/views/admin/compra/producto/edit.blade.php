@extends('layouts.app')

@section('title', 'Ordenes de compra')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  <style type="text/css">
    .switch .onoffswitch-inner:before{
      content: 'Total';
    }
    .switch .onoffswitch-inner:after{
      content: 'Neto';
    }
  </style>
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ordenes de compra</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.compra.index') }}">Ordenes de compra</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.compra.show', ['compra' => $producto->orden_compra_id]) }}">Productos</a></li>
        <li class="breadcrumb-item active"><strong>Generar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar Producto</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <form action="{{ route('admin.compra.producto.update', ['producto' => $producto->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <fieldset class="border-bottom mb-3" data-index="{{ $producto->id }}">
              <div class="row align-items-end">
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="inventario">Inventario:</label>
                    <select id="inventario" class="form-control" name="inventario">
                      <option value="">Seleccione...</option>
                      @foreach($inventarios as $inventario)
                        <option value="{{ $inventario->id }}"{{ old('inventario', $producto->inventario_id) == $inventario->id ? ' selected' : '' }}>{{ $inventario->nombre }} ({{ $inventario->unidad->nombre }})</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="custom-control custom-checkbox">
                      <input id="check-codigos" class="custom-control-input" type="checkbox" name="requiere_codigo" value="1"{{ old('requiere_codigo', ($producto->tipo_codigo || $producto->codigo) ? '1' : '') == '1' ? ' checked' : '' }}>
                      <label class="custom-control-label" for="check-codigos">
                        Requiere código
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="afecto-iva">Afecto a IVA:</label>
                    <div class="custom-control custom-checkbox">
                      <input id="afecto-iva" class="custom-control-input" type="checkbox" name="afecto_iva" value="1"{{ old('afecto_iva', $producto->afecto_iva) == '1' ? ' checked' : '' }}>
                      <label class="custom-control-label" for="afecto-iva">Sí</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="tipo-precio">Tipo de precio:</label>
                    <div class="switch mb-3">
                      <div class="onoffswitch">
                        <input id="tipo-precio" class="onoffswitch-checkbox" type="checkbox" name="tipo_precio" value="1"{{ old('tipo_precio', '0') == '1' ? ' checked' : '' }}>
                        <label class="onoffswitch-label" for="tipo-precio">
                          <span class="onoffswitch-inner"></span>
                          <span class="onoffswitch-switch"></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 fields-codigos" style="display: none">
                  <div class="form-group">
                    <label for="tipo_codigo">Tipo de código:</label>
                    <input id="tipo_codigo" class="form-control" type="text" name="tipo_codigo" value="{{ old('tipo_codigo'. $producto->tipo_codigo, $producto->tipo_codigo) }}" maxlength="20" placeholder="Tipo de código">
                  </div>
                </div>
                <div class="col-md-3 fields-codigos" style="display: none">
                  <div class="form-group">
                    <label for="codigo">Código:</label>
                    <input id="codigo" class="form-control" type="text" maxlength="50" name="codigo" value="{{ old('codigo', $producto->codigo) }}" placeholder="Código">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="producto-nombre">Nombre:</label>
                    <input id="producto-nombre" class="form-control" type="text" maxlength="100" name="nombre" value="{{ old('nombre', $producto->nombre) }}" placeholder="Nombre del producto" readonly>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="cantidad">Cantidad: *</label>
                    <input id="cantidad" class="form-control" type="number" min="1" max="99999" name="cantidad" value="{{ old('cantidad', $producto->cantidad) }}" placeholder="Cantidad" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="precio">Precio: *</label>
                    <input id="precio" class="form-control" type="number" min="1" max="99999999" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" placeholder="Precio" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Precio Total:</label>
                    <input id="precio_total" class="form-control-plaintext" type="text" placeholder="Precio Total" readonly>
                    <input id="hidden-precio_total" type="hidden" name="precio_total">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>IVA:</label>
                    <input id="iva" class="form-control-plaintext" type="text" name="iva" placeholder="IVA" readonly>
                    <input id="hidden-iva" type="hidden" name="iv">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Total:</label>
                    <input id="total" class="form-control-plaintext" type="text" name="total" placeholder="TOTAL" readonly>
                    <input id="hidden-total" type="hidden" name="total">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input id="descripcion" class="form-control" type="text" maxlength="200" name="descripcion" value="{{ old('descripcion', $producto->descripcion) }}" placeholder="Descripción">
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="alert alert-danger alert-important alert-"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.compra.show', ['compra' => $producto->orden_compra_id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
  <script type="text/javascript">
    const IBOX = $('.ibox-content');

    $(document).ready(function () {
      $('#inventario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#inventario').change(selectInventario);

      $('#check-codigos').change(function () {
        let isChecked = $(this).is(':checked');

        $(`.fields-codigos`).toggle(isChecked);
      });
      $('#check-codigos').change();

      $('#tipo-precio, #afecto-iva, #cantidad, #precio').change(calculatePrecio);
      $('#cantidad, #precio').keyup(calculatePrecio);
      $('#tipo-precio').change();

      calculatePrecio();
    });

    function selectInventario(){
      let id = $(this).val();
      let option = $(this).find(`option[value="${id}"]`);
      let hasValue = id != '';
      let nombre = hasValue ? option.text() : '';

      $('#producto-nombre').prop('readonly', hasValue).val(nombre);
      $('#tipo_codigo, #codigo, #cantidad, #precio, #impuesto, #iva, #total').val('');
    }

    function calculatePrecio(){
      let precio = +$('#precio').val();
      let cantidad = +$('#cantidad').val();
      let hasIva = $('#afecto-iva').is(':checked') ? 1 : 0;
      let tipoPrecio = $('#tipo-precio').is(':checked');
      let totalPrecio = precio * cantidad;
      totalPrecio = +(tipoPrecio ? (totalPrecio / 1.19) : totalPrecio);

      let totalIva = +(hasIva ? calculateIva(totalPrecio) : 0);
      let total = +(totalPrecio + totalIva);

      $('#iva').val(formatNumbers(totalIva));
      $('#precio_total').val(formatNumbers(totalPrecio));
      $('#total').val(formatNumbers(total));

      $('#hidden-iva').val(totalIva);
      $('#hidden-precio_total').val(totalPrecio);
      $('#hidden-total').val(total);
    }

    function calculateIva(precio, tipoPrecio){
      return (precio * 19) / 100;
    }

    function formatNumbers(number){
      return (+number).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits : 2});
    }
  </script>
@endsection
