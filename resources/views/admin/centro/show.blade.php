@extends('layouts.app')

@section('title', 'Centro de costo')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Centro de costos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contratos.index') }}">Centros de costo</a></li>
        <li class="breadcrumb-item active"><strong>Centro de costo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('contrato-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.contratos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('centro-costo-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.centro.edit', ['centro' => $centro->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('centro-costo-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $centro->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $centro->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div><!-- .row -->

  @permission('centro-costo-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.centro.destroy', ['centro' => $centro->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Centro de costo</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Centro de costo?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection
