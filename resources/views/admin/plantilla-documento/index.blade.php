@extends('layouts.app')

@section('title', 'Documentos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Documentos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
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
          @permission('plantilla-documento-index')
            <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documento</a></li>
          @endpermission
          @permission('plantilla-index')
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-object-group"></i> Plantillas</a></li>
          @endpermission
          @permission('plantilla-variable-index|plantilla-variable-view')
            <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-cube"></i> Variables</a></li>
          @endpermission
        </ul>
        <div class="tab-content">
          @permission('plantilla-documento-index')
            <div id="tab-1" class="tab-pane active">
              <div class="panel-body">
                @permission('plantilla-documento-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.documento.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Documento</a>
                  </div>
                @endpermission

                <table class="table data-table table-bordered table-hover w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Contrato</th>
                      <th class="text-center">Dirigido a</th>
                      <th class="text-center">Padre</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($documentos as $documento)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>@nullablestring($documento->nombre)</td>
                        <td>@nullablestring(optional($documento->contrato)->nombre)</td>
                        <td>{{ $documento->model->nombre() }}</td>
                        <td>@nullablestring(optional($documento->padre)->nombre)</td>
                        <td class="text-center">
                          @permission('plantilla-documento-view|plantilla-documento-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('plantilla-documento-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.plantilla.documento.show', ['documento' => $documento->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('plantilla-documento-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.plantilla.documento.edit', ['documento' => $documento->id]) }}">
                                      <i class="fa fa-pencil"></i> Editar
                                    </a>
                                  </li>
                                @endpermission
                              </ul>
                            </div>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
          @permission('plantilla-index')
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                @permission('plantilla-create')
                  <div class="mb-3 text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.plantilla.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Plantilla</a>
                  </div>
                @endpermission
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
                  <tbody>
                    @foreach($plantillas as $plantilla)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $plantilla->nombre }}</td>
                        <td class="text-right">{{ $plantilla->secciones_count }}</td>
                        <td class="text-right">{{ $plantilla->documentos_count }}</td>
                        <td class="text-center">
                          @permission('plantilla-view|plantilla-edit')
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                              <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                @permission('plantilla-view')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.plantilla.show', ['plantilla' => $plantilla->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                @endpermission
                                @permission('plantilla-edit')
                                  <li>
                                    <a class="dropdown-item" href="{{ route('admin.plantilla.edit', ['plantilla' => $plantilla->id]) }}">
                                      <i class="fa fa-pencil"></i> Editar
                                    </a>
                                  </li>
                                @endpermission
                              </ul>
                            </div>
                          @endpermission
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
          @permission('plantilla-variable-index|plantilla-variable-view')
            <div id="tab-3" class="tab-pane">
              <div class="panel-body">
                <div class="mb-3 text-right">
                  @permission('plantilla-variable-create')
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.variable.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Variable</a>
                  @endpermission
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
                  <tbody>
                    @foreach($variables as $variable)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $variable->nombre }}</td>
                        <td class="text-center">{{ $variable->tipo() }}</td>
                        <td class="text-center">{{ $variable->variable }}</td>
                        <td class="text-center">
                          @if(!$variable->isStatic())
                            @permission('plantilla-variable-edit|plantilla-variable-delete')
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  @permission('plantilla-variable-edit')
                                    <li>
                                      <a class="dropdown-item" href="{{ route('admin.variable.edit', ['variable' => $variable->id]) }}">
                                        <i class="fa fa-pencil"></i> Editar
                                      </a>
                                    </li>
                                  @endpermission
                                  @permission('plantilla-variable-delete')
                                    <li>
                                      <a class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#delModal" data-url="{{ route('admin.variable.destroy', ['variable' => $variable->id]) }}" data-target="#delProductoModal">
                                        <i class="fa fa-times" aria-hidden="true"></i> Eliminar
                                      </a>
                                    </li>
                                  @endpermission
                                </ul>
                              </div>
                            @endpermission
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endpermission
        </div>
      </div>
    </div>
  </div>

  @permission('plantilla-variable-delete')
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
  @endpermission
@endsection

@section('script')
  @permission('plantilla-variable-delete')
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
  @endpermission
@endsection
