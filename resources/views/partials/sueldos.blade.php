@foreach(Auth::user()->sueldos(true)->get() as $sueldo)
<div class="alert alert-success fade in alert-important" role="alert">
  <h4><i class="icon fa fa-money"></i> Sueldo</h4>
  <p>Confirmar el pago de <strong>{{ $sueldo->sueldoLiquido() }} </strong> por el mes de <strong>{{ $sueldo->mesPagado() }}</strong></p>
  <p>
    <button data-path="{{ route('sueldos.confirmar', ['sueldo' => $sueldo->id]) }}" type="button" class="btn btn-sm btn-flat bg-purple btn-confirmar">Confirmar</button>
  </p>
</div>
@endforeach