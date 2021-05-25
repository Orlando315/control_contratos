<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
  <div class="navbar-header">
    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
    <a class="minimalize-styl-2 btn btn-default" href="{{ route('welcome') }}"><i class="fa fa-external-link"></i> Pantalla de bienvenida</a>
  </div>
  <ul class="nav navbar-top-links navbar-right">
    <li{{ Auth::user()->empresa->logo ? '' : ' style="padding: 20px"' }}>
      @if(Auth::user()->empresa->logo)
        <img src="{{ asset('images/logo-small.png') }}" class="user-image" alt="Logo Vertrag" style="max-height: 40px">
      @else
        <span class="m-r-sm text-muted welcome-message">Bienvenidos a <strong>{{ config('app.name') }}</strong>.</span>
      @endif
    </li>
    <li>
      <a href="{{ route('ayuda.index') }}" title="Ayuda"><i class="fa fa-question-circle"></i></a>
    </li>
    @if(Auth::user()->isAdmin())
      <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Eventos de Empleados pendientes">
          <i class="fa fa-address-card"></i>
          @if(count($notificationEmpleadoEventosPendientes) > 0)
            <span class="label label-success">{{ count($notificationEmpleadoEventosPendientes) }}</span>
          @endif
        </a>
        <ul class="dropdown-menu dropdown-messages">
          @foreach($notificationEmpleadoEventosPendientes as $notificationEmpleadoEvento)
            <li>
              <a class="dropdown-item px-2" href="{{ route('admin.empleado.show', ['empleado' => $notificationEmpleadoEvento->empleado_id]) }}">
                <div class="dropdown-messages-box">
                  <div class="media-body">
                    <p class="notification-description"><strong>{{ $notificationEmpleadoEvento->empleado->usuario->nombre() }}</strong> ha solicitado: <strong>{{ $notificationEmpleadoEvento->tipo() }}</strong>
                    ({{ $notificationEmpleadoEvento->inicio}}{{ $notificationEmpleadoEvento->fin ? ' hasta '.$notificationEmpleadoEvento->fin : '' }}).</p>
                    <small class="text-muted">{{ optional($notificationEmpleadoEvento->created_at)->format('d-m-Y H:i:s') }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li class="dropdown-divider"></li>
          @endforeach
        </ul>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Solicitudes pendientes">
          <i class="fa fa-archive"></i>
          @if(count($notificationSolicitudesPendientes) > 0)
            <span class="label label-danger">{{ count($notificationSolicitudesPendientes) }}</span>
          @endif
        </a>
        <ul class="dropdown-menu dropdown-messages">
          @foreach($notificationSolicitudesPendientes as $notificationSolicitud)
            <li>
              <a class="dropdown-item px-2" href="{{ route('admin.solicitud.edit', ['solicitud' => $notificationSolicitud->id]) }}">
                <div class="dropdown-messages-box">
                  <div class="media-body">
                    <p class="notification-description"><strong>{{ $notificationSolicitud->empleado->usuario->nombre() }}</strong> ha solicitado: <strong>{{ $notificationSolicitud->tipo() }}</strong>.</p>
                    <small class="text-muted">{{ optional($notificationSolicitud->fecha)->format('d-m-Y H:i:s') }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li class="dropdown-divider"></li>
          @endforeach
        </ul>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Solicitudes de Anticipos">
          <i class="fa fa-level-up"></i>
          @if(count($notificationSolicitudesAnticiposPendientes) > 0)
            <span class="label label-success">{{ count($notificationSolicitudesAnticiposPendientes) }}</span>
          @endif
        </a>
        <ul class="dropdown-menu dropdown-messages">
          @foreach($notificationSolicitudesAnticiposPendientes as $notificationSolicitudAnticipo)
            <li>
              <a class="dropdown-item px-2" href="{{ route('admin.anticipo.show', ['anticipo' => $notificationSolicitudAnticipo->id]) }}">
                <div class="dropdown-messages-box">
                  <div class="media-body">
                    <p class="notification-description"><strong>{{ $notificationSolicitudAnticipo->empleado->usuario->nombre() }}</strong> ha solicitado un anticipo de <strong>{{ $notificationSolicitudAnticipo->anticipo() }}</strong>.</p>
                    <small class="text-muted">{{ optional($notificationSolicitudAnticipo->fecha)->format('d-m-Y H:i:s') }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li class="dropdown-divider"></li>
          @endforeach
        </ul>
      </li>
    @endif
    <li class="dropdown">
      <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" title="Requerimiento de Materiales">
        <i class="fa fa-list"></i>
        @if(count($notificationRequerimientosMaterialesPendientes) > 0)
          <span class="label label-success">{{ count($notificationRequerimientosMaterialesPendientes) }}</span>
        @endif
      </a>
      <ul class="dropdown-menu dropdown-messages">
        @foreach($notificationRequerimientosMaterialesPendientes as $notificationRequerimiento)
          <li>
            <a class="dropdown-item px-2" href="{{ route('requerimiento.material.show', ['requerimiento' => $notificationRequerimiento->requerimiento_id]) }}">
              <div class="dropdown-messages-box">
                <div class="media-body">
                  <p class="notification-description"><strong>{{ $notificationRequerimiento->requerimiento->userSolicitante->nombre() }}</strong> ha solicitado <strong>{{ $notificationRequerimiento->requerimiento->productos_count }}</strong> productos.</p>
                  <small class="text-muted">{{ optional($notificationRequerimiento->requerimiento->created_at)->format('d-m-Y H:i:s') }}</small>
                </div>
              </div>
            </a>
          </li>
          <li class="dropdown-divider"></li>
        @endforeach
      </ul>
    </li>
    <li>
      <a href="{{ route('login.logout') }}">
        <i class="fa fa-sign-out"></i> Salir
      </a>
    </li>
  </ul>
</nav>
