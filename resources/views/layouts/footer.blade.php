<div class="footer">
  <div>
    <strong>Copyright</strong> {{ env('APP_NAME') }} &copy; {{ date('Y') }}
    @if(Auth::user()->empresa->configuracion->hasActiveTerminos())
       | <a class="text-primary" href="{{ route('terminos') }}" target="_blank">Terminos y Condiciones</a>
    @endif
  </div>
</div>
