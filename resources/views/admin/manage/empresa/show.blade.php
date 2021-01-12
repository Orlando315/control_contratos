@extends('layouts.app')

@section('title', 'Empresa')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.empresa.index') }}">Empresas</a></li>
        <li class="breadcrumb-item active"><strong>Empresa</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.manage.empresa.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.manage.empresa.edit', ['empresa' => $empresa->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox ibox-with-footer">
        <div class="ibox-title">
          <h5><i class="fa fa-info"></i> Información</h5>
        </div>
        @if($empresa->logo)
          <div class="ibox-content no-padding text-center border-left-right">
            <img class="img-fluid" src="{{ $empresa->logo_url }}" alt="Logo" style="max-height: 180px;margin: 0 auto;">
          </div>
        @endif
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">@nullablestring($empresa->rut)</span>
            </li>
            <li class="list-group-item">
              <b>Razón social</b>
              <span class="pull-right">{{ $empresa->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Representante</b>
              <span class="pull-right">@nullablestring($empresa->representante)</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring($empresa->telefono)</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring($empresa->email)</span>
            </li>
            <li class="list-group-item">
              <b>Jornada</b>
              <span class="pull-right">@nullablestring($empresa->configuracion->jornada)</span>
            </li>
            <li class="list-group-item">
              <b>Días antes del vencimiento</b>
              <span class="pull-right">@nullablestring($empresa->configuracion->dias_vencimiento)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $empresa->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="row">
        <div class="col-6 col-md-4">
          <div class="ibox">
            <div class="ibox-title">
              <h5>Usuarios</h5>
            </div>
            <div class="ibox-content">
              <h2><i class="fa fa-users"></i> {{ $empresa->users_count }}</h2>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4">
          <div class="ibox">
            <div class="ibox-title">
              <h5>Contratos</h5>
            </div>
            <div class="ibox-content">
              <h2><i class="fa fa-clipboard"></i> {{ $empresa->contratos_count }}</h2>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4">
          <div class="ibox">
            <div class="ibox-title">
              <h5>Empleados</h5>
            </div>
            <div class="ibox-content">
              <h2><i class="fa fa-address-card"></i> {{ $empresa->empleados_count }}</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-users" aria-hidden="true"></i> Usuarios</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Contratos</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.user.create', ['empresa' => $empresa->id]) }}">
                  <i class="fa fa-plus" aria-hidden="true"></i> Agregar Usuario
                </a>
              </div>

              <table class="table data-table table-bordered table-hover w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Roles</th>
                    <th class="text-center">RUT</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($empresa->users()->staff()->get() as $user)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td clasS="text-center">
                        {!! $user->allRolesNames() !!}
                      </td>
                      <td>{{ $user->rut }}</td>
                      <td>{{ $user->nombre() }}</td>
                      <td>@nullablestring($user->email)</td>
                      <td class="text-center">
                        <a class="btn btn-success btn-xs" href="{{ route('admin.manage.user.show', ['user' => $user->id]) }}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.user.edit', ['user' => $user->id]) }}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Inicio</th>
                    <th class="text-center">Fin</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Empleados</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empresa->contratos as $contrato)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $contrato->nombre }}</td>
                      <td>@nullablestring($contrato->descripcion)</td>
                      <td>{{ $contrato->inicio }}</td>
                      <td>{{ $contrato->fin }}</td>
                      <td class="text-right">{{ $contrato->valor() }}</td>
                      <td>{{ $contrato->empleados()->count() }}</td>
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

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.manage.empresa.destroy', ['empresa' => $empresa->id]) }}" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>

            <h4 class="modal-title" id="delModalLabel">Eliminar Empresa</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Empresa?</h4>
            <p class="text-center">Se eliminará toda la información asociada a esta Empresa</p>
            <p class="text-center">Para confirmar esta acción, introduzca su contraseña</p>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password">Contraseña: *</label>
              <input id="password" class="form-control" type="password" name="password" placeholder="Contraseña" required>
            </div>
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
