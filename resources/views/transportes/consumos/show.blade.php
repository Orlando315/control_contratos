@extends( 'layouts.app' )

@section( 'title', 'Consumo - '.config( 'app.name' ) )
@section( 'header', 'Consumo' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('transportes.show', ['transporte' => $consumo->transporte_id]) }}">Transportes</a></li>
	  <li class="active"> Consumo </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('transportes.show', ['transporte' => $consumo->transporte_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('consumos.edit', [$consumo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del consumo
            </h4>
            <p class="text-muted text-center">{{ $consumo->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right">{{ $consumo->fecha() }}</span>
              </li>
              @if($consumo->tipo == 2)
                <li class="list-group-item">
                  <b>Cantidad</b>
                  <span class="pull-right">{{ $consumo->cantidad() }}</span>
                </li>
              @endif
              <li class="list-group-item">
                <b>Valor</b>
                <span class="pull-right">{{ $consumo->valor }}</span>
              </li>
              <li class="list-group-item">
                <b>Chofer</b>
                <span class="pull-right">{{ $consumo->chofer }}</span>
              </li>
              <li class="list-group-item">
                <b>Observación</b>
                <span class="pull-right">{{ $consumo->observacion }}</span>
              </li>
              <li class="list-group-item">
                <b>Adjunto</b>
                <span class="pull-right">{!! $consumo->adjunto() !!}</span>
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
          <h4 class="modal-title" id="delModalLabel">Eliminar Consumo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('consumos.destroy', [$consumo->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Consumo?</h4><br>

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