@extends('layouts.app')

@section('title', 'Gasto - '.config('app.name'))
@section('header', 'Gasto')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('gastos.index') }}">Gastos</a></li>
	  <li class="active"> Gasto </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('gastos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('gastos.edit', [$gasto->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-success">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del gasto
            </h4>
            <p class="text-muted text-center">{{ $gasto->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  @if(Auth::user()->tipo <= 2)
                    <a href="{{ route('contratos.show', ['contrato' => $gasto->contrato_id]) }}">{{ $gasto->contrato->nombre }}</a>
                  @else
                    {{ $gasto->contrato->nombre }}
                  @endif
                </span>
              </li>
              <li class="list-group-item">
                <b>Etiqueta</b>
                <span class="pull-right">
                  @if(Auth::user()->tipo <= 2)
                    <a href="{{ route('etiquetas.show', ['id' => $gasto->etiqueta_id]) }}">{{ $gasto->etiqueta->etiqueta }}</a>
                  @else
                    {{ $gasto->etiqueta->etiqueta }}
                  @endif
                </span>
              </li>
              <li class="list-group-item">
                <b>Nombre</b>
                <span class="pull-right">{{ $gasto->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Valor</b>
                <span class="pull-right">{{ $gasto->valor() }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>
    </div><!-- .row -->
  </section>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Gasto</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('gastos.destroy', ['id' => $gasto->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Gasto?</h4><br>

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
