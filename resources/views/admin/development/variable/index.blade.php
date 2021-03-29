@extends('layouts.app')

@section('title', 'Variables')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Variables</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item">Development</li>
        <li class="breadcrumb-item active"><strong>Variables</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-cube" aria-hidden="true"></i> Variables Globales</h5>

          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.development.variable.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Variable</a>
            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#generateModal"><i class="fa fa-plus" aria-hidden="true"></i> Generar variables</button>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Variable</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($variables as $variable)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $variable->nombre }}</td>
                  <td class="text-center">{{ $variable->tipo() }}</td>
                  <td class="text-center">{{ $variable->variable }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                      <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                        <li>
                          <a class="dropdown-item" href="{{ route('admin.development.variable.edit', ['variable' => $variable->id]) }}">
                            <i class="fa fa-pencil"></i> Editar
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delModal" data-url="{{ route('admin.development.variable.destroy', ['variable' => $variable->id]) }}" data-target="#delProductoModal">
                            <i class="fa fa-times" aria-hidden="true"></i> Eliminar
                          </a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="delete-form" action="#" method="POST">
          @method('DELETE')

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar variable</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Variable?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="generateModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="generateete-form" action="{{ route('admin.development.variable.generate') }}" method="POST">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="generateModalLabel">Generar variables estaticas globales</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">Generar variables</h4>
            <p class="text-center">Se crearán variables estaticas para los Documentos que se sustituirán con la información del Empleado, Contrato y/o Postulante</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-warning btn-sm" type="submit">Generar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
  $(document).ready(function(){
      $('#delModal').on('show.bs.modal', function(e){
        var btn = $(e.relatedTarget),
            url = btn.data('url');

        if(!url){
          return false;
        }

        $('#delete-form').attr('action', url);
      });
    });
  </script>
@endsection
