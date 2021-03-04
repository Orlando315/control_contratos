<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header">
        <div class="dropdown profile-element">
          <div class="menu-logo-empresa text-center">
            <img src="{{ Auth::user()->empresa->logo ? Auth::user()->empresa->logo_url : asset('images/logo-small-white.jpg') }}" class="user-image" alt="Logo empresa">
          </div>
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <span class="block m-t-xs font-bold">{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</span>
            <span class="text-muted text-xs block">{{ Auth::user()->usuario }} <b class="caret"></b></span>
          </a>
          <ul class="dropdown-menu animated fadeInRight m-t-xs">
            <li><a class="dropdown-item" href="{{ route('perfil') }}">Perfil</a></li>
            @role('developer|superadmin|empresa')
              <li><a class="dropdown-item" href="{{ route('admin.empresa.perfil') }}">Perfil Empresa</a></li>
            @endrole
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

      @permission('contrato-index|contrato-create')
        <li>
          <a href="#">
            <i class="fa fa-clipboard"></i>
            <span class="nav-label">Contratos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('contrato-index')
              <li><a href="{{ route('admin.contratos.index') }}">Ver contratos</a></li>
            @endpermission
            @permission('contrato-create')
              <li><a href="{{ route('admin.contratos.create') }}">Agregar contrato</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('plantilla-documento-index|plantilla-documento-create|plantilla-index|plantilla-variable-index|plantilla-variable-view')
        <li>
          <a href="#">
            <i class="fa fa-file-text-o"></i>
            <span class="nav-label">Documentos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('plantilla-documento-index')
              <li><a href="{{ route('admin.plantilla.documento.index') }}">Ver documentos</a></li>
            @endpermission
            @permission('plantilla-documento-create')
              <li><a href="{{ route('admin.plantilla.documento.create') }}">Agregar documento</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('user-index|user-create')
        <li>
          <a href="#">
            <i class="fa fa-users"></i>
            <span class="nav-label">Usuarios</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('contrato-index')
              <li><a href="{{ route('admin.usuarios.index') }}">Ver usuarios</a></li>
            @endpermission
            @permission('contrato-create')
              <li><a href="{{ route('admin.usuarios.create') }}">Agregar usuario</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('empleado-index')
        <li>
          <a href="{{ route('admin.empleados.index') }}">
            <i class="fa fa-address-card"></i> <span class="nav-label">Empleados</span>
          </a>
        </li>
      @endpermission
      @permission('solicitud-index')
        <li>
          <a href="{{ route('admin.solicitud.index') }}">
            <i class="fa fa-archive"></i> <span class="nav-label">Solicitudes</span>
          </a>
        </li>
      @endpermission
      @permission('requerimiento-material-index')
        <li>
          <a href="{{ route('admin.requerimiento.material.index') }}">
            <i class="fa fa-list-ul"></i> <span class="nav-label">Requerimiento de Materiales</span>
          </a>
        </li>
      @endpermission
      @permission('anticipo-index|anticipo-create')
        <li>
          <a href="#">
            <i class="fa fa-level-up"></i>
            <span class="nav-label">Anticipos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('anticipo-index')
              <li><a href="{{ route('admin.anticipos.index') }}">Ver anticipos</a></li>
            @endpermission
            @permission('anticipo-create')
              <li><a href="{{ route('admin.anticipos.individual') }}">Anticipo Individual</a></li>
              <li><a href="{{ route('admin.anticipos.masivo') }}">Anticipo Masivo</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('etiqueta-index|etiqueta-create')
        <li>
          <a href="#">
            <i class="fa fa-tags"></i>
            <span class="nav-label">Etiquetas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('etiqueta-index')
              <li><a href="{{ route('admin.etiquetas.index') }}">Ver etiquetas</a></li>
            @endpermission
            @permission('etiqueta-create')
              <li><a href="{{ route('admin.etiquetas.create') }}">Agregar etiqueta</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('factura-index|factura-create')
        <li>
          <a href="#">
            <i class="fa fa-file"></i>
            <span class="nav-label">Facturas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('factura-index')
              <li><a href="{{ route('admin.facturas.index') }}">Ver facturas</a></li>
            @endpermission
            @permission('factura-create')
              <li><a href="{{ route('admin.facturas.create') }}">Agregar factura</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('gasto-index|gasto-create')
        <li>
          <a href="#">
            <i class="fa fa-credit-card"></i>
            <span class="nav-label">Gastos</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('gasto-index')
              <li><a href="{{ route('admin.gastos.index') }}">Ver gastos</a></li>
            @endpermission
            @permission('gasto-create')
              <li><a href="{{ route('admin.gastos.create') }}">Agregar gasto</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('cotizacion-index|cotizacion-facturacion-index')
        <li>
          <a href="#">
            <i class="fa fa-dollar"></i>
            <span class="nav-label">Ventas</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('cotizacion-index')
              <li><a href="{{ route('admin.cotizacion.index') }}">Ver cotizaciones</a></li>
            @endpermission
            @permission('cotizacion-facturacion-index')
              <li><a href="{{ route('admin.cotizacion.facturacion.index') }}">Ver facturaciones</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('compra-index')
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
      @endpermission
      @permission('inventario-index|inventario-create')
        <li>
          <a href="#">
            <i class="fa fa-cubes"></i>
            <span class="nav-label">Inventarios</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('inventario-index')
              <li><a href="{{ route('admin.inventarios.index') }}">Ver inventarios</a></li>
            @endpermission
            @permission('inventario-create')
              <li><a href="{{ route('admin.inventarios.create') }}">Agregar inventario</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('inventario-v2-index|inventario-v2-create')
        <li>
          <a href="#">
            <i class="fa fa-tasks"></i>
            <span class="nav-label">Inventarios</span>
            <span class="fa arrow"></span>
            <span class="label label-warning float-right mr-2">v2</span>
          </a>
          <ul class="nav nav-second-level">
            @permission('inventario-v2-index')
              <li><a href="{{ route('admin.inventario.v2.index') }}">Ver inventarios</a></li>
            @endpermission
            @permission('inventario-v2-create')
              <li><a href="{{ route('admin.inventario.v2.create') }}">Agregar inventario</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('transporte-index|transporte-create')
        <li>
          <a href="#">
            <i class="fa fa-car"></i>
            <span class="nav-label">Transportes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('transporte-index')
              <li><a href="{{ route('admin.transportes.index') }}">Ver transportes</a></li>
            @endpermission
            @permission('transporte-create')
              <li><a href="{{ route('admin.transportes.create') }}">Agregar transporte</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('cliente-index|cliente-create')
        <li>
          <a href="#">
            <i class="fa fa-user-circle"></i>
            <span class="nav-label">Clientes</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('cliente-index')
              <li><a href="{{ route('admin.cliente.index') }}">Ver clientes</a></li>
            @endpermission
            @permission('cliente-create')
              <li><a href="{{ route('admin.cliente.create', ['type' => 'persona']) }}">Agregar persona</a></li>
              <li><a href="{{ route('admin.cliente.create', ['type' => 'empresa']) }}">Agregar empresa</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('proveedor-index|proveedor-create')
        <li>
          <a href="#">
            <i class="fa fa-briefcase"></i>
            <span class="nav-label">Proveedores</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            @permission('proveedor-index')
              <li><a href="{{ route('admin.proveedor.index') }}">Ver proveedores</a></li>
            @endpermission
            @permission('proveedor-create')
              <li><a href="{{ route('admin.proveedor.create', ['type' => 'persona']) }}">Agregar persona</a></li>
              <li><a href="{{ route('admin.proveedor.create', ['type' => 'empresa']) }}">Agregar empresa</a></li>
            @endpermission
          </ul>
        </li>
      @endpermission
      @permission('reporte-view')
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
      @endpermission
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
          @role('empleado')
            <li><a href="{{ route('solicitud.index') }}">Ver solicitudes</a></li>
            <li><a href="{{ route('solicitud.create') }}">Agregar solicitud</a></li>
          @endrole
          <li><a href="{{ route('requerimiento.material.index') }}">Requerimiento de Materiales</a></li>
        </ul>
      </li>

      @role('developer|superadmin|empresa')
        <li>
          <a href="{{ route('admin.empresa.covid19.index') }}">
            <i class="fa fa-heartbeat"></i> <span class="nav-label">Covid-19</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.empresa.configuracion') }}">
            <i class="fa fa-cogs"></i> <span class="nav-label">Configuración</span>
          </a>
        </li>
      @endrole

      @role('developer|superadmin')
        <li>
          <a href="#">
            <i class="fa fa-sliders"></i>
            <span class="nav-label">Administración</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.manage.empresa.index') }}"><i class="fa fa-building"></i>Empresas</a></li>
            <li><a href="{{ route('admin.manage.covid19.index') }}"><i class="fa fa-heartbeat"></i>Covid-19</a></li>
            <li><a href="{{ route('admin.manage.ayuda.index') }}"><i class="fa fa-question-circle"></i>Ayudas</a></li>
          </ul>
        </li>
      @endrole
      @ability('developer', 'god')
        <li>
          <a href="#">
            <i class="fa fa-terminal"></i>
            <span class="nav-label">Development</span>
            <span class="fa arrow"></span>
          </a>
          <ul class="nav nav-second-level">
            <li><a href="{{ route('admin.development.modulo.index') }}"><i class="fa fa-cube" aria-hidden="true"></i>Modulos</a></li>
            <li><a href="{{ route('admin.development.role.index') }}"><i class="fa fa-user-circle" aria-hidden="true"></i>Roles</a></li>
            <li><a href="{{ route('admin.development.permission.index') }}"><i class="fa fa-key" aria-hidden="true"></i>Permissions</a></li>
            <li><a href="{{ route('admin.development.fix.index') }}"><i class="fa fa-terminal" aria-hidden="true"></i>Fixes</a></li>
          </ul>
        </li>
      @endability

      <li>
        <a href="{{ route('ayuda.index') }}">
          <i class="fa fa-question-circle"></i> <span class="nav-label">Ayuda</span>
        </a>
      </li>
    </ul>
  </div>
</nav>
