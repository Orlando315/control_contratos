@extends( 'layouts.app' )

@section( 'title', 'Sueldo - '.config( 'app.name' ) )
@section( 'header', 'Sueldo' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('sueldos.index', ['contrato' => $sueldo->contrato_id]) }}">Sueldos</a></li>
	  <li class="active"> Sueldo </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('sueldos.index', ['contrato' => $sueldo->contrato_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del sueldo
            </h4>
            <p class="text-muted text-center">{{ $sueldo->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Mes pagado</b>
                <span class="pull-right">{{ $sueldo->mesPagado() }}</span>
              </li>
              <li class="list-group-item">
                <b>Alcance liquido</b>
                <span class="pull-right">{{ $sueldo->alcanceLiquido() }}</span>
              </li>
              <li class="list-group-item">
                <b>Asistencias</b>
                <span class="pull-right">{{ $sueldo->asistencias }}</span>
              </li>
              <li class="list-group-item">
                <b>Anticipo</b>
                <span class="pull-right">{{ $sueldo->anticipo() }}</span>
              </li>
              <li class="list-group-item">
                <b>Bono de reemplazo</b>
                <span class="pull-right"> {{ $sueldo->bonoReemplazo() }} </span>
              </li>
              <li class="list-group-item">
                <b>Sueldo liquido</b>
                <span class="pull-right"> {{ $sueldo->sueldoLiquido() }} </span>
              </li>
              <li class="list-group-item">
                <b>Adjunto</b>
                <span class="pull-right">{!! $sueldo->adjunto() !!}</span>
              </li>
              <li class="list-group-item">
                <b>Recibido</b>
                <span class="pull-right">{!! $sueldo->recibido() !!}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>

@endsection
