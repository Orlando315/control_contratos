@extends('layouts.app')

@section('title', 'Etiqueta')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Etiquetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('etiquetas.index') }}">Etiquetas</a></li>
        <li class="breadcrumb-item active"><strong>Etiqueta</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('etiquetas.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('etiquetas.edit', ['etiqueta' => $etiqueta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Etiqueta</b>
              <span class="pull-right">{{ $etiqueta->etiqueta }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $etiqueta->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-file"></i> Facturas</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-credit-card"></i> Gastos</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover w-100">
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
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="{{ route('contratos.show', ['contrato' => $d->contrato->id]) }}">{{ $d->contrato->nombre }} </a></td>
                      <td>{{ $d->tipo() }}</td>
                      <td>{{ $d->nombre }}</td>
                      <td>{{ $d->valor() }}</td>
                      <td>{{ $d->fecha }}</td>
                      <td>{!! $d->pago() !!}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('facturas.show', ['factura' => $d->id] )}}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('facturas.edit', ['factura' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <table class="table data-table table-bordered table-hover w-100">
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
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="{{ route('contratos.show', ['id', $d->contrato_id]) }}">{{ $d->contrato->nombre }}</a></td>
                      <td>{{ $d->nombre }}</td>
                      <td>{{ $d->valor() }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('gastos.show', ['gasto' => $d->id]) }}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('gastos.edit', ['gasto' => $d->id]) }}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div>
    </div>
  </div><!-- .row -->

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('etiquetas.destroy', ['etiqueta' => $etiqueta->id]) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Etiqueta</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Etiqueta?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
