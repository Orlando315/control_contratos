@extends('layouts.app')

@section('title', 'Etiqueta - '.config('app.name'))
@section('header', 'Etiqueta')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('etiquetas.index') }}">Etiquetas</a></li>
	  <li class="active"> Etiqueta </li>
	</ol>
@endsection
@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('etiquetas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('etiquetas.edit', [$etiqueta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-warning">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos de la etiqueta
            </h4>
            <p class="text-muted text-center">{{ $etiqueta->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Etiqueta</b>
                <span class="pull-right">{{ $etiqueta->etiqueta }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-file"></i> Facturas </h3>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Contrato</th>
                  <th class="text-center">Tipo</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Valor</th>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Pago</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($etiqueta->facturas as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td><a href="{{ route('contratos.show', ['contrato' => $d->contrato->id]) }}">{{ $d->contrato->nombre }} </a></td>
                    <td>{{ $d->tipo() }}</td>
                    <td>{{ $d->nombre }}</td>
                    <td>{{ $d->valor() }}</td>
                    <td>{{ $d->fecha }}</td>
                    <td>{!! $d->pago() !!}</td>
                    <td>
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('facturas.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                      <a class="btn btn-success btn-flat btn-sm" href="{{ route('facturas.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-credit-card"></i> Gastos</h3>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Contrato</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Valor</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($etiqueta->gastos as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td><a href="{{ route('contratos.show', ['id', $d->contrato_id]) }}">{{ $d->contrato->nombre }}</a></td>
                    <td>{{ $d->nombre }}</td>
                    <td>{{ $d->valor() }}</td>
                    <td>
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('gastos.show', ['id' => $d->id]) }}"><i class="fa fa-search"></i></a>
                      <a class="btn btn-success btn-flat btn-sm" href="{{ route('gastos.edit', ['id' => $d->id]) }}"><i class="fa fa-pencil"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div><!-- .row -->
  </section>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Etiqueta</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('etiquetas.destroy', ['id' => $etiqueta->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar esta Etiqueta?</h4><br>

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
