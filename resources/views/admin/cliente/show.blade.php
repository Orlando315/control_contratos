@extends('layouts.app')

@section('title', 'Cliente')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Clientes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.cliente.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active"><strong>Cliente</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.cliente.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @if($cliente->isPersona())
        <a class="btn btn-default btn-sm" href="{{ route('admin.cliente.edit', ['cliente' => $cliente->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endif
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>{{ $cliente->isEmpresa() ? 'Razón social' : 'Nombre' }}</b>
              <span class="pull-right">{{ $cliente->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ $cliente->rut ?? 'N/A' }}</span>
            </li>
            @if($cliente->isPersona())
              <li class="list-group-item">
                <b>Teléfono</b>
                <span class="pull-right">{{ $cliente->telefono ?? 'N/A' }}</span>
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <span class="pull-right">{{ $cliente->email ?? 'N/A' }}</span>
              </li>
              <li class="list-group-item">
                <b>Descripción</b>
                <span class="pull-right">{{ $cliente->descripcion ?? 'N/A' }}</span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Tipo</b>
              <span class="pull-right">{!! $cliente->tipo() !!}</span>
            </li>
            @if($cliente->hasProveedorProfile())
              <li class="list-group-item">
                <b>Proveedor</b>
                <span class="pull-right">
                  <a href="{{ route('admin.proveedor.show', ['proveedor' => $cliente->proveedor_id]) }}">
                    Ver perfil
                  </a>
                </span>
              </li>
            @endif
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $cliente->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-map-marker" aria-hidden="true"></i> Direcciones</a></li>
          @if($cliente->isEmpresa())
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-address-book" aria-hidden="false"></i> Contactos</a></li>
          @endif
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.direccion.create', ['id' => $cliente->id, 'type' => 'cliente']) }}">
                  <i class="fa fa-plus" aria-hidden="true"></i> Nueva dirección
                </a>
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
                  @foreach($cliente->direcciones as $direccion)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>{{ $direccion->ciudad ?? 'N/A' }}</td>
                      <td>{{ $direccion->comuna ?? 'N/A' }}</td>
                      <td>{{ $direccion->direccion ?? 'N/A' }}</td>
                      <td class="text-center">
                        <small>
                          {!! $direccion->status() !!}
                        </small>
                      </td>
                      <td class="text-center">
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
                <a class="btn btn-primary btn-xs" href="{{ route('admin.contacto.create', ['id' => $cliente->id, 'type' => 'cliente']) }}">
                  <i class="fa fa-plus" aria-hidden="true"></i> Nuevo contacto
                </a>
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
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cliente->contactos as $contacto)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td>{{ $contacto->nombre }}</td>
                      <td>{{ $contacto->telefono }}</td>
                      <td>{{ $contacto->email ?? 'N/A' }}</td>
                      <td>{{ $contacto->cargo ?? 'N/A' }}</td>
                      <td>{{ $contacto->descripcion ?? 'N/A' }}</td>
                      <td class="text-center">
                        <div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                          <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
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
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de marcar esta dirección como Seleccionada?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm btn-status" type="submit" disabled>Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.cliente.destroy', ['cliente' => $cliente->id]) }}" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>

            <h4 class="modal-title" id="delModalLabel">Eliminar Cliente</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Cliente?</h4>
            <p class="text-center">Se eliminará toda la información asociada a este Cliente</p>
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

        let [header, label] = type == 'direccion' ? ['esta Dirección', 'Eliminar Dirección'] : ['este Contacto', 'Eliminar Contacto'];
        $('#delDataModalHeader').text(header);
        $('#delDataModalLabel').text(label);
        $('#delDataModalForm').attr('action', url);
      });

      $('#statusModal').on('show.bs.modal', function (e) {
        let btn = $(e.relatedTarget),
            url = btn.data('url');

        if(!url){
          setTimeout(function (){
            $('#stausModal').modal('hide');
          }, 500);
        }

        $('.btn-status').prop('disabled', !url);
        $('#statusModalForm').attr('action', url);
      });
    })
  </script>
@endsection
