@foreach(Auth::user()->entregasPendientes()->get() as $entrega)
  <div class="alert alert-warning alert-important" role="alert">
    <h4><i class="icon fa fa-info"></i> Entrega de inventario</h4>
    Confirmar entrega de <strong>{{ $entrega->cantidad }} {{ $entrega->inventario->nombre }}</strong> el día <strong>{{ $entrega->created_at->format('d-m-Y H:i:s') }}</strong><br>
    <button class="btn btn-success btn-sm mt-2 btn-confirmar" type="button" data-path="{{ route('entregas.update', ['entrega' => $entrega->id]) }}">
      <i class="fa fa-check"></i> Confirmar
    </button>
  </div>
@endforeach
