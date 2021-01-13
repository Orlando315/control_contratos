@role('empleado')
  @foreach(Auth::user()->sueldos(true)->get() as $sueldo)
    <div class="alert alert-info alert-important" role="alert">
      <h4><i class="icon fa fa-money"></i> Sueldo</h4>
      Confirmar el pago de <strong>{{ $sueldo->sueldoLiquido() }} </strong> por el mes de <strong>{{ $sueldo->mesPagado() }}</strong><br>
      <button class="btn btn-success btn-sm mt-2 btn-confirmar" type="button" data-path="{{ route('sueldos.confirmar', ['sueldo' => $sueldo->id]) }}">
        <i class="fa fa-check"></i> Confirmar
      </button>
    </div>
  @endforeach
@endrole
