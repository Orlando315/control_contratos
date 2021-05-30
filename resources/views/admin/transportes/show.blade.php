@extends('layouts.app')

@section('title', 'Transporte')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Transportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.transporte.index') }}">Transportes</a></li>
        <li class="breadcrumb-item active"><strong>Transporte</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('transporte-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.transporte.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('transporte-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.transporte.edit', ['transporte' => $transporte->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('transporte-delete')
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
              <b>Patente</b>
              <span class="pull-right">{{ $transporte->patente }}</span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($transporte->vehiculo)</span>
            </li>
            <li class="list-group-item">
              <b>Marca</b>
              <span class="pull-right">@nullablestring($transporte->marca)</span>
            </li>
            <li class="list-group-item">
              <b>Modelo</b>
              <span class="pull-right">@nullablestring($transporte->modelo)</span>
            </li>
            <li class="list-group-item">
              <b>Color</b>
              <span class="pull-right">@nullablestring($transporte->color)</span>
            </li>
            <li class="list-group-item">
              <b>Faenas</b>
              <span class="pull-right">{!! $transporte->faenasTags() !!}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $transporte->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
    <div class="col-md-9">
      <div class="tabs-container mb-3">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-13" data-toggle="tab"><i class="fa fa-asterisk"></i> Requisitos</a></li>
          <li><a class="nav-link" href="#tab-11" data-toggle="tab"><i class="fa fa-paperclip"></i> Adjuntos</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-13">
            <div class="panel-body">
              <div class="row">
                @foreach($transporte->contratos as $contrato)
                  <div class="col-lg-4">
                    <div class="ibox m-2 m-lg-0">
                      <div class="ibox-title">
                        <h5>{{ $contrato->contrato->nombre }}</h5>
                        <div class="ibox-tools">
                          <a class="collapse-link" href="#">
                            <i class="fa fa-chevron-up"></i>
                          </a>
                        </div>
                      </div>
                      @forelse($transporte->requisitosWithDocumentos($contrato->contrato) as $requisito)
                        <div class="ibox-content p-2">
                          <div class="row">
                            <div class="col-9">
                              <i class="fa {{ $requisito->documento ? 'fa-check-square text-primary' : 'fa-square-o text-muted' }}"></i>
                              @if($requisito->documento)
                                <a href="{{ $requisito->isFile() ? route('admin.documento.download', ['documento' => $requisito->documento->id]) : route('admin.carpeta.show', ['carpeta' => $requisito->documento->id]) }}">
                                  {!! $requisito->icon() !!} {{ $requisito->nombre }}
                                  @if($requisito->isFile() && $requisito->documento->vencimiento)
                                    <small class="text-muted">- {{ $requisito->documento->vencimiento }}</small>
                                  @endif
                                </a>
                              @else
                                {!! $requisito->icon() !!} {{ $requisito->nombre }}
                              @endif
                            </div>
                            <div class="col-3">
                              @permission('transporte-edit')
                                <div class="btn-group">
                                  <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                  <ul class="dropdown-menu" x-placement="bottom-start">
                                    @if($requisito->documento)
                                      @if($requisito->isFile())
                                        @if($requisito->documento->isPdf())
                                          <li>
                                            <a title="Ver PDF" href="#" data-toggle="modal" data-target="#pdfModal" data-url="{{ $requisito->documento->asset_url }}">
                                              <i class="fa fa-eye" aria-hidden="true"></i> Ver PDF
                                            </a>
                                          </li>
                                        @endif
                                        <li><a class="dropdown-item" href="{{ route('admin.documento.edit', ['documento' => $requisito->documento->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                        <li><a class="dropdown-item text-danger" type="button" title="Eliminar requisito" data-url="{{ route('admin.documento.destroy', ['documento' => $requisito->documento->id]) }}" data-toggle="modal" data-target="#delFileModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</a></li>
                                      @else
                                        <li><a class="dropdown-item" href="{{ route('admin.carpeta.edit', ['carpeta' => $requisito->documento->id]) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                      @endif
                                    @else
                                      <li><a class="dropdown-item" href="{{ $requisito->isFile() ? route('admin.documento.create', ['type' => 'transportes', 'id' => $transporte->id, 'carpeta' => null, 'requisito' => $requisito->id]) : route('admin.carpeta.create', ['type' => 'transportes', 'id' => $transporte->id, 'requisito' => $requisito->id]) }}"><i class="fa fa-plus"></i> Agregar</a></li>
                                    @endif
                                  </ul>
                                </div>
                              @endpermission
                            </div>
                          </div>
                        </div>
                      @empty
                        <div class="ibox-content p-2">
                          <p class="text-muted text-center mb-1">No hay requisitos</p>
                        </div>
                      @endforelse
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-11">
            <div class="panel-body">
              @if($transporte->documentos()->count() < 10 && Auth::user()->hasPermission('transporte-edit'))
                <div class="mb-3">
                  <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => 'transportes', 'id' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.documento.create', ['type' => 'transportes', 'id' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                </div>
              @endif
              <div class="row icons-box icons-folder">
                @foreach($transporte->carpetas()->main()->get() as $carpeta)
                  <div class="col-md-3 col-xs-4 infont mb-3">
                    <a href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}">
                      @if($carpeta->isRequisito())
                        <span class="pull-left text-muted" title="Requisito"><i class="fa fa-asterisk" aria-hidden="true" style="font-size: 12px"></i></span>
                      @endif
                      <i class="fa fa-folder" aria-hidden="true"></i>
                      <p class="m-0">{{ $carpeta->nombre }}</p>
                    </a>
                  </div>
                @endforeach
              </div>

              <hr class="hr-line-dashed">

              <div class="row">
                @forelse($transporte->documentos()->main()->get() as $documento)
                  @include('partials.documentos', ['edit' => true])
                @empty
                  <div class="col-12">
                    <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-clipboard"></i> Contratos</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-user"></i> Supervisores</a></li>
          <li><a class="nav-link" href="#tab-3" data-toggle="tab"><i class="fa fa-file-text-o"></i> Consumos</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              @permission('transporte-edit')
                <div class="text-right mb-3">
                  <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Contrato</button>
                </div>
              @endpermission
              <table class="table table-bordered data-table table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Agregado</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($transporte->contratos as $contrato)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        @permission('contrato-view')
                          <a href="{{ route('admin.contrato.show', ['contrato' => $contrato->contrato_id]) }}">
                            {{ $contrato->contrato->nombre }}
                          </a>
                        @else
                          {{ $contrato->contrato->nombre }}
                        @endpermission
                      </td>
                      <td>{{ $contrato->created_at }}</td>
                      <td>
                        @permission('transporte-edit')
                          <button class="btn btn-danger btn-xs" data-url="{{ route('admin.transporte.contrato.destroy', ['contrato' => $contrato->id]) }}" data-toggle="modal" data-target="#delDataModal" data-type="contrato">
                            <i class="fa fa-times" aria-hidden="true"></i>
                          </button>
                        @endpermission
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <table class="table table-bordered data-table table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">RUT</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transporte->supervisores as $supervisor)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        @permission('user-view')
                          <a href="{{ route('admin.usuario.show', ['usuario' => $supervisor->id]) }}">
                            {{ $supervisor->nombre() }}
                          </a>
                        @else
                          {{ $supervisor->nombre() }}
                        @endpermission
                      </td>
                      <td>{{ $supervisor->rut }}</td>
                      <td class="text-center">
                        @permission('transporte-edit')
                          <button class="btn btn-danger btn-xs" data-url="{{ route('admin.transporte.supervisor.destroy', ['transporte' => $transporte->id, 'supervisor' => $supervisor->id]) }}" data-type="supervisor" data-toggle="modal" data-target="#delDataModal"><i class="fa fa-times" aria-hidden="true"></i></button>
                        @endpermission
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div id="tab-3" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3">
                @permission('transporte-consumo-create')
                  <div class="text-right">
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.consumo.create', ['transporte' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Consumo</a>
                  </div>
                @endpermission
              </div>
              <table id="tableConsumos" class="table table-bordered data-table table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($transporte->consumos as $consumo)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>
                        @permission('contrato-view')
                          <a href="{{ route('admin.contrato.show', ['contrato' => $consumo->contrato_id]) }}">
                            {{ $consumo->contrato->nombre }}
                          </a>
                        @else
                          {{ $consumo->contrato->nombre }}
                        @endpermission
                      </td>
                      <td>{{ $consumo->tipo() }}</td>
                      <td>{{ $consumo->fecha() }}</td>
                      <td>{{ $consumo->valor() }}</td>
                      <td>
                        @permission('transporte-consumo-view')
                          <a class="btn btn-success btn-sm" href="{{ route('admin.consumo.show', ['consumo' => $consumo->id]) }}"><i class="fa fa-search"></i></a>
                        @endpermission
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

  @permission('transporte-edit')
    <div id="addModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.transporte.contrato.store', ['transporte' => $transporte->id]) }}" method="POST">
            @csrf
            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="addModalLabel">Agregar a contrato</h4>
            </div>
            <div id="add-modal-body" class="modal-body">
              <div class="form-group">
                <label for="contrato">Contrato: *</label>
                <select id="contrato" class="form-control" name="contrato" required style="width: 100%">
                  <option value="">Seleccione...</option>
                  @foreach($otherContratos as $otherContrato)
                    <option value="{{ $otherContrato->id }}">{{ $otherContrato->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="delDataModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delDataModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="destroyContrato" action="#" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delDataModalLabel">Eliminar <span class="modal-data-text"></span></h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este <span class="modal-data-text"></span>?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="delFileModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delete-file-form" action="#" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delFileModalLabel">Eliminar Adjunto</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Adjunto?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit" disabled>Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission

  @permission('transporte-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.transporte.destroy', ['transporte' => $transporte->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Transporte</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Transporte?</h4>
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
  @include('partials.preview-pdf')

  @permission('transporte-edit')
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
    <script type="text/javascript">
      $(document).ready( function(){
        $('#contrato').select2({
          dropdownParent: $('#add-modal-body'),
          theme: 'bootstrap4',
          placeholder: 'Seleccione...',
        });

        @permission('transporte-edit')
          $('#delDataModal').on('show.bs.modal', function(e){
            let btn = $(e.relatedTarget),
                action = btn.data('url'),
                type = btn.data('type');

            if(!action){ return false; }

            let title = (type.charAt(0).toUpperCase() + type.slice(1));
            $('.modal-data-text').text(title);
            $('#destroyContrato').attr('action', action);
          });
        @endpermission

        $('#delFileModal').on('show.bs.modal', function(e){
          let button = $(e.relatedTarget),
              url = button.data('url');

          $('#delete-file-form button[type="submit"]').prop('disabled', !url)
          $('#delete-file-form').attr('action', url);
        });
      });
    </script>
  @endpermission
@endsection
