@extends('layouts.app')

@section('title', 'Carpeta')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Carpetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('archivo.index') }}">Archivo</a></li>
        <li class="breadcrumb-item active"><strong>Carpeta</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ $carpeta->backArchivoUrl }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @permission('archivo-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.archivo.carpeta.edit', ['carpeta' => $carpeta->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('archivo-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $carpeta->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Adjuntos</b>
              <span class="pull-right">{{ $carpeta->documentos->count() }}</span>
            </li>
            @permission('archivo-index|archivo-create|archivo-edit')
              <li class="list-group-item">
                <b>Pública</b>
                <span class="pull-right">{!! $carpeta->isPublic(true) !!}</span>
              </li>
            @endpermission
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $carpeta->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9 p-0">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-paperclip"></i> Documentos adjuntos</a></li>
          @permission('archivo-create|archivo-edit')
            <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-users"></i> Usuarios con acceso</a></li>
          @endpermission
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              @permission('archivo-create')
                <div class="mb-3 text-right">
                  <a class="btn btn-warning btn-xs" href="{{ route('admin.archivo.carpeta.create', ['carpeta' => $carpeta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
                  <a class="btn btn-primary btn-xs" href="{{ route('admin.archivo.documento.create', ['carpeta' => $carpeta->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
                </div>
              @endpermission

              <div class="row icons-box icons-folder">
                @foreach($carpeta->subcarpetas as $subcarpeta)
                  <div class="col-md-3 col-xs-4 infont mb-3">
                    @permission('archivo-create|archivo-edit|archivo-delete')
                      <span class="pull-left text-muted">
                        <i class="fa {{ $subcarpeta->isPublic() ? 'fa-unlock' : 'fa-lock' }} block" aria-hidden="true" title="{{ $subcarpeta->isPublic() ? 'Público' : 'Privado' }}" style="font-size: 12px"></i>
                      </span>
                    @endpermission
                    <a href="{{ route('archivo.carpeta.show', ['carpeta' => $subcarpeta->id]) }}">
                      <i class="fa fa-folder" aria-hidden="true"></i>
                      <p class="m-0">{{ $subcarpeta->nombre }}</p>
                    </a>
                  </div>
                @endforeach
              </div>
              @if(count($carpeta->subcarpetas) > 0)
                <hr class="hr-line-dashed">
              @endif
              <div class="row">
                @forelse($carpeta->documentos as $documento)
                  <div id="adjunto-{{ $documento->id }}" class="col-md-3 col-sm-4 col-xs-6 mb-3">
                    <div class="file m-0 file-options p-2">
                      @if($documento->isPdf() || Auth::user()->hasPermission('archivo-edit|archivo-delete'))
                        <div class="float-right dropdown position-absolute" style="top: 0; right: 0;">
                          <button class="dropdown-toggle btn-white px-2" data-toggle="dropdown" aria-expanded="false"></button>
                          <ul class="dropdown-menu m-t-xs" x-placement="bottom-start" style="position: absolute; top: 21px; left: 0px; will-change: top, left;">
                            @if($documento->isPdf())
                              <li>
                                <a title="Ver PDF" href="#" data-toggle="modal" data-target="#pdfModal" data-url="{{ $documento->asset_url }}">
                                  <i class="fa fa-eye" aria-hidden="true"></i> Ver PDF
                                </a>
                              </li>
                            @endif
                            @permission('archivo-view')
                              <li>
                                <a title="Ver detalles" href="{{ route('admin.archivo.documento.show', ['documento' => $documento->id]) }}">
                                  <i class="fa fa-search" aria-hidden="true"></i> Ver detalles
                                </a>
                              </li>
                            @endpermission
                            @permission('archivo-edit')
                              <li>
                                <a title="Editar documento" href="{{ route('admin.archivo.documento.edit', ['documento' => $documento->id]) }}">
                                  <i class="fa fa-pencil" aria-hidden="true"></i> Editar
                                </a>
                              </li>
                            @endpermission
                            @permission('archivo-delete')
                              <li>
                                <a class="btn-delete-file" type="button" title="Eliminar archivo" data-url="{{ route('admin.archivo.documento.destroy', ['documento' => $documento->id]) }}" data-toggle="modal" data-target="#delFileModal">
                                  <i class="fa fa-times" aria-hidden="true"></i> Eliminar
                                </a>
                              </li>
                            @endpermission
                          </ul>
                        </div>
                      @endif
                      @permission('archivo-create|archivo-edit|archivo-delete')
                        <span class="pull-left text-muted">
                          <i class="fa {{ $documento->isPublic() ? 'fa-unlock' : 'fa-lock' }} block" aria-hidden="true" title="{{ $documento->isPublic() ? 'Público' : 'Privado' }}" style="font-size: 12px"></i>
                        </span>
                      @endpermission
                      <a href="{{ route('archivo.documento.download', ['documento' => $documento->id]) }}">
                        <div class="icon px-0">
                          <i class="fa {{ $documento->getIconByMime() }}"></i>
                        </div>
                        <div class="file-name p-0 pt-2">
                          {{ $documento->nombre }}
                          @if($documento->observacion)
                            <br>
                            <small>{{ $documento->observacion }}</small>
                          @endif
                          @if($documento->vencimiento)
                            <br>
                            <small><strong>Vencimiento:</strong> {{ $documento->vencimiento }}</small>
                          @endif
                        </div>
                      </a>
                    </div>
                  </div>
                @empty
                  <div class="col-12">
                    <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
          @permission('archivo-create|archivo-edit')
            <div id="tab-2" class="tab-pane">
              <div class="panel-body">
                <table class="table data-table table-bordered table-hover table-sm w-100">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombres</th>
                      <th class="text-center">Apellidos</th>
                      <th class="text-center">RUT</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($carpeta->archivoUsers as $usuario)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $usuario->nombres }}</td>
                        <td>@nullablestring($usuario->apellidos)</td>
                        <td>{{ $usuario->rut }}</td>
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

  <div id="delFileModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="delete-file-form" action="#" method="POST">
          @method('DELETE')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delFileModalLabel">Eliminar documento</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @permission('archivo-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.archivo.carpeta.destroy', ['carpeta' => $carpeta->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Carpeta</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Carpeta?</h4>
              <p class="text-center">Tambien se eliminarán todas las Carpetas y Documentos adjuntos contenidos en ella.</p>

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

  @permission('archivo-delete')
    <script type="text/javascript">
      $(document).ready(function(){
        $('#delFileModal').on('show.bs.modal', function(e){
          var button = $(e.relatedTarget),
              action = button.data('url');

          $('#delete-file-form').attr('action', action);
        });
      });
    </script>
  @endpermission
@endsection
