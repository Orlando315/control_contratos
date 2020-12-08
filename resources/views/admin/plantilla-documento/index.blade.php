@extends('layouts.app')

@section('title', 'Documentos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Documentos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Documentos</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-file-text-o"></i> {{ count($documentos) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documento</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-object-group"></i> Plantillas</a></li>
          <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-cube"></i> Variables</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              @if(Auth::user()->tipo < 2)
                <div class="mb-3 text-right">
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Documento</a>
                </div>
              @endif

              <table class="table data-table table-bordered table-hover w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Padre</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($documentos as $documento)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $documento->nombre }}</td>
                      <td>{{ $documento->contrato->nombre }}</td>
                      <td>{{ $documento->empleado->nombre() }}</td>
                      <td>@nullablestring(optional($documento->padre)->nombre)</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.plantilla.documento.show', ['documento' => $documento->id] )}}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.edit', ['documento' => $documento->id] )}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Plantilla</a>
              </div>
              <table class="table data-table table-bordered table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Secciones</th>
                    <th class="text-center">Documentos</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($plantillas as $plantilla)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $plantilla->nombre }}</td>
                      <td>{{ $plantilla->secciones_count }}</td>
                      <td>{{ $plantilla->documentos_count }}</td>
                      <td>
                        <a class="btn btn-success btn-xs" href="{{ route('admin.plantilla.show', ['plantilla' => $plantilla->id] )}}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.edit', ['plantilla' => $plantilla->id] )}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div id="tab-3" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3 text-right">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.variable.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Variable</a>
                <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#generateModal"><i class="fa fa-random" aria-hidden="true"></i> Generar variables</button>
              </div>
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
                <tbody class="text-center">
                  @foreach($variables as $variable)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $variable->nombre }}</td>
                      <td>{{ $variable->tipo() }}</td>
                      <td>{{ $variable->variable }}</td>
                      <td>
                        @if(!$variable->isStatic())
                          <a class="btn btn-primary btn-xs" href="{{ route('admin.variable.edit', ['variable' => $variable->id] )}}"><i class="fa fa-pencil"></i></a>
                          <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delModal" data-url="{{ route('admin.variable.destroy', ['variable' => $variable->id]) }}"><i class="fa fa-times" aria-hidden="true"></i></button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
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
        <form id="generateete-form" action="{{ route('admin.variable.generate') }}" method="POST">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="generateModalLabel">Generar variables de Empleado</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">Generar variables</h4>
            <p class="text-center">Se crearán variables estaticas para los Documentos que se sustituirán con la información del Empleado</p>
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
