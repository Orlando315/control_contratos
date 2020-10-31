@extends('layouts.app')

@section('title', 'Faena')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Faenas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Faenas</a></li>
        <li class="breadcrumb-item active"><strong>Faena</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.faena.edit', ['faena' => $faena->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Faena</b>
              <span class="pull-right">{{ $faena->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $faena->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-clipboard"></i> Contratos</h5>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Inicio</th>
                <th class="text-center">Fin</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Empleados</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($faena->contratos as $contrato)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $contrato->nombre }}</td>
                  <td>{{ $contrato->descripcion ?? 'M/A' }}</td>
                  <td>{{ $contrato->inicio }}</td>
                  <td>{{ $contrato->fin }}</td>
                  <td>{{ $contrato->valor() }}</td>
                  <td>{{ $contrato->empleados->count() }}</td>
                  <td>
                    <a class="btn btn-success btn-flat btn-xs" href="{{ route('admin.contratos.show', ['contrato' => $contrato->id] )}}"><i class="fa fa-search"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div><!-- .row -->

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.faena.destroy', ['faena' => $faena->id]) }}" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Faena</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Faena?</h4>
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
