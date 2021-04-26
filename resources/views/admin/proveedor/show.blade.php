@extends('layouts.app')

@section('title', 'Proveedor')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Proveedores</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.proveedor.index') }}">Proveedores</a></li>
        <li class="breadcrumb-item active"><strong>Proveedor</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      @permission('proveedor-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.proveedor.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('proveedor-edit')
        @if($proveedor->isPersona())
          <a class="btn btn-default btn-sm" href="{{ route('admin.proveedor.edit', ['proveedor' => $proveedor->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        @endif
      @endpermission
      @permission('proveedor-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>{{ $proveedor->isEmpresa() ? 'Razón social' : 'Nombre' }}</b>
              <span class="pull-right">{{ $proveedor->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">@nullablestring($proveedor->rut)</span>
            </li>
            @if($proveedor->isPersona())
              <li class="list-group-item">
                <b>Teléfono</b>
                <span class="pull-right">@nullablestring($proveedor->telefono)</span>
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <span class="pull-right">@nullablestring($proveedor->email)</span>
              </li>
              <li class="list-group-item">
                <b>Descripción</b>
                <span class="pull-right">@nullablestring($proveedor->descripcion)</span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{!! $proveedor->tipo() !!}</span>
            </li>
            @if($proveedor->hasClienteProfile())
              <li class="list-group-item">
                <b>Cliente</b>
                <span class="pull-right">
                  <a href="{{ route('admin.cliente.show', ['cliente' => $proveedor->cliente_id]) }}">
                    Ver perfil
                  </a>
                </span>
              </li>
            @endif
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $proveedor->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-3" data-toggle="tab"><i class="fa fa-cubes" aria-hidden="true"></i> Productos</a></li>
          <li><a class="nav-link" href="#tab-1" data-toggle="tab"><i class="fa fa-map-marker" aria-hidden="true"></i> Direcciones</a></li>
          @if($proveedor->isEmpresa())
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-address-book" aria-hidden="false"></i> Contactos</a></li>
          @endif
        </ul>
        <div class="tab-content">
          <div id="tab-3" class="tab-pane active">
            <div class="panel-body">
              <div class="mb-3 text-right">
                @permission('proveedor-edit')
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.proveedor.producto.create', ['proveedor' => $proveedor->id]) }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nuevo producto
                  </a>
                @endpermission
              </div>

              <table class="table data-table table-bordered table-hover w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Costo</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($proveedor->productos as $producto)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>{{ $producto->nombre }}</td>
                      <td class="text-right">{{ $producto->costo() }}</td>
                      <td class="text-center">
                        @permission('proveedor-edit')
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              <li>
                                <a class="dropdown-item" href="{{ route('admin.proveedor.producto.edit', ['producto' => $producto->id]) }}">
                                  <i class="fa fa-pencil"></i> Editar
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delDataModal" data-type="producto" data-url="{{ route('admin.proveedor.producto.destroy', ['producto' => $producto->id]) }}">
                                  <i class="fa fa-times"></i> Eliminar
                                </a>
                              </li>
                            </ul>
                          </div>
                        @endpermission
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
          <div id="tab-1" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3 text-right">
                @permission('proveedor-edit')
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.direccion.create', ['id' => $proveedor->id, 'type' => 'proveedor']) }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nueva dirección
                  </a>
                @endpermission
              </div>

              <table class="table data-table table-bordered table-hover w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Ciudad</th>
                    <th class="text-center">Comuna</th>
                    <th class="text-center">Dirección</th>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($proveedor->direcciones as $direccion)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>@nullablestring($direccion->ciudad)</td>
                      <td>@nullablestring($direccion->comuna)</td>
                      <td>@nullablestring($direccion->direccion)</td>
                      <td class="text-center">
                        <small>
                          {!! $direccion->status() !!}
                        </small>
                      </td>
                      <td class="text-center">
                        @permission('proveedor-edit')
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              @if(!$direccion->isSelected())
                                <li>
                                  <a class="dropdown-item" type="button" data-toggle="modal" data-target="#statusModal" data-url="{{ route('admin.direccion.status', ['direccion' => $direccion->id]) }}">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i> Seleccionar
                                  </a>
                                </li>
                              @endif
                              <li>
                                <a class="dropdown-item" href="{{ route('admin.direccion.edit', ['direccion' => $direccion->id]) }}">
                                  <i class="fa fa-pencil"></i> Editar
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delDataModal" data-type="direccion" data-url="{{ route('admin.direccion.destroy', ['direccion' => $direccion->id]) }}">
                                  <i class="fa fa-times"></i> Eliminar
                                </a>
                              </li>
                            </ul>
                          </div>
                        @endpermission
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3 text-right">
                @permission('proveedor-edit')
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.contacto.create', ['id' => $proveedor->id, 'type' => 'proveedor']) }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> Nuevo contacto
                  </a>
                @endpermission
              </div>

              <table class="table data-table table-bordered table-hover w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Teléfono</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Cargo</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($proveedor->contactos as $contacto)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>{{ $contacto->nombre }}</td>
                      <td>{{ $contacto->telefono }}</td>
                      <td>@nullablestring($contacto->email)</td>
                      <td>@nullablestring($contacto->cargo)</td>
                      <td>@nullablestring($contacto->descripcion)</td>
                      <td class="text-center">
                        <small>
                          {!! $contacto->status() !!}
                        </small>
                      </td>
                      <td class="text-center">
                        @permission('proveedor-edit')
                          <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                              @if(!$contacto->isSelected())
                                <li>
                                  <a class="dropdown-item" type="button" data-toggle="modal" data-target="#statusModal" data-type="contacto" data-url="{{ route('admin.contacto.status', ['contacto' => $contacto->id]) }}">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i> Seleccionar
                                  </a>
                                </li>
                              @endif
                              <li>
                                <a class="dropdown-item" href="{{ route('admin.contacto.edit', ['contacto' => $contacto->id]) }}">
                                  <i class="fa fa-pencil"></i> Editar
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delDataModal" data-type="contacto" data-url="{{ route('admin.contacto.destroy', ['contacto' => $contacto->id]) }}">
                                  <i class="fa fa-times"></i> Eliminar
                                </a>
                              </li>
                            </ul>
                          </div>
                        @endpermission
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div>
    </div>
  </div>

  @permission('compra-index|inventario-ingreso-index')
    <div class="row">
      <div class="col-md-12">
        <div class="tabs-container">
          <ul class="nav nav-tabs">
            <li><a class="nav-link active" href="#tab-11" data-toggle="tab"><i class="fa fa-plus-square" aria-hidden="true"></i> Ordenes de compra</a></li>
            @permission('inventario-ingreso-index')
              <li><a class="nav-link" href="#tab-12" data-toggle="tab"><i class="fa fa-level-down"></i> Ingresos (Inventarios V2)</a></li>
            @endpermission
            @permission('factura-index')
              <li><a class="nav-link" href="#tab-13" data-toggle="tab"><i class="fa fa-clipboard"></i> Facturas</a></li>
            @endpermission
          </ul>
          <div class="tab-content">
            <div id="tab-11" class="tab-pane active">
              <div class="panel-body">
                <div class="mb-3 text-right">
                  @permission('compra-create')
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.compra.create', ['proveedor' => $proveedor->id]) }}">
                      <i class="fa fa-plus" aria-hidden="true"></i> Nueva orden de compra
                    </a>
                  @endpermission
                </div>

                <table class="table data-table table-bordered table-hover w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Código</th>
                      <th class="text-center">Total</th>
                      <th class="text-center">Creado</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($proveedor->compras as $compra)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $compra->codigo() }}</td>
                        <td class="text-right">{{ $compra->total() }}</td>
                        <td class="text-center">{{ $compra->created_at->format('d-m-Y H:i:s') }}</td>
                        <td class="text-center">
                          @permission('compra-view|compra-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('compra-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.compra.show', ['compra' => $compra->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('compra-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.compra.edit', ['compra' => $compra->id]) }}">
                                      <i class="fa fa-pencil"></i> Editar
                                    </a>
                                  </li>
                                @endpermission
                              </ul>
                            </div>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- /.tab-pane -->
            @permission('inventario-ingreso-index')
              <div id="tab-12" class="tab-pane">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Inventario</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Costo</th>
                        <th class="text-center">Acción</th>
                      </tr>
                    </thead>
                    <tbody class="text-center">
                      @foreach($proveedor->inventariosV2Ingreso as $ingreso)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            @permission('inventario-v2-view')
                              <a href="{{ route('admin.inventario.v2.show', ['inventario' => $ingreso->inventario_id]) }}">
                                {{ $ingreso->inventario->nombre }}
                              </a>
                            @else
                              {{ $ingreso->inventario->nombre }}
                            @endpermission
                          </td>
                          <td class="text-right">{{ $ingreso->cantidad() }}</td>
                          <td class="text-right">
                            @if($ingreso->costo)
                              {{ $ingreso->costo() }}
                            @else
                              @nullablestring(null)
                            @endif
                          </td>
                          <td>
                            @permission('inventario-ingreso-view|inventario-ingreso-edit')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('inventario-ingreso-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.ingreso.show', ['ingreso' => $ingreso->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('inventario-ingreso-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.inventario.ingreso.edit', ['ingreso' => $ingreso->id]) }}">
                                        <i class="fa fa-pencil"></i> Editar
                                      </a>
                                    </li>
                                  @endpermission
                                </ul>
                              </div>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endpermission
            @permission('factura-index')
              <div id="tab-13" class="tab-pane">
                <div class="panel-body">
                  <table class="table data-table table-bordered table-hover table-sm w-100">
                    <thead>
                      <tr class="text-center">
                        <th>#</th>
                        <th>Contrato</th>
                        <th>Tipo</th>
                        <th>Folio</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                        <th>Pago</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($proveedor->facturas as $factura)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $factura->contrato->nombre }}</td>
                          <td>{{ $factura->tipo() }}</td>
                          <td>{{ $factura->nombre }}</td>
                          <td class="text-right">{{ $factura->valor() }}</td>
                          <td class="text-center">{{ $factura->fecha }}</td>
                          <td class="text-center"><small>{!! $factura->pago() !!}</small></td>
                          <td class="text-center">
                            @permission('factura-view|factura-edit')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('factura-view')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.facturas.show', ['factura' => $factura->id]) }}">
                                        <i class="fa fa-search"></i> Ver
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('factura-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.facturas.edit', ['factura' => $factura->id]) }}">
                                        <i class="fa fa-pencil"></i> Editar
                                      </a>
                                    </li>
                                  @endpermission
                                </ul>
                              </div>
                            @endpermission
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endpermission
          </div><!-- /.tab-content -->
        </div>
      </div>
    </div>
  @endpermission

  @permission('proveedor-edit')
    <div id="delDataModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delDataModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delDataModalForm" action="#" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delDataModalLabel"></h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar <span id="delDataModalHeader"></span>?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm btn-delete-data" type="submit" disabled>Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="statusModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="statusModalForm" action="#" method="POST">
            @method('PATCH')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="statusModalLabel">Seleccionar Dirección</h4>
            </div>
            <div class="modal-body text-center">
              <h4 class="text-direccion" style="display: none">¿Esta seguro de marcar esta dirección como Seleccionada?</h4>
              <h4 class="text-contacto" style="display: none">¿Esta seguro de marcar este contaco como Seleccionado?</h4>
              <p>Se usará por defecto al crear una Orden de Compra.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm btn-status" type="submit" disabled>Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
  
  @permission('proveedor-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.proveedor.destroy', ['proveedor' => $proveedor->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Proveedor</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Proveedor?</h4>
              <p class="text-center">Se eliminará toda la información asociada a este Proveedor</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection

@section('script')
  @permission('proveedor-edit')
    <script type="text/javascript">
      $(document).ready(function () {
          $('#delDataModal').on('show.bs.modal', function (e) {
            let btn = $(e.relatedTarget),
                type = btn.data('type'),
                url = btn.data('url');

            if(!url){
              setTimeout(function (){
                $('#delDataModal').modal('hide');
              }, 500);
            }

            $('.btn-delete-data').prop('disabled', !url);

            let [header, label] = modalTitles(type);

            $('#delDataModalHeader').text(header);
            $('#delDataModalLabel').text(label);
            $('#delDataModalForm').attr('action', url);
          });

        $('#statusModal').on('show.bs.modal', function (e) {
          let btn = $(e.relatedTarget),
              url = btn.data('url'),
              type = btn.data('type');

          if(!url){
            setTimeout(function (){
              $('#stausModal').modal('hide');
            }, 500);
          }

          let isContacto = type == 'contacto';
          let title = isContacto ? 'Seleccionar Contacto' : 'Seleccionar Dirección';

          $('.text-direccion').toggle(!isContacto);
          $('.text-contacto').toggle(isContacto);

          $('.btn-status').prop('disabled', !url);
          $('#statusModalForm').attr('action', url);
          $('#statusModalLabel').text(title);
        });
      });

      function modalTitles(type) {
        switch(type){
          case 'direccion':
            return ['esta Dirección', 'Eliminar Dirección'];
          break;
          case 'contacto':
            return ['este Contacto', 'Eliminar Contacto'];
          break;
          case 'producto':
            return ['este Producto', 'Eliminar Producto'];
          break;
        }
      }
    </script>
  @endpermission
@endsection
