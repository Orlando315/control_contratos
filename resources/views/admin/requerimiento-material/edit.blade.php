@extends('layouts.app')

@section('title', 'Requerimiento de Materiales')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Requerimiento de Materiales</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Solicitudes</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.requerimiento.material.index') }}">Requerimiento de Materiales</a></li>
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
          <h5>Editar Requerimiento de Materiales</h5>
        </div>
        <div class="ibox-content">
          <fieldset>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ old('contrato', $requerimiento->contrato_id) == $contrato->id ? ' selected' : '' }}>
                        {{ $contrato->nombre }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('faena') ? ' has-error' : '' }}">
                  <label for="faena">Faena:</label>
                  <select id="faena" class="form-control" name="faena">
                    <option value="">Seleccione...</option>
                    @foreach($faenas as $faena)
                      <option value="{{ $faena->id }}"{{ old('faena', $requerimiento->faena_id) == $faena->id ? ' selected' : '' }}>
                        {{ $faena->nombre }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('centro_costo') ? ' has-error' : '' }}">
                  <label for="centro_costo">Centro de costo:</label>
                  <select id="centro_costo" class="form-control" name="centro_costo">
                    <option value="">Seleccione...</option>
                    @foreach($centrosCostos as $centroCosto)
                      <option value="{{ $centroCosto->id }}"{{ old('centro_costo', $requerimiento->centro_costo_id) == $centroCosto->id ? ' selected' : '' }}>
                        {{ $centroCosto->nombre }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('dirigido') ? ' has-error' : '' }}">
                  <label for="dirigido">Dirigido a: *</label>
                  <select id="dirigido" class="form-control" name="dirigido" required>
                    <option value="">Seleccione...</option>
                    @foreach($usuarios as $usuario)
                      <option value="{{ $usuario->id }}"{{ old('dirigido', $requerimiento->dirigido) == $usuario->id ? ' selected' : '' }}>
                        {{ $usuario->rut }} | {{ $usuario->nombre() }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </fieldset>

          <form id="add-product-form" action="#" method="POST">
            <fieldset>
              <legend class="form-legend">Productos</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="inventario">Inventario:</label>
                    <select id="inventario" class="form-control">
                      <option value="">Seleccione...</option>
                      @foreach($inventariosV2 as $inventario)
                        <option value="{{ $inventario->id }}">{{ $inventario->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="producto-nombre">Nombre: *</label>
                    <input id="producto-nombre" class="form-control" type="text" maxlength="100" placeholder="Nombre del producto" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="cantidad">Cantidad: *</label>
                    <input id="cantidad" class="form-control" type="number" min="1" max="99999" placeholder="Cantidad" required>
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

          <form action="{{ route('admin.requerimiento.material.update', ['requerimiento' => $requerimiento->id]) }}" method="POST">
            @method('PATCH')
            @csrf
            <input id="form-contrato" type="hidden" name="contrato" value="{{ old('contrato', $requerimiento->contrato_id) }}">
            <input id="form-faena" type="hidden" name="faena" value="{{ old('faena', $requerimiento->faena_id) }}">
            <input id="form-centro_costo" type="hidden" name="centro_costo" value="{{ old('centro_costo', $requerimiento->centro_costo_id) }}">
            <input id="form-dirigido" type="hidden" name="dirigido" value="{{ old('dirigido', $requerimiento->dirigido) }}">

            <table class="table table-bordered">
              <colgroup>
                <col span="1" style="width: 5%;">
                <col span="1" style="width: 80%;">
                <col span="1" style="width: 15%;">
              </colgroup>
              <thead>
                <tr class="text-center">
                  <th class="align-middle">-</th>
                  <th class="align-middle">Nombre</th>
                  <th class="align-middle">Cantidad</th>
                </tr>
              </thead>
              <tbody>
                @foreach($requerimiento->productos as $producto)
                  <tr class="tr-producto">
                    <td class="text-center align-middle"></td>
                    <td>{{ $producto->nombre }}</td>
                    <td class="text-center">{{ $producto->cantidad() }}</td>
                  </tr>
                @endforeach
              </tbody>
              <tbody id="tbody-productos" class="is-empty">
                @forelse(old('productos', []) as $index => $producto)
                  <tr id="tr-${index}" class="tr-producto">
                    <td class="text-center align-middle">
                      <button class="btn btn-danger btn-xs btn-delete m-0" type="button" role="button" data-id="{{ $index }}"><i class="fa fa-trash"></i></button>
                    </td>
                    <td>
                      {{ $producto['nombre'] }}
                      <input type="hidden" name="productos[{{ $index }}][inventario]" value="{{ $producto['inventario'] }}">
                      <input type="hidden" name="productos[{{ $index }}][nombre]" value="{{ $producto['nombre'] }}">
                    </td>
                    <td class="text-right">
                      {{ $producto['cantidad'] }}
                      <input type="hidden" name="productos[{{ $index }}][cantidad]" value="{{ $producto['cantidad'] }}">
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center text-muted" colspan="3">No se han agregado productos.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

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
    const TBODY_PRODUCTOS = $('#tbody-productos');
    const BTN_ADD_PRODUCT = $('#btn-add-product');

    $(document).ready(function () {
      $('#dirigido, #contrato, #faena, #centro_costo, #inventario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      $('#contrato, #faena, #centro_costo, #dirigido').change(function () {
        let field = $(this).attr('id');
        let value = $(this).val();

        $(`#form-${field}`).val(value);
      });

      $('#inventario').change(selectInventario);
      $('#inventario').change();

      $('#add-product-form').submit(addProduct);
      TBODY_PRODUCTOS.on('click', '.btn-delete', deleteProduct);
    });

    function selectInventario(){
      let id = $(this).val();
      let option = $(this).find(`option[value="${id}"]`);
      let hasValue = id != '';
      let nombre = hasValue ? option.text() : '';

      $('#producto-nombre').prop('readonly', hasValue).val(nombre);
      $('#cantidad').val('');
    }

    function addProduct(e){
      e.preventDefault();

      BTN_ADD_PRODUCT.prop('disabled', true);

      let cantidad = +$('#cantidad').val();

      let data = {
        inventario: $('#inventario').val(),
        nombre: $('#producto-nombre').val(),
        cantidad: cantidad,
      };
      let index = Date.now();

      if(TBODY_PRODUCTOS.hasClass('is-empty')){
        TBODY_PRODUCTOS.removeClass('is-empty').empty();
      }

      TBODY_PRODUCTOS.append(producto(index, data));
      BTN_ADD_PRODUCT.prop('disabled', false);
      $(this)[0].reset();
      $('#inventario').val(null).trigger('change');
    }

    function deleteProduct(){
      let index = $(this).data('id');
      $(`#tr-${index}`).remove();

      let productos = $('.tr-producto').length;

      if(productos == 0){
        TBODY_PRODUCTOS
          .addClass('is-empty')
          .append('<tr class="tr-empty"><td class="text-center text-muted" colspan="3">No se han agregado productos.</td></tr>');
      }
    }

    // Informacion del Producto
    let producto = function(index, data) {
      return `<tr id="tr-${index}" class="tr-producto">
                <td class="text-center align-middle">
                  <button class="btn btn-danger btn-xs btn-delete m-0" type="button" role="button" data-id="${index}"><i class="fa fa-trash"></i></button>
                </td>
                <td>
                  ${data.nombre}
                  <input type="hidden" name="productos[${index}][inventario]" value="${data.inventario}">
                  <input type="hidden" name="productos[${index}][nombre]" value="${data.nombre}">
                </td>
                <td class="text-center">
                  ${data.cantidad}
                  <input type="hidden" name="productos[${index}][cantidad]" value="${data.cantidad}">
                </td>
              </tr>`;
    }
  </script>
@endsection

