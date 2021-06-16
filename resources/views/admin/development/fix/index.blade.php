@extends('layouts.app')

@section('title', 'Fixes')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Fixes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item active"><strong>Fixes</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-terminal" aria-hidden="true"></i> Fixs</h5>
        </div>
        <div class="ibox-content">
          <table class="table table-bordered data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Descripción</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Agregar Roles a los Usuarios que no tengan uno.</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'create.users.permissions']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Agregar los Usuarios a las Empresas a la que pertenecen (Usando el empresa_id, para agregarlos a la tabla empresa_user).</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'attach.users.empresas']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Agregar RUT a las Empresas segun el rut de su Usuario con Role Empresa.</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'copy.rut.empresas']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>4</td>
                <td>Agregar Role de Empleado a los User administradores que tengan registo de Empleado.</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'missing.empleado.role']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>5</td>
                <td>Eliminar las variables estaticas (reservadas) registradas en las Empresas.</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'remove.static.variables']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>6</td>
                <td>Migrar la relacion de Supervisor y Faena de Transporte a belongsToMany.</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'migrate.transporte.data']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>7</td>
                <td>Migrar informacion de Bodega y Ubicacion de InventarioV2 a la nueva relacion belongsToMany.</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'inventario.bodegas.ubicaciones']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
              <tr>
                <td>8</td>
                <td>Eliminar todos los registros de Inventario V1 y sus Entregas</td>
                <td class="text-center">
                  <a class="btn btn-xs btn-primary" href="{{ route('admin.development.fix.route', ['fix' => 'remove.inventario.v1']) }}" target="_blank">
                    <i class="fa fa-play" aria-hidden="false"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
