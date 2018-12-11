@extends( 'layouts.app')
@section( 'title', 'Usuarios - '.config( 'app.name' ) )
@section( 'header', 'Usuarios' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active"> Usuarios </li>
  </ol>
@endsection
@section( 'content' )
  @include( 'partials.flash' )
  <!-- Info boxes -->
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>
        
        <div class="info-box-content">
          <span class="info-box-text">Usuarios</span>
          <span class="info-box-number">{{ count( $users ) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-user-plus"></i></span>
        
        <div class="info-box-content">
          <span class="info-box-text">Solicitudes</span>
          <span class="info-box-number">{{ count( $notUsers ) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div><!--row-->

  <div class="row">
    <div class="col-md-12">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Usuarios</h3>
          <span class="pull-right">
            <a href="{{ route( 'users.create' ) }}" class="btn btn-flat btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo usuario</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Status</th>
                <th class="text-center">Role</th>
                <th class="text-center">Cedula</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Accion</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach( $users as $d )
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{!! $d->status() !!}</td>
                  <td>{{ $d->role }}</td>
                  <td>{{ $d->cedula() }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route( 'users.show', [ 'id' => $d->id ] )}}"><i class="fa fa-search"></i></a>
                    <a href="{{ route( 'users.edit', [ 'id' => $d->id ] ) }}" class="btn btn-flat btn-success btn-sm" title="Editar"><i class="fa fa-edit"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="solicitudes" class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Solicitudes de ingreso</h3>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Status</th>
                <th class="text-center">Cedula</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Departamento</th>
                <th class="text-center">Cargo</th>
                <th class="text-center">Accion</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach( $notUsersAll as $d )
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{!! $d->status() !!}</td>
                  <td>{{ $d->cedula() }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->departamento->departamento }}</td>
                  <td>{{ $d->cargo->cargo }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route( 'users.show', [ 'id' => $d->id ] )}}"><i class="fa fa-search"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
