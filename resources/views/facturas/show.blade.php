@extends( 'layouts.app' )

@section( 'title', 'Factura - '.config( 'app.name' ) )
@section( 'header', 'Factura' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('facturas.index') }}">Facturas</a></li>
	  <li class="active"> Factura </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('facturas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('facturas.edit', [$factura->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del factura
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  <a href="{{ route('contratos.show', ['contrato' => $factura->contrato->id]) }}">{{ $factura->contrato->nombre }} </a>
                </span>
              </li>
              @if($factura->etiqueta)
              <li class="list-group-item">
                <b>Etiqueta</b>
                <span class="pull-right">
                  @if(Auth::user()->tipo <= 2)
                    <a href="{{ route('etiquetas.show', ['id' => $factura->etiqueta_id]) }}">{{ $factura->etiqueta->etiqueta }}</a>
                  @else
                    {{ $factura->etiqueta->etiqueta }}
                  @endif
                </span>
              </li>
              @endif
              <li class="list-group-item">
                <b>Tipo</b>
                <span class="pull-right">{{ $factura->tipo() }}</span>
              </li>
              <li class="list-group-item">
                <b>Nombre</b>
                <span class="pull-right">{{ $factura->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Realizada por</b>
                <span class="pull-right">{{ $factura->realizada_por }}</span>
              </li>
              <li class="list-group-item">
                <b>Realizada para</b>
                <span class="pull-right">{{ $factura->realizada_para }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right"> {{ $factura->fecha }} </span>
              </li>
              <li class="list-group-item">
                <b>Valor</b>
                <span class="pull-right">{{ $factura->valor() }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right"> {{ $factura->pago_fecha }} </span>
              </li>
              <li class="list-group-item">
                <b>Pago</b>
                <span class="pull-right"> {!! $factura->pago() !!} </span>
              </li>
              <li class="list-group-item">
                <b>Adjunto #1</b>
                <span class="pull-right">{!! $factura->adjunto(1) !!}</span>
              </li>
              <li class="list-group-item">
                <b>Adjunto #2</b>
                <span class="pull-right">{!! $factura->adjunto(2) !!}</span>
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
          <h4 class="modal-title" id="delModalLabel">Eliminar Factura</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('facturas.destroy', [$factura->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Factura?</h4><br>

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
