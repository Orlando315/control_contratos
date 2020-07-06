<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header">
        <div class="dropdown profile-element">
          <div class="menu-logo-empresa">
            <img src="{{ Auth::user()->empresa->logo ? Auth::user()->empresa->logo_url : asset('images/logo-small-white.jpg') }}" class="user-image" alt="Logo empresa">
          </div>
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <span class="block m-t-xs font-bold">{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</span>
            <span class="text-muted text-xs block">{{ Auth::user()->usuario }} <b class="caret"></b></span>
          </a>
          <ul class="dropdown-menu animated fadeInRight m-t-xs">
            <li><a class="dropdown-item" href="{{ route('usuarios.perfil') }}">Perfil</a></li>
            <li class="dropdown-divider"></li>
            <li>
              <form action="{{ route('login.logout') }}" method="POST">
                {{ csrf_field() }}
                <button class="btn btn-link dropdown-item w-100" type="submit">Salir</button>
              </form>
            </li>
          </ul>
        </div>
        <div class="logo-element">
          IN+
        </div>
      </li>

      <li>
        <a href="{{ route('dashboard') }}">
          <i class="fa fa-home"></i> <span class="nav-label">Inicio</span>
        </a>
      </li>

      @if(Auth::user()->tipo <= 2)
        <li>
          <a href="#">
            <i class="fa fa-clipboard"></i>
            <span class="nav-label">Contratos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('contratos.index') }}">Ver contratos</a></li>
            @if(Auth::user()->tipo == 1)
              <li><a href="{{ route('contratos.create') }}">Agregar contrato</a></li>
            @endif
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-file-text-o"></i>
            <span class="nav-label">Documentos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('plantilla.documento.index') }}">Ver documentos</a></li>
            <li><a href="{{ route('plantilla.documento.create') }}">Agregar documento</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-object-group"></i>
            <span class="nav-label">Plantillas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('plantilla.index') }}">Ver plantillas</a></li>
            <li><a href="{{ route('plantilla.create') }}">Agregar plantilla</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-users"></i>
            <span class="nav-label">Usuarios</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('usuarios.index') }}">Ver usuarios</a></li>
            <li><a href="{{ route('usuarios.create') }}">Agregar usuario</a></li>
          </ul>
        </li>
        <li>
          <a href="{{ route('empleados.index') }}">
            <i class="fa fa-address-card"></i> <span class="nav-label">Empleados</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-level-up"></i>
            <span class="nav-label">Anticipos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('anticipos.index') }}">Ver anticipos</a></li>
            <li><a href="{{ route('anticipos.individual') }}">Anticipo Individual</a></li>
            <li><a href="{{ route('anticipos.masivo') }}">Anticipo Masivo</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-tags"></i>
            <span class="nav-label">Etiquetas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('etiquetas.index') }}">Ver etiquetas</a></li>
            <li><a href="{{ route('etiquetas.create') }}">Agregar etiqueta</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-file"></i>
            <span class="nav-label">Facturas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('facturas.index') }}">Ver facturas</a></li>
            <li><a href="{{ route('facturas.create') }}">Agregar factura</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-credit-card"></i>
            <span class="nav-label">Gastos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('gastos.index') }}">Ver gastos</a></li>
            <li><a href="{{ route('gastos.create') }}">Agregar gasto</a></li>
          </ul>
        </li>
      @endif

      @if(Auth::user()->tipo <= 3)
        <li>
          <a href="#">
            <i class="fa fa-cubes"></i>
            <span class="nav-label">Inventarios</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('inventarios.index') }}">Ver inventarios</a></li>
            <li><a href="{{ route('inventarios.create') }}">Agregar inventario</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-car"></i>
            <span class="nav-label">Transportes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('transportes.index') }}">Ver transportes</a></li>
            @if(Auth::user()->tipo <= 2)
            <li><a href="{{ route('transportes.create') }}">Agregar transporte</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(Auth::user()->tipo <= 2)
        <li>
          <a href="#">
            <i class="fa fa-area-chart"></i>
            <span class="nav-label">Reportes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('reportes.general.index') }}">General</a></li>
            <li><a href="{{ route('reportes.inventarios.index') }}">Inventarios</a></li>
            <li><a href="{{ route('reportes.facturas.index') }}">Facturas</a></li>
            <li><a href="{{ route('reportes.eventos.index') }}">Eventos</a></li>
            <li><a href="{{ route('reportes.sueldos.index') }}">Sueldos</a></li>
            <li><a href="{{ route('reportes.anticipos.index') }}">Anticipos</a></li>
            <li><a href="{{ route('reportes.transportes.index') }}">Transportes</a></li>
            <li><a href="{{ route('reportes.reemplazos.index') }}">Reemplazos</a></li>
          </ul>
        </li>
      @endif
    </ul>
  </div>
</nav>
