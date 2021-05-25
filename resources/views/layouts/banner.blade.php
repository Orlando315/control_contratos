@if(Auth::user()->isEmpleado() && Auth::user()->hasRole('empleado') && Auth::user()->empresa->configuracion->hasActiveTerminos() && Auth::user()->haventAcceptedTerms())
  <div class="terms-banner fixed-bottom p-3" style="display: none;">
    Al seleccionar ACEPTAR, aceptas los <a class="text-primary" href="{{ route('terminos') }}" target="_blank">Terminos y Condiciones</a> establecidos por <strong>{{ Auth::user()->empresa->nombre }}</strong>. <button class="btn btn-outline btn-default btn-xs btn-accept-terms" role="button" type="button">ACEPTAR</button>
  </div>
@endif

@if(Auth::user()->hasRole('empleado') && Auth::user()->empresa->configuracion->hasActiveCovid19Encuesta() && Auth::user()->haventAnsweredCovid19Today())
  <a href="{{ route('covid19') }}" class="btn btn-danger btn-rounded fixed-bottom btn-covid19" title="Encuesta Covid-19">
    <i class="fa fa-heartbeat"></i>
    </br>
    <small>Covid-19</small>
  </a>
@endif
