@extends('layouts.app')

@section('title', 'Facturaciones')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Facturaciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.facturacion.index') }}">Facturaciones</a></li>
        <li class="breadcrumb-item active"><strong>Facturación</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.facturacion.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
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
              <b>Total</b>
              <span class="pull-right">
                {{ $facturacion->cotizacion->total() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Factura Sii ID</b>
              <span class="pull-right">
                {{ $facturacion->sii_factura_id }}
              </span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">
                {{ $facturacion->rut }}
              </span>
            </li>
            <li class="list-group-item">
              <b>DV</b>
              <span class="pull-right">
                {{ $facturacion->dv }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Firma</b>
              <span class="pull-right">
                {{ $facturacion->firma }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Total pagado</b>
              <span class="pull-right">
                {{ $facturacion->pagado() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Por pagar</b>
              <span class="pull-right">
                {{ $facturacion->pendiente() }}
              </span>
            </li>
            <li class="list-group-item">
              <b>Estatus</b>
              <span class="pull-right">
                {!! $facturacion->status() !!}
              </span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $facturacion->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Cotización</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Código</b>
              <span class="pull-right">
                <a href="{{ route('admin.cotizacion.show', ['cotizacion' => $facturacion->cotizacion_id]) }}">
                  {{ $facturacion->cotizacion->codigo() }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Cliente</b>
              <span class="pull-right">
                <a href="{{ route('admin.cliente.show', ['cliente' => $facturacion->cotizacion->cliente_id]) }}">
                  {{ $facturacion->cotizacion->cliente->nombre }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Total</b>
              <span class="pull-right">{{ $facturacion->cotizacion->total() }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $facturacion->cotizacion->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-credit-card"></i> Pagos</h5>
          <div class="ibox-tools">
            @if(!$facturacion->isPaga())
              <a class="btn btn-primary btn-xs" href="{{ route('admin.pago.create', ['facturacion' => $facturacion->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Pago</a>
            @endif
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Método</th>
                <th class="text-center">Monto</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Adjunto</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($facturacion->pagos as $pago)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $pago->metodo() }}</td>
                  <td class="text-right">{{ $pago->monto() }}</td>
                  <td>@nullablestring($pago->descripcion)</td>
                  <td>
                    @if($pago->adjunto)
                      <a href="{{ $pago->download }}">Descargar</a>
                    @else
                      @nullablestring(null)
                    @endif
                  </td>
                  <td>
                    <div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                        <li>
                          <a class="dropdown-item" href="{{ route('admin.pago.edit', ['pago' => $pago->id]) }}">
                            <i class="fa fa-pencil"></i> Editar
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delDataModal" data-url="{{ route('admin.pago.destroy', ['pago' => $pago->id]) }}">
                            <i class="fa fa-times"></i> Eliminar
                          </a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

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

            <h4 class="modal-title">Eliminar Pago</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Pago?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm btn-delete-data" type="submit" disabled>Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#delDataModal').on('show.bs.modal', function (e) {
        let btn = $(e.relatedTarget),
            url = btn.data('url');

        if(!url){
          setTimeout(function (){
            $('#delDataModal').modal('hide');
          }, 500);
        }

        $('.btn-delete-data').prop('disabled', !url);
        $('#delDataModalForm').attr('action', url);
      });
    })
  </script>
@endsection
