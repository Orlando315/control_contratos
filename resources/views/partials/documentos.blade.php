<div id="adjunto-{{ $documento->id }}" class="col-md-3 col-sm-4 col-xs-6 mb-3">
  <div class="file m-0 file-options p-2">
    @permission('empleado-edit')
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
          @if($edit)
            <li>
              <a title="Editar documento" href="{{ route('admin.documento.edit', ['documento' => $documento->id]) }}">
                <i class="fa fa-pencil" aria-hidden="true"></i> Editar
              </a>
            </li>
          @endif
          <li>
            <a class="btn-delete-file" type="button" title="Eliminar archivo" data-url="{{ route('admin.documento.destroy', ['documento' => $documento->id]) }}" data-toggle="modal" data-target="#delFileModal">
              <i class="fa fa-times" aria-hidden="true"></i> Eliminar
            </a>
          </li>
        </ul>
      </div>
    @endpermission
    <a href="{{ $documento->download }}">
      @if($documento->isRequisito() || $documento->isTypeEmpleado())
        <span class="pull-left text-muted">
          @if($documento->isRequisito())
            <i class="fa fa-asterisk block" aria-hidden="true" title="Requisito" style="font-size: 12px"></i>
          @endif
          @if(!Auth::user()->hasRole('empleado') && $documento->isTypeEmpleado() && $documento->isVisible())
            <i class="fa fa-eye block" aria-hidden="true" title="Visible para el Empleado" style="font-size: 12px"></i>
          @endif
        </span>
      @endif

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
