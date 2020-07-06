<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
  <div class="navbar-header">
    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
  </div>
  <ul class="nav navbar-top-links navbar-right">
    <li {{ Auth::user()->empresa->logo? '' : 'style="padding: 20px"' }}>
      @if(Auth::user()->empresa->logo)
        <img src="{{ asset('images/logo-small.png') }}" class="user-image" alt="Logo Vertrag" style="max-height: 40px">
      @else
        <span class="m-r-sm text-muted welcome-message">Bienvenidos a <strong>{{ config('app.name') }}</strong>.</span>
      @endif
    </li>
    <li>
      <a href="{{ route('login.logout') }}">
        <i class="fa fa-sign-out"></i> Salir
      </a>
    </li>
  </ul>
</nav>
