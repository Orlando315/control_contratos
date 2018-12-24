@foreach(Auth::user()->entregasPendientes()->get() as $entrega)
<div class="alert bg-teal fade in alert-important" role="alert">
  <h4><i class="icon fa fa-info"></i> Entrega de inventario</h4>
  <p>Confirmar entrega de <strong>{{ $entrega->cantidad }} {{ $entrega->inventario->nombre }}</strong> el día <strong>{{ $entrega->created_at }}</strong></p>
  <p>
    <button data-id="{{ $entrega->id }}" type="button" class="btn btn-sm btn-flat bg-purple btn-confirmar-entrega">Confirmar</button>
  </p>
</div>
@endforeach