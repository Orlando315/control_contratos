@foreach(Auth::user()->egresos()->pendiente()->get() as $egreso)
  <div class="ibox shadow-sm alert alert-important b-0">
    <div class="ibox-title">
      <i class="icon fa fa-info"></i>
      <strong class="mr-auto m-l-sm">Confirmación de entrega de Inventario</strong>
    </div>
    <div class="ibox-content">
      Confirmar entrega de <strong>{{ $egreso->cantidad() }} {{ $egreso->inventario->nombre }}</strong> el día <strong>{{ $egreso->created_at->format('d-m-Y H:i:s') }}</strong><br>
      <button class="btn btn-primary btn-sm mt-2 btn-confirmar" type="button" data-path="{{ route('inventario.egreso.accept', ['egreso' => $egreso->id]) }}">
        <i class="fa fa-check"></i> Confirmar
      </button>
    </div>
  </div>
@endforeach
