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
          <h5>Generar orden de compra</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <p class="text-center mb-0">Se generará una orden de compra por cada Proveedor seleccionado, agrupando los diferentes productos con sus respectivos Proveedores</p>
          <p class="text-center">Se utilizará la información por defecto de contacto y despacho de cada Proveedor.</p>

          <form action="{{ route('admin.compra.requerimiento', ['requerimiento' => $requerimiento]) }}" method="POST">
            @csrf

            @foreach($requerimiento->productos as $producto)
              <fieldset class="border-bottom mb-3" data-index="{{ $producto->id }}">
                <input type="hidden" name="productos[{{ $producto->id }}][inventario_id]" value="{{ $producto->inventario_id }}">
                <input type="hidden" name="productos[{{ $producto->id }}][producto_id]" value="{{ $producto->id }}">

                <div class="row align-items-end">
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('productos.'.$producto->id.'.proveedor') ? ' has-error' : '' }}">
                      <label for="proveedor-{{ $producto->id }}">Proveedor: *</label>
                      <select id="proveedor-{{ $producto->id }}" class="form-control" name="productos[{{ $producto->id }}][proveedor]">
                        <option value="">Seleccione...</option>
                        @foreach($proveedores as $proveedor)
                          <option value="{{ $proveedor->id }}"{{ old('productos.'.$producto->id.'.proveedor') == $proveedor->id ? ' selected' : '' }}>
                            {{ $proveedor->rut }} | {{ $proveedor->nombre }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input id="check-codigos-{{ $producto->id }}" class="custom-control-input" type="checkbox" name="productos[{{ $producto->id }}][requiere_codigo]" value="1"{{ old('productos.'.$producto->id.'.requiere_codigo', '0') == '1' ? ' checked' : '' }}>
                        <label class="custom-control-label" for="check-codigos-{{ $producto->id }}">
                          Requiere código
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="afecto-iva-{{ $producto->id }}">Afecto a IVA:</label>
                      <div class="custom-control custom-checkbox">
                        <input id="afecto-iva-{{ $producto->id }}" class="custom-control-input" type="checkbox" name="productos[{{ $producto->id }}][afecto_iva]" value="1"{{ old('productos.'.$producto->id.'.afecto_iva') == '1' ? ' checked' : '' }}>
                        <label class="custom-control-label" for="afecto-iva-{{ $producto->id }}">Sí</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="tipo-precio-{{ $producto->id }}">Tipo de precio:</label>
                      <div class="switch mb-3">
                        <div class="onoffswitch">
                          <input id="tipo-precio-{{ $producto->id }}" class="onoffswitch-checkbox" type="checkbox" name="productos[{{ $producto->id }}][tipo_precio]" value="1"{{ old('productos.'.$producto->id.'.tipo_precio') == '1' ? ' checked' : '' }}>
                          <label class="onoffswitch-label" for="tipo-precio-{{ $producto->id }}">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 fields-codigos-{{ $producto->id }}" style="display: none">
                    <div class="form-group">
                      <label for="tipo_codigo-{{ $producto->id }}">Tipo de código:</label>
                      <input id="tipo_codigo-{{ $producto->id }}" class="form-control" type="text" name="productos[{{ $producto->id }}][tipo_codigo]" value="{{ old('productos.'.$producto->id.'.tipo_codigo') }}" maxlength="20" placeholder="Tipo de código">
                    </div>
                  </div>
                  <div class="col-md-3 fields-codigos-{{ $producto->id }}" style="display: none">
                    <div class="form-group">
                      <label for="codigo-{{ $producto->id }}">Código:</label>
                      <input id="codigo-{{ $producto->id }}" class="form-control" type="text" maxlength="50" name="productos[{{ $producto->id }}][codigo]" value="{{ old('productos.'.$producto->id.'.codigo') }}" placeholder="Código">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="producto-nombre-{{ $producto->id }}">Nombre:</label>
                      <input id="producto-nombre-{{ $producto->id }}" class="form-control" type="text" maxlength="100" name="productos[{{ $producto->id }}][nombre]" value="{{ $producto->nombre }}" placeholder="Nombre del producto" readonly>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="cantidad-{{ $producto->id }}">Cantidad: *</label>
                      <input id="cantidad-{{ $producto->id }}" class="form-control" type="number" min="1" max="99999" name="productos[{{ $producto->id }}][cantidad]" value="{{ old('productos.'.$producto->id.'.cantidad', $producto->cantidad) }}" placeholder="Cantidad" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="precio-{{ $producto->id }}">Precio: *</label>
                      <input id="precio-{{ $producto->id }}" class="form-control" type="number" min="1" max="99999999" step="0.01" name="productos[{{ $producto->id }}][precio]" value="{{ old('productos.'.$producto->id.'.precio') }}" placeholder="Precio" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Precio Total:</label>
                      <input id="precio_total-{{ $producto->id }}" class="form-control-plaintext" type="text" placeholder="Precio Total" readonly>
                      <input id="hidden-precio_total-{{ $producto->id }}" type="hidden" name="productos[{{ $producto->id }}][precio_total]">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>IVA:</label>
                      <input id="iva-{{ $producto->id }}" class="form-control-plaintext" type="text" name="productos[{{ $producto->id }}][iva]" placeholder="IVA" readonly>
                      <input id="hidden-iva-{{ $producto->id }}" type="hidden" name="productos[{{ $producto->id }}][iva]">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Total:</label>
                      <input id="total-{{ $producto->id }}" class="form-control-plaintext" type="text" name="productos[{{ $producto->id }}][total]" placeholder="TOTAL" readonly>
                      <input id="hidden-total-{{ $producto->id }}" type="hidden" name="productos[{{ $producto->id }}][total]">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="descripcion-{{ $producto->id }}">Descripción:</label>
                      <input id="descripcion-{{ $producto->id }}" class="form-control" type="text" maxlength="200" name="productos[{{ $producto->id }}][descripcion]" value="{{ old('productos.'.$producto->id.'.descripcion') }}" placeholder="Descripción">
                    </div>
                  </div>
                </div>
              </fieldset>
            @endforeach

            <div class="alert alert-danger alert-important alert-"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.requerimiento.material.show', ['requerimiento' => $requerimiento->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
      $('select[id^="proveedor-"]').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('input[id^="check-codigos-"]').change(function () {
        let isChecked = $(this).is(':checked');
        let index = $(this).closest('fieldset').data('index');

        $(`.fields-codigos-${index}`).toggle(isChecked);
      });
      $('input[id^="check-codigos-"]').change();

      $('input[id^="tipo-precio-"], input[id^="afecto-iva-"]').change(calculatePrecio);
      $('input[id^="cantidad-"], input[id^="precio-"]').keyup(calculatePrecio);
      $('input[id^="tipo-precio-"]').change();

      calculatePrecio();
    });

    function calculatePrecio(){
      let index = $(this).closest('fieldset').data('index');
      let precio = +$(`#precio-${index}`).val();
      let cantidad = +$(`#cantidad-${index}`).val();
      let hasIva = $(`#afecto-iva-${index}`).is(':checked') ? 1 : 0;
      let tipoPrecio = $(`#tipo-precio-${index}`).is(':checked');
      let totalPrecio = precio * cantidad;
      totalPrecio = +(tipoPrecio ? (totalPrecio / 1.19) : totalPrecio);

      let totalIva = +(hasIva ? calculateIva(totalPrecio) : 0);
      let total = +(totalPrecio + totalIva);

      $(`#iva-${index}`).val(formatNumbers(totalIva));
      $(`#precio_total-${index}`).val(formatNumbers(totalPrecio));
      $(`#total-${index}`).val(formatNumbers(total));

      $(`#hidden-iva-${index}`).val(totalIva);
      $(`#hidden-precio_total-${index}`).val(totalPrecio);
      $(`#hidden-total-${index}`).val(total);
    }

    function calculateIva(precio, tipoPrecio){
      return (precio * 19) / 100;
    }

    function formatNumbers(number){
      return (+number).toLocaleString('de-DE', {minimumFractionDigits: 2, maximumFractionDigits : 2});
    }
  </script>
@endsection
