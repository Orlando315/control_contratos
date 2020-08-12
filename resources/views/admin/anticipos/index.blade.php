@extends('layouts.app')

@section('title', 'Anticipos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Anticipos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Anticipos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-level-up text-success"></i> {{ count($anticipos) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab">Anticipos</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab">Solicitudes</a></li>
          <li><a class="nav-link" href="#tab-3" data-toggle="tab">Rechazados</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-1">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.anticipos.individual') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Individual</a>
                <a class="btn btn-primary btn-xs" href="{{ route('admin.anticipos.masivo') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Masivo</a>
              </div>
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Anticipo</th>
                    <th class="text-center">Agregado</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($anticipos as $anticipo)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="{{ route('admin.contratos.show', ['contrato' => $anticipo->contrato->id]) }}">{{ $anticipo->contrato->nombre }} </a></td>
                      <td><a href="{{ route('admin.empleados.show', ['empleado' => $anticipo->empleado->id]) }}">{{ $anticipo->empleado->usuario->nombre() }}</a></td>
                      <td>{{ $anticipo->fecha }}</td>
                      <td>{{ $anticipo->anticipo() }}</td>
                      <td>{{ optional($anticipo->created_at)->format('d-m-Y H:i:s') }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.anticipos.show', ['anticipo' => $anticipo->id] )}}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.anticipos.edit', ['anticipo' => $anticipo->id] )}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="tab-2">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Anticipo</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($pendientes as $pendiente)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="{{ route('admin.contratos.show', ['contrato' => $pendiente->contrato->id]) }}">{{ $pendiente->contrato->nombre }} </a></td>
                      <td><a href="{{ route('admin.empleados.show', ['empleado' => $pendiente->empleado->id]) }}">{{ $pendiente->empleado->usuario->nombre() }}</a></td>
                      <td>{{ $pendiente->fecha }}</td>
                      <td>{{ $pendiente->anticipo() }}</td>
                      <td>
                        <div class="btn-group">
                          <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                          <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                            <li><a class="dropdown-item" href="{{ route('admin.anticipos.show', ['anticipo' => $pendiente->id] ) }}"><i class="fa fa-search"></i> Ver</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.anticipos.edit', ['anticipo' => $pendiente->id] ) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                            <li><a class="dropdown-item" type="button" data-url="{{ route('admin.anticipos.status', ['anticipo' => $pendiente->id] ) }}" data-type="1" data-toggle="modal" data-target="#statusAnticipoModal"><i class="fa fa-check"></i> Aprobar</a></li>
                            <li><a class="dropdown-item" type="button" data-url="{{ route('admin.anticipos.status', ['anticipo' => $pendiente->id] ) }}" data-type="0" data-toggle="modal" data-target="#statusAnticipoModal"><i class="fa fa-ban"></i> Rechazar</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="tab-3">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Anticipo</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($rechazados as $rechazado)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="{{ route('admin.contratos.show', ['contrato' => $rechazado->contrato->id]) }}">{{ $rechazado->contrato->nombre }} </a></td>
                      <td><a href="{{ route('admin.empleados.show', ['empleado' => $rechazado->empleado->id]) }}">{{ $rechazado->empleado->usuario->nombre() }}</a></td>
                      <td>{{ $rechazado->fecha }}</td>
                      <td>{{ $rechazado->anticipo() }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.anticipos.show', ['anticipo' => $rechazado->id] ) }}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="statusAnticipoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="statusAnticipoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="status-modal-form" action="#" method="POST">
          <input id="status-modal-value" type="hidden" name="status">
          {{ method_field('PUT') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="statusAnticipoModalLabel">Cambiar estatus</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de <span id="status-modal-label"></span> este Anticipo?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="submit" disabled>Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('#statusAnticipoModal').on('show.bs.modal', function (e) {
        let type = +$(e.relatedTarget).data('type'),
            url = $(e.relatedTarget).data('url');

        title = type == 1 ? 'aprobar' : 'rechazar';

        $('#status-modal-form button[type="submit"]').prop('disabled', !url)
        $('#status-modal-form').attr('action', url)
        $('#status-modal-value').val(type)
        $('#status-modal-label').text(title)
      })
    })
  </script>
@endsection
