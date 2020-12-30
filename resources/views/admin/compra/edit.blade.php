@extends('layouts.app')

@section('title', 'Ordenes de compra')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ordenes de compra</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.compra.index') }}">Ordenes de compra</a></li>
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
          <h5>Editar Orden de compra</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group{{ $errors->has('proveedor') ? ' has-error' : '' }}">
                <label for="proveedor">Proveedor: *</label>
                <select id="proveedor" class="form-control">
                  <option value="">Seleccione...</option>
                  @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}"{{ old('proveedor', $compra->proveedor_id) == $proveedor->id ? ' selected' : '' }}
                      data-type="{{ $proveedor->type }}"
                      data-nombre="{{ $proveedor->nombre }}"
                      data-telefono="{{ $proveedor->telefono }}"
                      data-email="{{ $proveedor->email }}"
                    >
                      {{ $proveedor->rut }} | {{ $proveedor->nombre }}
                    </option>
                  @endforeach
                </select>

                @permission('proveedor-create')
                  <button class="btn btn-simple btn-link btn-sm" type="button" data-toggle="modal" data-target="#optionModal" data-option="tipo"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Proveedor</button>
                @endpermission
              </div>
            </div>
          </div>

          <fieldset>
            <legend class="form-legend">Información de contacto</legend>
            <div id="box-contactos" class="row">
              <div class="col-12">
                <h5 class="text-center text-muted">No hay contactos agregados.</h5>
              </div>
            </div>

            <fieldset id="fields-contacto" disabled style="display: none">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label for="nombre">Nombre: *</label>
                    <input id="nombre" class="form-control" type="text" maxlength="100" value="{{ old('nombre', optional($compra->contacto)->nombre) }}" placeholder="Nombre">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                    <label for="telefono">Teléfono: *</label>
                    <input id="telefono" class="form-control" type="text" maxlength="20" value="{{ old('telefono', optional($compra->contacto)->telefono) }}" placeholder="Teléfono">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email:</label>
                    <input id="email" class="form-control" type="email" maxlength="50" value="{{ old('email', optional($compra->contacto)->email) }}" placeholder="Email">
                  </div>
                </div>
              </div>
            </fieldset>

            @permission('proveedor-edit')
              <button class="btn btn-simple btn-link btn-sm btn-contacto" type="button" data-toggle="modal" data-target="#contactoModal" disabled><i class="fa fa-plus" aria-hidden="true"></i> Agregar Contacto</button>
            @endpermission

            <div class="alert alert-danger"{!! $errors->has('contacto') ? '' : ' style=display:none;"' !!}>
              <ul class="m-0 box-errors-contactos">
                @error('contacto')
                  <li>{{ $message }}</li>
                @enderror
              </ul>
            </div>
          </fieldset>

          <fieldset>
            <legend class="form-legend">Notas adicionales</legend>

            <div class="form-group{{ $errors->has('notas') ? ' has-error' : '' }}">
              <label for="notas">Notas:</label>
              <textarea id="notas" class="form-control" cols="30" rows="4" maxlength="350">{{ old('notas', $compra->notas) }}</textarea>
              <small class="form-text text-muted">Caracteres disponibles: <span id="notas-count">350</span>/350</small>
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
                    <label for="tipo_codigo">Tipo código:</label>
                    <input id="tipo_codigo" class="form-control" type="text" maxlength="20" placeholder="Tipo código">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="codigo">Código:</label>
                    <input id="codigo" class="form-control" type="text" maxlength="50" placeholder="Código">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
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

          <form action="{{ route('admin.compra.update', ['compra' => $compra->id]) }}" method="POST">
            @method('PATCH')
            @csrf
            <input id="form-proveedor" type="hidden" name="proveedor" value="{{ old('proveedor', $compra->id) }}">
            <input id="form-contacto" type="hidden" name="contacto" value="{{ old('contacto', optional($compra->contacto)->id) }}">
            <input id="form-notas" type="hidden" name="notas" value="{{ old('notas', $compra->notas) }}">
            <input id="form-nombre" type="hidden" name="nombre" value="{{ old('nombre', optional($compra->contacto)->nombre) }}">
            <input id="form-telefono" type="hidden" name="telefono" value="{{ old('telefono', optional($compra->contacto)->telefono) }}">
            <input id="form-email" type="hidden" name="email" value="{{ old('email', optional($compra->contacto)->email) }}">

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
              <tbody id="tbody-productos" class="{{ $compra->productos->count() > 0 ? '' : ' is-empty' }}">
                @foreach($compra->productos as $producto)
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

            <div class="alert alert-danger alert-important alert-"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.compra.show', ['compra' => $compra->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @permission('proveedor-edit')
    <div id="contactoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="contactoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="add-form-contacto" action="#" method="POST">
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="contactoModalLabel">Agregar Contacto</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contacto-nombre">Nombre: *</label>
                    <input id="contacto-nombre" class="form-control" type="text" name="nombre" maxlength="50" placeholder="Nombre" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contacto-telefono">Teléfono: *</label>
                    <input id="contacto-telefono" class="form-control" type="telefono" name="telefono" maxlength="20" placeholder="Teléfono" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contacto-email">Email:</label>
                    <input id="contacto-email" class="form-control" type="email" name="email" maxlength="50" placeholder="Email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contacto-cargo">Cargo:</label>
                    <input id="contacto-cargo" class="form-control" type="text" name="cargo" maxlength="50" placeholder="Cargo">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="contacto-descripcion">Descripción:</label>
                <input id="contacto-descripcion" class="form-control" type="text" name="descripcion" maxlength="200" placeholder="Descripción">
              </div>

              <div class="alert alert-danger" style="display: none">
                <ul class="m-0 form-errors-contactos">
                </ul>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm btn-add-contacto" type="submit" disabled>Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission

  @permission('proveedor-create')
    <div id="optionModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <form id="add-form-proveedor" action="#" method="POST">
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="optionModalLabel">Agregar Proveedor</h4>
            </div>
            <div class="modal-body">

              <div class="row justify-content-center">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="proveedor-type">Tipo:</label>
                    <select id="proveedor-type" class="custom-select">
                      <option value="persona">Persona</option>
                      <option value="empresa">Empresa</option>
                    </select>
                  </div>
                </div>
              </div>

              <fieldset id="proveedor-type-persona">
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

              <fieldset id="proveedor-type-empresa" disabled>
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
              </fieldset><!-- #proveedor-persona -->

              <div class="form-group">
                <label>Cliente:</label>
                <div class="custom-control custom-checkbox">
                  <input id="cliente" class="custom-control-input" type="checkbox" name="cliente" value="1">
                  <label class="custom-control-label" for="cliente">
                    Es cliente
                  </label>
                </div>
                <small class="form-text text-muted">Se creará un registro de Cliente usando la misma información</small>
              </div>

              <div class="alert alert-danger" style="display: none">
                <ul class="m-0 form-errors-proveedor">
                </ul>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm btn-add-proveedor" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    const IBOX = $('.ibox-content');
    const MAX_COUNT = 350;
    const TBODY_PRODUCTOS = $('#tbody-productos');
    const BTN_ADD_PRODUCT = $('#btn-add-product');

    const BOX_CONTACTOS = $('#box-contactos');
    const FIELDS_CONTACTO = $('#fields-contacto');
    const BTN_ADD_CONTACTO = $('.btn-add-contacto');

    const BTN_ADD_PROVEEDOR = $('.btn-add-proveedor');
    const BTN_CONSULTAR_EMPRESA = $('.btn-consultar');
    const INTEGRATION_COMPLETE = @json(Auth::user()->empresa->configuracion->isIntegrationComplete('sii'));

    $(document).ready(function () {
      $('#proveedor').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#notas').keyup(countCharacters);
      $('#notas').keyup();

      $('#inventario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      $('#proveedor').change(loadProveedor);
      $('#proveedor').change();

      $('#nombre, #telefono, #email').change(function () {
        let field = $(this).attr('id');
        let value = $(this).val();

        $(`#form-${field}`).val(value);
      });

      $('#inventario').change(selectInventario);
      $('#inventario').change();

      $('#add-product-form').submit(addProduct);
      TBODY_PRODUCTOS.on('click', '.btn-delete', deleteProduct);

      $('#add-form-proveedor').submit(addProveedor);
      $('#proveedor-type').change(selectProveedorType);
      $('#proveedor-type').change();
      BTN_CONSULTAR_EMPRESA.click(consultarProveedorEmpresa);

      $('#add-form-contacto').submit(addContacto);
      $('#contactoModal').on('show.bs.modal', function (e) {
        let proveedor = $(e.relatedTarget).data('proveedor');
        let action = '{{ route("admin.contacto.store", ["id" => ":id", "type" => "proveedor"]) }}'.replace(':id', proveedor);

        $('#add-form-contacto').attr('action', action);
      });
      BOX_CONTACTOS.on('change', 'input[name="radio_contacto"]', function () {
        let checked = $('input[name="radio_contacto"]:checked').val();
        let val = $(this).val();

        $('#form-contacto').val($(this).val());
      });

      calculateTotal();
    });

    function loadProveedor(){
      let proveedor = $(this).val();
      let option = $(this).find(`option[value="${proveedor}"]`);
      let type = option.data('type');

      $('#form-proveedor').val(proveedor);

      $('.btn-contacto').data('proveedor', proveedor).prop('disabled', !proveedor);
      $('.btn-contacto').toggle(type == 'empresa');
      FIELDS_CONTACTO.toggle(type == 'persona').prop('disabled', !(type == 'persona'));
      BOX_CONTACTOS.toggle(type == 'empresa');
      BTN_ADD_CONTACTO.prop('disabled', type == 'persona');

      if(!proveedor){
        return;
      }

      if(type == 'empresa'){
        loadProveedorContactos(proveedor); 
        $('#nombre, #telefono, #email').val('');

      }else{
        BOX_CONTACTOS.empty();
        $('#nombre, #form-nombre').val(option.data('nombre'));
        $('#telefono, #form-telefono').val(option.data('telefono'));
        $('#email, #form-email').val(option.data('email'));
      }
    }

    function addContacto(e){
      e.preventDefault();

      BTN_ADD_CONTACTO.prop('disabled', true);

      let form = $(this);
      let action = form.attr('action');

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function (response) {
        if(response.response){
          BOX_CONTACTOS.append(templateContacto(BOX_CONTACTOS.children().length, response.contacto));
          form[0].reset();
          $('#contactoModal').modal('hide');
        }else{
          showErrors(['Ha ocurrido un error.'], '.form-errors-contactos');
        }
      })
      .fail(function (response) {
        showErrors(response.responseJSON.errors, '.form-errors-contactos');
      })
      .always(function () {
        BTN_ADD_CONTACTO.prop('disabled', false);
      });
    }

    function loadProveedorContactos(proveedor){
      let action = '{{ route("admin.proveedor.contactos", ["proveedor" => ":id"]) }}'.replace(':id', proveedor);

      IBOX.toggleClass('sk-loading', true);
      
      $.ajax({
        type: 'GET',
        url: action,
        dataType: 'json',
      })
      .done(function (response) {
        BOX_CONTACTOS.empty();

        if(response.contactos.length > 0){
          $.each(response.contactos, function (k, contacto) {
            BOX_CONTACTOS.append(templateContacto(k, contacto));
          });

          $('input[name="radio_contacto"]:checked').change();
        }else{
          BOX_CONTACTOS.append('<div class="col-md-12"><h5 class="text-center text-muted">No hay contactos agregados.</h5></div>');
        }
      })
      .fail(function (response) {
        showErrors(['Ha ocurrido un error al consultar los contactos'], '.box-errors-contactos');
      })
      .always(function () {
        IBOX.toggleClass('sk-loading', false);
      });
    }

    let templateContacto = function (index, contacto) {
      let checked = @json(old('contacto', optional($compra->contacto)->id)) == contacto.id;

      return `<div class="col-md-3">
                <label for="contacto-${index}" class="border w-100 hover-pointer">
                  <div class="p-3">
                    <p>
                      <strong>${contacto.nombre}</strong>
                      ${contacto.cargo ? ('</br><small class="text-muted">('+contacto.cargo+')</small>') : ''}
                    </p>
                    <p class="m-0"><i class="fa fa-phone" aria-hidden="true"></i> ${contacto.telefono}</p>
                    ${contacto.email ? ('<p class="m-0"><i class="fa fa-envelope" aria-hidden="true"></i> '+contacto.email+'</p>') : ''}
                    ${contacto.descripcion ? ('<p class="m-0">'+contacto.descripcion+'</p>') : ''}
                  </div>
                  <div class="border-top text-center">
                    <div class="custom-control custom-radio">
                      <input id="contacto-${index}" class="custom-control-input" type="radio" name="radio_contacto" value="${contacto.id}"${checked ? ' checked' : ''}>
                      <label class="custom-control-label" for="contacto-${index}"></label>
                    </div>
                  </div>
                </label>
              </div>`;
    }

    function countCharacters(){
      let value = $(this).val();
      let result = MAX_COUNT - value.length;

      $('#notas-count').text(result).css('color', (result <= 50 ? 'var(--red)' : 'inherit'));
      $('#form-notas').val(value)
    }

    function selectInventario(){
      let id = $(this).val();
      let option = $(this).find(`option[value="${id}"]`);
      let hasValue = id != '';
      let nombre = hasValue ? option.text() : '';
      let precio = hasValue ? option.data('precio') : '';

      $('#producto-nombre').prop('readonly', hasValue).val(nombre);
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
        nombre: $('#producto-nombre').val(),
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

    function selectProveedorType(){
      let type = $(this).val();

      $('#proveedor-type-empresa, #proveedor-type-persona').prop('disabled', true).toggle(false);
      $(`#proveedor-type-${type}`).prop('disabled', false).toggle(true);

      BTN_ADD_PROVEEDOR.prop('disabled', type != 'persona');
    }

    function addProveedor(e){
      e.preventDefault();

      BTN_ADD_PROVEEDOR.prop('disabled', true);

      let form = $(this);
      let type = $('#proveedor-type').val();
      let action = type == 'persona'
        ? '{{ route("admin.proveedor.store", ["type" => "persona"]) }}'
        : '{{ route("admin.proveedor.store", ["type" => "empresa"]) }}';

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function (response) {
        if(response.response){
          let option = `
          <option value="${response.proveedor.id}"
            data-type="${response.proveedor.type}"
            data-nombre="${response.proveedor.nombre}"
            data-telefono="${response.proveedor.telefono}"
            data-email="${response.proveedor.email}"
          >
            ${response.proveedor.rut} | ${response.proveedor.nombre}
          </option>`;

          $('#proveedor').append(option);
          $('#proveedor').val(response.proveedor.id);
          $('#proveedor').trigger('change');
          form[0].reset();
          $('#proveedor-type').trigger('change');
          $('#optionModal').modal('hide');
        }else{
          showErrors(['Ha ocurrido un error.'], '.form-errors-proveedor');
        }
      })
      .fail(function (response) {
        showErrors(response.responseJSON.errors, '.form-errors-proveedor');
      })
      .always(function () {
        BTN_ADD_PROVEEDOR.prop('disabled', false);
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

    function consultarProveedorEmpresa(){
      BTN_CONSULTAR_EMPRESA.prop('disabled', true);

      if(!INTEGRATION_COMPLETE){
        showErrors(['Debe completar los datos de su integración con Facturación Sii.'], '.form-errors-proveedor');
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
        url: '{{ route("admin.proveedor.busqueda.sii") }}',
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

          BTN_ADD_PROVEEDOR.prop('disabled', false);
        }else{
          BTN_ADD_PROVEEDOR.prop('disabled', true);
          showErrors([response.data], '.form-errors-proveedor');
        }
      })
      .fail(function (data) {
        showErrors(['Ha ocurrido un error al consultar la información.'], '.form-errors-proveedor');
        BTN_ADD_PROVEEDOR.prop('disabled', true);
      })
      .always(function () {
        BTN_CONSULTAR_EMPRESA.prop('disabled', false);
      });
    }
  </script>
@endsection

