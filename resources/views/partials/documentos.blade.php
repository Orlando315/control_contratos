<div id="adjunto-{{ $documento->id }}" class="col-md-3 col-sm-4 col-xs-6 mb-3">
  <div class="file m-0 file-options">
    @if($documento->isRequisito())
      <span class="pull-left text-muted" title="Requisito"><i class="fa fa-asterisk" aria-hidden="true"></i></span>
    @endif
    <div class="float-right dropdown">
      <button data-toggle="dropdown" class="dropdown-toggle btn-white" aria-expanded="false"></button>
      <ul class="dropdown-menu m-t-xs" x-placement="bottom-start" style="position: absolute; top: 21px; left: 0px; will-change: top, left;">
        @if($documento->isPdf())
          <li>
            <a title="Ver PDF" href="#" data-toggle="modal" data-target="#pdfModal" data-url="{{ $documento->download_url }}">
              <i class="fa fa-eye" aria-hidden="true"></i> Ver PDF
            </a>
          </li>
        @endif
        @if($edit)
          <li>
            <a title="Editar documento" href="{{ route('admin.documentos.edit', ['documento' => $documento->id]) }}">
              <i class="fa fa-pencil" aria-hidden="true"></i> Editar
            </a>
          </li>
        @endif
        <li>
          <a class="btn-delete-file" type="button" title="Eliminar archivo" data-url="{{ route('admin.documentos.destroy', ['documento' => $documento->id]) }}" data-toggle="modal" data-target="#delFileModal">
            <i class="fa fa-times" aria-hidden="true"></i> Eliminar
          </a>
        </li>
      </ul>
    </div>
    <a href="{{ route('admin.documentos.download', ['documento' => $documento->id]) }}">
      <span class="corner"></span>

      <div class="icon">
        <i class="fa {{ $documento->getIconByMime() }}"></i>
      </div>
      <div class="file-name">
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
