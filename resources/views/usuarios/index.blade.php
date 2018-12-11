@extends( 'layouts.app' )
@section( 'title','Usuarios - '.config( 'app.name' ) )
@section( 'header','Usuarios' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Usuarios</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Usuarios</span>
          <span class="info-box-number">{{ count($usuarios) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-users"></i> Usuarios</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('usuarios.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Usuario</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombres</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">RUT</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($usuarios as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $d->nombres }}</td>
                  <td>{{ $d->apellidos }}</td>
                  <td>{{ $d->rut }}</td>
                  <td>{{ $d->telefono }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('usuarios.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('usuarios.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
