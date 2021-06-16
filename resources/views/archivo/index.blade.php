@extends('layouts.app')

@section('title', 'Archivos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Archivos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><strong>Archivo</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="ibox">
    <div class="ibox-title">
      <h5>Archivos</h5>

      <div class="ibox-tools">
        @permission('archivo-create')
          <a class="btn btn-warning btn-xs" href="{{ route('admin.archivo.carpeta.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
          <a class="btn btn-primary btn-xs" href="{{ route('admin.archivo.documento.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
        @endpermission
      </div>
    </div>
    <div class="ibox-content">
      <div class="row icons-box icons-folder">
        @foreach($carpetas as $carpeta)
          <div class="col-md-2 col-xs-4 infont mb-3">
            @permission('archivo-create|archivo-edit|archivo-delete')
              <span class="pull-left text-muted">
                <i class="fa {{ $carpeta->isPublic() ? 'fa-unlock' : 'fa-lock' }} block" aria-hidden="true" title="{{ $carpeta->isPublic() ? 'Público' : 'Privado' }}" style="font-size: 12px"></i>
              </span>
            @endpermission
            <a href="{{ route('archivo.carpeta.show', ['carpeta' => $carpeta->id]) }}">
              <i class="fa fa-folder" aria-hidden="true"></i>
              <p class="m-0">{{ $carpeta->nombre }}</p>
            </a>
          </div>
        @endforeach
      </div>

      @if(count($carpetas) > 0)
        <hr class="hr-line-dashed">
      @endif

      <div class="row">
        @forelse($documentos as $documento)
          <div id="adjunto-{{ $documento->id }}" class="col-md-2 col-sm-4 col-xs-6 mb-3">
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
              <a href="{{ route('archivo.documento.download', ['documento' => $documento->id]) }}">
                @permission('archivo-create|archivo-edit|archivo-delete')
                  <span class="pull-left text-muted">
                    <i class="fa {{ $documento->isPublic() ? 'fa-unlock' : 'fa-lock' }} block" aria-hidden="true" title="{{ $documento->isPublic() ? 'Público' : 'Privado' }}" style="font-size: 12px"></i>
                  </span>
                @endpermission
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
    </div><!-- /.box-body -->
  </div>

  @permission('archivo-delete')
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
