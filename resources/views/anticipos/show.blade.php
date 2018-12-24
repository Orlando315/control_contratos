@extends( 'layouts.app' )

@section( 'title', 'Anticipo - '.config( 'app.name' ) )
@section( 'header', 'Anticipo' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('anticipos.index') }}">Anticipos</a></li>
	  <li class="active"> Anticipo </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('anticipos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('anticipos.edit', [$anticipo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del Anticipo
            </h4>
            <p class="text-muted text-center">{{ $anticipo->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  <a href="{{ route('contratos.show', ['contrato' => $anticipo->contrato->id]) }}">
                    {{ $anticipo->contrato->nombre }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Empleado</b>
                <span class="pull-right">
                  <a href="{{ route('empleados.show', ['empleado' => $anticipo->empleado_id]) }}">{{ $anticipo->empleado->usuario->nombres }} {{ $anticipo->empleado->usuario->apellidos }}</a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right">{{ $anticipo->fecha }}</span>
              </li>
              <li class="list-group-item">
                <b>Anticipo</b>
                <span class="pull-right"> {{ $anticipo->anticipo() }}</span>
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
          <h4 class="modal-title" id="delModalLabel">Eliminar Anticipo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('anticipos.destroy', [$anticipo->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Anticipo?</h4><br>

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
