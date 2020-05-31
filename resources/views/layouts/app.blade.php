<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield( 'title' , config( 'app.name' ) )</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Icon 16x16 -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset( 'images/icon.png' ) }}">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/bootstrap.min.css' ) }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/font-awesome.min.css' ) }}">
    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/AdminLTE.min.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'css/glyphicons.css' ) }}">
    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'plugins/datatables/datatables.min.css' ) }}"/>
    <!-- Datepicker -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'plugins/datepicker/css/bootstrap-datepicker3.min.css' ) }}">
    <!-- Fullcalendar -->
    <link rel="stylesheet" type="text/css" href="{{ asset( 'plugins/fullcalendar/fullcalendar.min.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'plugins/fullcalendar/scheduler.min.css' ) }}">
    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2.min.css' ) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/select2/select2-bootstrap.min.css' ) }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset( 'css/_all-skins.min.css' ) }}">
    <!-- Custom -->
    <link rel="stylesheet" href="{{ asset( 'css/style.css' ) }}">
  </head>
  <body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <!-- Logo -->
        <a href="{{ route( 'dashboard' ) }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">
            <!--<img src="#" alt="">-->
          </span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
            <!--<img src="#" alt="">-->
          </span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <!-- User Account: style can be found in dropdown.less -->

              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="hidden-xs">{{ Auth::user()->usuario }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <p>{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}<br>
                      <small>{{ Auth::user()->tipo() }}</small><br>
                      <small>{{ Auth::user()->email }}</small>
                    </p>
                    <p>
                      @if(Auth::user()->tipo == 1)
                        {{ Auth::user()->empresa->representante }}
                      @endif
                      <small>{{ Auth::user()->telefono }}</small>
                    </p>
                  </li>

                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="{{ route('usuarios.perfil') }}" class="btn btn-flat btn-default"><i class="fa fa-user-circle" aria-hidden="true"></i> Perfil</a>
                    </div>
                    
                    <div class="pull-right">
                      <form action="{{ route('login.logout') }}" method="POST">
                        {{ csrf_field() }}
                        <button class="btn btn-flat btn-default" type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i> Salir</button>
                      </form>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MENÚ</li>

            <li>
              <a href="{{ route('dashboard') }}">
                <i class="fa fa-home"></i> <span>Inicio</span>
              </a>
            </li>

            @if(Auth::user()->tipo <= 2)            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-clipboard"></i>
                <span>Contratos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('contratos.index') }}"><i class="fa fa-circle-o"></i>Ver contratos</a></li>
                @if(Auth::user()->tipo == 1)
                <li><a href="{{ route('contratos.create') }}"><i class="fa fa-circle-o"></i>Agregar contrato</a></li>
                @endif
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-file-text-o"></i>
                <span>Documentos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('plantilla.documento.index') }}"><i class="fa fa-circle-o"></i>Ver documentos</a></li>
                <li><a href="{{ route('plantilla.documento.create') }}"><i class="fa fa-circle-o"></i>Agregar documento</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-object-group"></i>
                <span>Plantillas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('plantilla.index') }}"><i class="fa fa-circle-o"></i>Ver plantillas</a></li>
                <li><a href="{{ route('plantilla.create') }}"><i class="fa fa-circle-o"></i>Agregar plantilla</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Usuarios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('usuarios.index') }}"><i class="fa fa-circle-o"></i>Ver usuarios</a></li>
                <li><a href="{{ route('usuarios.create') }}"><i class="fa fa-circle-o"></i>Agregar usuario</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-level-up"></i>
                <span>Anticipos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('anticipos.index') }}"><i class="fa fa-circle-o"></i>Ver anticipos</a></li>
                <li><a href="{{ route('anticipos.individual') }}"><i class="fa fa-circle-o"></i>Anticipo Individual</a></li>
                <li><a href="{{ route('anticipos.masivo') }}"><i class="fa fa-circle-o"></i>Anticipo Masivo</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-tags"></i>
                <span>Etiquetas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('etiquetas.index') }}"><i class="fa fa-circle-o"></i>Ver etiquetas</a></li>
                <li><a href="{{ route('etiquetas.create') }}"><i class="fa fa-circle-o"></i>Agregar etiqueta</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-file"></i>
                <span>Facturas</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('facturas.index') }}"><i class="fa fa-circle-o"></i>Ver facturas</a></li>
                <li><a href="{{ route('facturas.create') }}"><i class="fa fa-circle-o"></i>Agregar factura</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-credit-card"></i>
                <span>Gastos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('gastos.index') }}"><i class="fa fa-circle-o"></i>Ver gastos</a></li>
                <li><a href="{{ route('gastos.create') }}"><i class="fa fa-circle-o"></i>Agregar gasto</a></li>
              </ul>
            </li>
            @endif

            @if(Auth::user()->tipo <= 3)
            <li class="treeview">
              <a href="#">
                <i class="fa fa-cubes"></i>
                <span>Inventarios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('inventarios.index') }}"><i class="fa fa-circle-o"></i>Ver inventarios</a></li>
                <li><a href="{{ route('inventarios.create') }}"><i class="fa fa-circle-o"></i>Agregar inventario</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#">
                <i class="fa fa-car"></i>
                <span>Transportes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('transportes.index') }}"><i class="fa fa-circle-o"></i>Ver transportes</a></li>
                @if(Auth::user()->tipo <= 2)
                <li><a href="{{ route('transportes.create') }}"><i class="fa fa-circle-o"></i>Agregar transporte</a></li>
                @endif
              </ul>
            </li>
            @endif

            @if(Auth::user()->tipo <= 2)
            <li class="treeview">
              <a href="#">
                <i class="fa fa-area-chart"></i>
                <span>Reportes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{ route('reportes.generalIndex') }}"><i class="fa fa-circle-o"></i>General</a></li>
                <li><a href="{{ route('reportes.inventariosIndex') }}"><i class="fa fa-circle-o"></i>Inventarios</a></li>
                <li><a href="{{ route('reportes.facturasIndex') }}"><i class="fa fa-circle-o"></i>Facturas</a></li>
                <li><a href="{{ route('reportes.eventosIndex') }}"><i class="fa fa-circle-o"></i>Eventos</a></li>
                <li><a href="{{ route('reportes.sueldosIndex') }}"><i class="fa fa-circle-o"></i>Sueldos</a></li>
                <li><a href="{{ route('reportes.anticiposIndex') }}"><i class="fa fa-circle-o"></i>Anticipos</a></li>
                <li><a href="{{ route('reportes.transportesIndex') }}"><i class="fa fa-circle-o"></i>Transportes</a></li>
                <li><a href="{{ route('reportes.comidasIndex') }}"><i class="fa fa-circle-o"></i>Comidas</a></li>
                <li><a href="{{ route('reportes.reemplazosIndex') }}"><i class="fa fa-circle-o"></i>Reemplazos</a></li>
              </ul>
            </li>
            @endif

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content-header">
          <h1>
            @yield( 'header' )
          </h1>
          @yield( 'breadcrumb' )
        </section>
        <!-- Main content -->
        <section class="content">
          @include('partials.entregas')
          @include('partials.sueldos')
          @yield( 'content' )
        </section>
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
      <footer class="main-footer">
      </footer>
    </div><!-- .wrapper -->
    <!-- jQuery 2.1.4 -->
    <script type="text/javascript" src="{{ asset( 'js/jQuery-2.1.4.min.js' ) }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script type="text/javascript" src="{{ asset( 'js/bootstrap.min.js' ) }}"></script>
    <!-- AdminLTE App -->
    <script type="text/javascript" src="{{ asset( 'js/app.min.js' ) }}"></script>
    <!-- Data table -->
    <script type="text/javascript" src="{{ asset( 'plugins/datatables/datatables.min.js' ) }}"></script>
    <!-- Datepicker -->
    <script type="text/javascript" src="{{ asset( 'plugins/datepicker/js/bootstrap-datepicker.min.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'plugins/datepicker/locales/bootstrap-datepicker.es.min.js' ) }}"></script>
    <!-- Fullcalendar -->
    <script type="text/javascript" src="{{ asset( 'plugins/fullcalendar/lib/moment.min.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'plugins/fullcalendar/fullcalendar.min.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'plugins/fullcalendar/locale/es.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'plugins/fullcalendar/scheduler.min.js' ) }}"></script>
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset( 'plugins/select2/select2.min.js' ) }}"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('div.alert').not('.alert-important').delay(7000).slideUp(300);

        $('.data-table').DataTable({
          dom: 'Bfrtip',
          responsive: true,
          language: {
            url:'{{ asset( "plugins/datatables/spanish.json" ) }}'
          },
          buttons: [
            {
              extend: 'print',
              text: 'Imprimir'
            }
          ],
          pageLength: 20
        });

        $('.btn-confirmar').click(function(){
          let btn = $(this),
              id = btn.data('id'),
              action = btn.data('path');

          $.ajax({
            type: 'POST',
            url: action,
            data: {
              _token: '{{ csrf_token() }}',
              _method: 'PATCH',
            },
            dataType: 'json',
          })
          .done(function(data){
            if(data.response){
              btn.closest('.alert').alert('close')
            }else{
              console.log('false')
            }
          })
          .fail(function(){
            console.log('fail')
          })
        })

        $('.btn-print').click(function(){
          window.print();
        })
      })
    </script>

    @yield( 'scripts' )
  </body>
</html>
