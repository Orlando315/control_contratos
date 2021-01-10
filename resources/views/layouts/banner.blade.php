@if(Auth::user()->isEmpleado() && Auth::user()->hasRole('empleado') && Auth::user()->empresa->configuracion->hasActiveTerminos() && Auth::user()->haventAcceptedTerms())
  <div class="terms-banner fixed-bottom p-3" style="display: none;">
    Al seleccionar ACEPTAR, aceptas los <a class="text-primary" href="{{ route('terminos') }}" target="_blank">Terminos y Condiciones</a> establecidos por <strong>{{ Auth::user()->empresa->nombre }}</strong>. <button class="btn btn-outline btn-default btn-xs btn-accept-terms" role="button" type="button">ACEPTAR</button>
  </div>
@endif
