@extends( 'layouts.app' )

@section( 'title', 'Usuario - '.config( 'app.name' ) )
@section( 'header', 'Usuario' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
	  <li class="active"> Usuario </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('usuarios.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('usuarios.edit', [$usuario->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-success">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del usuario
            </h4>
            <p class="text-muted text-center">
              {{ $usuario->tipo() }}
            </p>

            <ul class="list-group list-group-unbordered">
              @if($usuario->empleado_id)
              <li class="list-group-item">
                <b>Empleado</b>
                <span class="pull-right">
                  <a href="{{ route('empleados.show', ['empleado'=> $usuario->empleado_id]) }}">Ver pefil</a>
                </span>
              </li>
              @endif
              <li class="list-group-item">
                <b>Nombres</b>
                <span class="pull-right">{{ $usuario->nombres }}</span>
              </li>
              <li class="list-group-item">
                <b>Apellidos</b>
                <span class="pull-right">{{ $usuario->apellidos }}</span>
              </li>
              <li class="list-group-item">
                <b>RUT</b>
                <span class="pull-right"> {{ $usuario->rut }} </span>
              </li>
              <li class="list-group-item">
                <b>Teléfono</b>
                <span class="pull-right"> {{ $usuario->telefono }} </span>
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <span class="pull-right">{{ $usuario->email }}</span>
              </li>
              <li class="list-group-item">
                <b>Registrado</b>
                <span class="pull-right">{{ $usuario->created_at }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Usuario</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('usuarios.destroy', [$usuario->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Usuario?</h4><br>

              <center>
                <button class="btn btn-flat btn-danger" type="submit">Eliminar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
