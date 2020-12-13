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
            <li><a class="dropdown-item" href="{{ route('perfil') }}">Perfil</a></li>
            <li class="dropdown-divider"></li>
            <li>
              <form action="{{ route('login.logout') }}" method="POST">
                @csrf

                <button class="btn btn-link dropdown-item w-100" type="submit">Salir</button>
              </form>
            </li>
          </ul>
        </div>
        <div class="logo-element">
          <img src="{{ asset('images/icon-white.png') }}" alt="V" style="max-height: 32px">
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
            <li><a href="{{ route('admin.contratos.index') }}">Ver contratos</a></li>
            @if(Auth::user()->tipo == 1)
              <li><a href="{{ route('admin.contratos.create') }}">Agregar contrato</a></li>
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
            <li><a href="{{ route('admin.plantilla.documento.index') }}">Ver documentos</a></li>
            <li><a href="{{ route('admin.plantilla.documento.create') }}">Agregar documento</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-users"></i>
            <span class="nav-label">Usuarios</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.usuarios.index') }}">Ver usuarios</a></li>
            <li><a href="{{ route('admin.usuarios.create') }}">Agregar usuario</a></li>
          </ul>
        </li>
        <li>
          <a href="{{ route('admin.empleados.index') }}">
            <i class="fa fa-address-card"></i> <span class="nav-label">Empleados</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.solicitud.index') }}">
            <i class="fa fa-archive"></i> <span class="nav-label">Solicitudes</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-level-up"></i>
            <span class="nav-label">Anticipos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.anticipos.index') }}">Ver anticipos</a></li>
            <li><a href="{{ route('admin.anticipos.individual') }}">Anticipo Individual</a></li>
            <li><a href="{{ route('admin.anticipos.masivo') }}">Anticipo Masivo</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-tags"></i>
            <span class="nav-label">Etiquetas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.etiquetas.index') }}">Ver etiquetas</a></li>
            <li><a href="{{ route('admin.etiquetas.create') }}">Agregar etiqueta</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-file"></i>
            <span class="nav-label">Facturas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.facturas.index') }}">Ver facturas</a></li>
            <li><a href="{{ route('admin.facturas.create') }}">Agregar factura</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-credit-card"></i>
            <span class="nav-label">Gastos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.gastos.index') }}">Ver gastos</a></li>
            <li><a href="{{ route('admin.gastos.create') }}">Agregar gasto</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-dollar"></i>
            <span class="nav-label">Ventas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.cotizacion.index') }}">Ver cotizaciones</a></li>
            <li><a href="{{ route('admin.facturacion.index') }}">Ver facturaciones</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-plus-square"></i>
            <span class="nav-label">Compras</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.compra.index') }}">Ver ordenes de compra</a></li>
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
            <li><a href="{{ route('admin.inventarios.index') }}">Ver inventarios</a></li>
            <li><a href="{{ route('admin.inventarios.create') }}">Agregar inventario</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-car"></i>
            <span class="nav-label">Transportes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.transportes.index') }}">Ver transportes</a></li>
            @if(Auth::user()->tipo <= 2)
            <li><a href="{{ route('admin.transportes.create') }}">Agregar transporte</a></li>
            @endif
          </ul>
        </li>
      @endif

      @if(Auth::user()->tipo == 1)
        <li>
          <a href="#">
            <i class="fa fa-user-circle"></i>
            <span class="nav-label">Clientes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.cliente.index') }}">Ver clientes</a></li>
            <li><a href="{{ route('admin.cliente.create', ['type' => 'persona']) }}">Agregar persona</a></li>
            <li><a href="{{ route('admin.cliente.create', ['type' => 'empresa']) }}">Agregar empresa</a></li>
          </ul>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-briefcase"></i>
            <span class="nav-label">Proveedores</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.proveedor.index') }}">Ver proveedores</a></li>
            <li><a href="{{ route('admin.proveedor.create', ['type' => 'persona']) }}">Agregar persona</a></li>
            <li><a href="{{ route('admin.proveedor.create', ['type' => 'empresa']) }}">Agregar empresa</a></li>
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
            <li><a href="{{ route('admin.reportes.general.index') }}">General</a></li>
            <li><a href="{{ route('admin.reportes.inventarios.index') }}">Inventarios</a></li>
            <li><a href="{{ route('admin.reportes.facturas.index') }}">Facturas</a></li>
            <li><a href="{{ route('admin.reportes.eventos.index') }}">Eventos</a></li>
            <li><a href="{{ route('admin.reportes.sueldos.index') }}">Sueldos</a></li>
            <li><a href="{{ route('admin.reportes.anticipos.index') }}">Anticipos</a></li>
            <li><a href="{{ route('admin.reportes.transportes.index') }}">Transportes</a></li>
            <li><a href="{{ route('admin.reportes.reemplazos.index') }}">Reemplazos</a></li>
          </ul>
        </li>
      @endif

      @if(Auth::user()->isEmpleado())
        <li>
          <a href="{{ route('anticipos.create') }}">
            <i class="fa fa-level-up"></i> <span class="nav-label">Solicitar anticipo</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-archive"></i>
            <span class="nav-label">Solicitudes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('solicitud.index') }}">Ver solicitudes</a></li>
            <li><a href="{{ route('solicitud.create') }}">Agregar solicitud</a></li>
          </ul>
        </li>
      @endif
    </ul>
  </div>
</nav>
