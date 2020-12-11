@extends('layouts.app')

@section('title', 'Cotizaciones')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Cotizaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cotizacion.index') }}">Cotizaciones</a></li>
        <li class="breadcrumb-item active"><strong>Cotización</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.cotizacion.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.cotizacion.edit', ['cotizacion' => $cotizacion->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
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
                {{ $cotizacion->codigo() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Generado por</b>
              <span class="pull-right">
                <a href="{{ route('admin.usuarios.show', ['usuario' => $cotizacion->user_id]) }}">
                  {{ $cotizacion->user->nombre() }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Cliente</b>
              <span class="pull-right">
                <a href="{{ route('admin.cliente.show', ['cliente' => $cotizacion->cliente_id]) }}">
                  {{ $cotizacion->cliente->nombre }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Total</b>
              <span class="pull-right">{{ $cotizacion->total() }}</span>
            </li>
            <li class="list-group-item">
              <b>Notas</b>
              <span class="pull-right">@nullablestring($cotizacion->notas)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $cotizacion->created_at }}</small>
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
                @nullablestring(optional($cotizacion->contacto)->nombre)
              </span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">
                @nullablestring(optional($cotizacion->contacto)->telefono)
              </span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">
                @nullablestring(optional($cotizacion->contacto)->email)
              </span>
            </li>
            @if($cotizacion->cliente->isEmpresa())
              <li class="list-group-item">
                <b>Cargo</b>
                <span class="pull-right">
                  @nullablestring(optional($cotizacion->contacto)->cargo)
                </span>
              </li>
              <li class="list-group-item">
                <b>Descripción</b>
                <span class="pull-right">
                  @nullablestring(optional($cotizacion->contacto)->descripcion)
                </span>
              </li>
            @endif
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Despacho</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Ciudad</b>
              <span class="pull-right">
                @nullablestring(optional($cotizacion->direccion)->ciudad)
              </span>
            </li>
            <li class="list-group-item">
              <b>Comuna</b>
              <span class="pull-right">
                @nullablestring(optional($cotizacion->direccion)->comuna)
              </span>
            </li>
            <li class="list-group-item">
              <b>Dirección</b>
              <span class="pull-right">
                @nullablestring(optional($cotizacion->direccion)->direccion)
              </span>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-3">
      @if($cotizacion->facturacion)
        <div class="ibox">
          <div class="ibox-title">
            <h5>Facturación</h5>
          </div>
          <div class="ibox-content no-padding">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Factura Sii ID</b>
                <span class="pull-right">
                  <a href="{{ route('admin.facturacion.show', ['facturacion' => $cotizacion->facturacion->id]) }}">
                    {{ $cotizacion->facturacion->sii_factura_id }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>RUT</b>
                <span class="pull-right">
                  {{ $cotizacion->facturacion->rut }}
                </span>
              </li>
              <li class="list-group-item">
                <b>DV</b>
                <span class="pull-right">
                  {{ $cotizacion->facturacion->dv }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Firma</b>
                <span class="pull-right">
                  {{ $cotizacion->facturacion->firma }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Estatus</b>
                <span class="pull-right">
                  {!! $cotizacion->facturacion->status() !!}
                </span>
              </li>
              <li class="list-group-item text-center">
                <small class="text-muted">{{ $cotizacion->facturacion->created_at }}</small>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      @else
        @if(Auth::user()->empresa->configuracion->isIntegrationIncomplete('sii'))
          <div class="alert alert-danger alert-important">
            <p class="m-0"><strong>¡Integración incompleta!</strong> Debe completar los datos de su integración con Facturación Sii antes de poder realizar una facturación. <a href="{{ route('perfil.edit') }}">Editar perfil Empresa</a></p>
          </div>
        @else
          <div class="w-100 text-center">
            <a class="btn btn-primary" href="{{ route('admin.facturacion.create', ['cotizacion' => $cotizacion->id]) }}">Realizar facturación</a>
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
                <th class="text-center">Nombre</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Precio</th>
                <th class="text-center">Impuesto</br>adicional</th>
                <th class="text-center">Total</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cotizacion->productos as $producto)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>@nullablestring($producto->tipo_codigo)</td>
                  <td>@nullablestring($producto->codigo)</td>
                  <td>
                    @if($producto->inventario)
                      <a href="{{ route('admin.inventarios.show', ['inventario' => $producto->inventario_id]) }}">
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
                  <td class="text-right">{{ $producto->precio() }}</td>
                  <td class="text-right">{{ $producto->impuesto() }}</td>
                  <td class="text-right">{{ $producto->total() }}</td>
                  <td class="text-center">
                    <button class="btn btn-danger btn-xs" type="button" data-toggle="modal" data-target="#delProductoModal" data-url="{{ route('admin.cotizacion.producto.destroy', ['producto' => $producto->id]) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

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
  
  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.cotizacion.destroy', ['cotizacion' => $cotizacion->id]) }}" method="POST">
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
@endsection

@section('script')
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
@endsection
