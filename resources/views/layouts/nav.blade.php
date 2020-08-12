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
    @if(Auth::user()->isAdmin())
      <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
          <i class="fa fa-level-up"></i>  <span class="label label-success">{{ count($notificationSolicitudesAnticiposPendientes) }}</span>
        </a>
        <ul class="dropdown-menu dropdown-messages">
          @foreach($notificationSolicitudesAnticiposPendientes as $notificationSolicitud)
            <li>
              <a class="dropdown-item" href="{{ route('admin.anticipos.show', ['anticipo' => $notificationSolicitud->id]) }}">
                <div class="dropdown-messages-box">
                  <div class="media-body">
                    <strong>{{ $notificationSolicitud->empleado->usuario->nombre() }}</strong> ha solicitado un anticipo de <strong>{{ $notificationSolicitud->anticipo() }}</strong>. <br>
                    <small class="text-muted">{{ optional($notificationSolicitud->fecha)->format('d-m-Y H:i:s') }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li class="dropdown-divider"></li>
          @endforeach
        </ul>
      </li>
    @endif
    <li>
      <a href="{{ route('login.logout') }}">
        <i class="fa fa-sign-out"></i> Salir
      </a>
    </li>
  </ul>
</nav>
