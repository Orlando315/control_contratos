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
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar cotización</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group{{ $errors->has('cliente') ? ' has-error' : '' }}">
                <label for="cliente">Cliente: *</label>
                <select id="cliente" class="form-control">
                  <option value="">Seleccione...</option>
                  @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}"{{ old('cliente', $cotizacion->cliente_id) == $cliente->id ? ' selected' : '' }}>{{ $cliente->nombre }}</option>
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
                    <input id="tipo_codigo" class="form-control" type="text" maxlength="20" placeholder="Tipo código" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="codigo">Código: *</label>
                    <input id="codigo" class="form-control" type="text" maxlength="50" placeholder="Código" required>
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

          <form action="{{ route('admin.cotizacion.update', ['cotizacion' => $cotizacion->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <input id="cliente-form" type="hidden" name="cliente" value="{{ old('cliente', $cotizacion->id) }}">

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
              <tbody id="tbody-productos" class="{{ $cotizacion->productos->count() > 0 ? '' : ' is-empty' }}">
                @foreach($cotizacion->productos as $producto)
                  <tr class="tr-producto" data-total="{{ str_replace(',', '.', $producto->total) }}">
                    <td class="text-center align-middle"></td>
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

                @if(old('productos'))
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
                @endif
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.cotizacion.show', ['cotizacion' => $cotizacion->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
    const TBODY_PRODUCTOS = $('#tbody-productos');
    const BTN_ADD_PRODUCT = $('#btn-add-product');

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
  </script>
@endsection

