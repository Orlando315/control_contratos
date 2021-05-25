@extends('layouts.app')

@section('title', 'Ordenes de compra')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ordenes de compra</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.compra.index') }}">Ordenes de compra</a></li>
        <li class="breadcrumb-item active"><strong>Orden de compra</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('compra-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.compra.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('compra-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.compra.edit', ['compra' => $compra->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      <a class="btn btn-default btn-sm" href="{{ route('admin.compra.pdf', ['compra' => $compra->id]) }}"><i class="fa fa fa-file-pdf-o" aria-hidden="true"></i> Descargar</a>
      @permission('compra-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Información</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Código</b>
              <span class="pull-right">
                {{ $compra->codigo() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Generado por</b>
              <span class="pull-right">
                @permission('user-view')
                  <a href="{{ route('admin.usuario.show', ['usuario' => $compra->user_id]) }}">
                    {{ $compra->user->nombre() }}
                  </a>
                @else
                  {{ $compra->user->nombre() }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Proveedor</b>
              <span class="pull-right">
                @permission('proveedor-view')
                  <a href="{{ route('admin.proveedor.show', ['proveedor' => $compra->proveedor_id]) }}">
                    {{ $compra->proveedor->nombre }}
                  </a>
                @else
                  {{ $compra->proveedor->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Partida</b>
              <span class="pull-right">
                @if($compra->partida)
                  @permission('partida-view')
                    <a href="{{ route('admin.partida.show', ['partida' => $compra->partida_id]) }}">
                      {{ $compra->partida->codigo }}
                    </a>
                  @else
                    {{ $compra->partida->codigo }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Total</b>
              <span class="pull-right">{{ $compra->total() }}</span>
            </li>
            <li class="list-group-item">
              <b>Notas</b>
              <span class="pull-right">@nullablestring($compra->notas)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $compra->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Contacto</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">
                @nullablestring(optional($compra->contacto)->nombre)
              </span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">
                @nullablestring(optional($compra->contacto)->telefono)
              </span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">
                @nullablestring(optional($compra->contacto)->email)
              </span>
            </li>
            @if($compra->proveedor->isEmpresa())
              <li class="list-group-item">
                <b>Cargo</b>
                <span class="pull-right">
                  @nullablestring(optional($compra->contacto)->cargo)
                </span>
              </li>
              <li class="list-group-item">
                <b>Descripción</b>
                <span class="pull-right">
                  @nullablestring(optional($compra->contacto)->descripcion)
                </span>
              </li>
            @endif
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    @if($compra->hasRequerimiento())
      <div class="col-md-3">
        <div class="ibox">
          <div class="ibox-title">
            <h5>Requerimiento</h5>
          </div>
          <div class="ibox-content no-padding">
            <ul class="list-group">
              <li class="list-group-item">
                <b>Requerimiento</b>
                <span class="pull-right">
                  @permission('requerimiento-material-view')
                    <a href="{{ route('admin.requerimiento.material.show', ['requerimiento' => $compra->requerimiento_id]) }}">
                      {{ $compra->requerimiento->id() }}
                    </a>
                  @else
                    {{ $compra->requerimiento->id() }}
                  @endpermission
                </span>
              </li>
              <li class="list-group-item">
                <b>Solicitante</b>
                <span class="pull-right">
                  @permission('user-view')
                    <a href="{{ route('admin.usuario.show', ['usuario' => $compra->requerimiento->solicitante]) }}">
                      {{ $compra->requerimiento->userSolicitante->nombre() }}
                    </a>
                  @else
                    {{ $compra->requerimiento->userSolicitante->nombre() }}
                  @endpermission
                </span>
              </li>
              <li class="list-group-item">
                <b>Dirigido a</b>
                <span class="pull-right">
                  @permission('user-view')
                    <a href="{{ route('admin.usuario.show', ['usuario' => $compra->requerimiento->dirigido]) }}">
                      {{ $compra->requerimiento->dirigidoA->nombre() }}
                    </a>
                  @else
                    {{ $compra->requerimiento->dirigidoA->nombre() }}
                  @endpermission
                </span>
              </li>
              <li class="list-group-item">
                <b>Requerido para el</b>
                <span class="pull-right">
                  @nullablestring(optional($compra->requerimiento->fecha)->format('d-m-Y'))
                </span>
              </li>
              <li class="list-group-item">
                <b>Urgencia</b>
                <span class="pull-right">
                  {!! $compra->requerimiento->urgencia() !!}
                </span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>
    @endif

    <div class="col-md-3">
      @if($compra->facturacion)
        <div class="ibox">
          <div class="ibox-title">
            <h5>Facturación</h5>
            @permission('compra-edit')
              <div class="ibox-tools">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                  <i class="fa fa-cogs"></i>
                </a>
                <ul class="dropdown-menu dropdown-user dropdown-menu-right" x-placement="bottom-start">
                  @if(Auth::user()->empresa->configuracion->isIntegrationComplete('sii'))
                    <li>
                      <a class="dropdown-item" type="button" data-toggle="modal" data-target="#syncModal">
                        <i class="fa fa-refresh"></i> Sincronizar
                      </a>
                    </li>
                  @endif
                  <li>
                    <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delFacturacionModal">
                      <i class="fa fa-times"></i> Eliminar
                    </a>
                  </li>
                </ul>
              </div>
            @endpermission
          </div>
          <div class="ibox-content no-padding">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Código</b>
                <span class="pull-right">
                  {{ $compra->facturacion->codigo }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Emisor</b>
                <span class="pull-right">
                  @nullablestring($compra->facturacion->emisor)
                </span>
              </li>
              <li class="list-group-item">
                <b>Razón social</b>
                <span class="pull-right">
                  {{ $compra->facturacion->razon_social }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Documento</b>
                <span class="pull-right">
                  {{ $compra->facturacion->documento }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Folio</b>
                <span class="pull-right">
                  {{ $compra->facturacion->folio }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right">
                  {{ $compra->facturacion->fecha }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Monto</b>
                <span class="pull-right">
                  {{ $compra->facturacion->monto }}
                </span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      @else
        @if(Auth::user()->empresa->configuracion->isIntegrationIncomplete('sii'))
          <div class="alert alert-danger alert-important">
            <p class="m-0"><strong>¡Integración incompleta!</strong> Debe completar los datos de su integración con Facturación Sii antes de poder asociar a una factura. <a href="{{ route('perfil.edit') }}">Editar perfil Empresa</a></p>
          </div>
        @else
          <div class="w-100 text-center">
            @permission('compra-edit')
              <a class="btn btn-primary" href="{{ route('admin.compra.facturacion.create', ['compra' => $compra->id]) }}">Asociar factura</a>
            @endpermission
          </div>
        @endif
      @endif
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Productos</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Tipo</br>código</th>
                <th class="text-center">Código</th>
                <th class="text-center">Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Unidad</th>
                <th class="text-center">Precio</br>Unitario</th>
                <th class="text-center">IVA</th>
                <th class="text-center">Total</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($compra->productos as $producto)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>@nullablestring($producto->tipo_codigo)</td>
                  <td>@nullablestring($producto->codigo)</td>
                  <td>
                    @if($producto->inventario && Auth::user()->hasPermission('inventario-view'))
                      <a href="{{ route('admin.inventario.v2.show', ['inventario' => $producto->inventario_id]) }}">
                        {{ $producto->nombre }}
                      </a>
                    @else
                      {{ $producto->nombre }}
                    @endif
                    @if($producto->descripcion)
                      <p class="m-0"><small>{{ $producto->descripcion }}</small></p>
                    @endif
                  </td>
                  <td class="text-right">{{ $producto->cantidad() }}</td>
                  <td class="text-center">
                    @if($producto->inventario)
                      {{ $producto->inventario->unidad->nombre }}
                    @else
                      @nullablestring(null)
                    @endif
                  </td>
                  <td class="text-right">{{ $producto->precio() }}</td>
                  <td class="text-right">{{ $producto->impuesto() }}</td>
                  <td class="text-right">{{ $producto->total() }}</td>
                  <td class="text-center">
                    @permission('compra-edit')
                      <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                          <li>
                            <a class="dropdown-item" href="{{ route('admin.compra.producto.edit', ['producto' => $producto->id]) }}">
                              <i class="fa fa-pencil"></i> Editar
                            </a>
                          </li>
                          <li><a class="dropdown-item text-danger" type="button" title="Eliminar producto" data-url="{{ route('admin.compra.producto.destroy', ['producto' => $producto->id]) }}" data-toggle="modal" data-target="#delProductoModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</a></li>
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
    </div>
  </div>

  @permission('compra-edit')
    <div id="delProductoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delProductoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delProductoModalForm" action="#" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delDataModalLabel">Eliminar Producto</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Producto?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm btn-delete-data" type="submit" disabled>Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
    
  @permission('compra-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.compra.destroy', ['compra' => $compra->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delModalLabel">Eliminar Cotización</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Cotización?</h4>
              <p class="text-center">Se eliminará toda la información asociada a esta Cotización</p>
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

  @if($compra->facturacion && Auth::user()->hasPermission('compra-edit'))
    <div id="syncModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="syncModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.compra.facturacion.sync', ['facturacion' => $compra->facturacion->id]) }}" method="POST">
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="syncModalLabel">Sincronizar Facturación</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de sincronizar esta Facturación?</h4>
              <p class="text-center">Toda la informacón será actualizada con la API de Facturación SII</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="delFacturacionModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFacturacionModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.compra.facturacion.destroy', ['facturacion' => $compra->facturacion->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>

              <h4 class="modal-title" id="delFacturacionModalLabel">Eliminar Facturación</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar esta Facturación?</h4>
              <p class="text-center">La orden de compra ya no estará asociada a esta factura.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('script')
  @permission('compra-edit')
    <script type="text/javascript">
      $(document).ready(function() {
        $('#delProductoModal').on('show.bs.modal', function (e) {
          let btn = $(e.relatedTarget),
              url = btn.data('url');

          if(!url){
            setTimeout(function (){
              $('#delProductoModal').modal('hide');
            }, 500);
          }

          $('.btn-delete-data').prop('disabled', !url);
          $('#delProductoModalForm').attr('action', url);
        });
      });
    </script>
  @endpermission
@endsection
