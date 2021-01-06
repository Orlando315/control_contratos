<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    @include('layouts.head')
  </head>
  <body class="fixed-sidebar">
    <div id="wrapper">
      @include('layouts.menu')

      <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
          @include('layouts.nav')
        </div>
        <div class="wrapper-heading">
          @yield('page-heading')
        </div>
        
        <div class="wrapper wrapper-content">
          @include('partials.entregas')
          @include('partials.sueldos')
          @include('partials.flash')

          @yield('content')
        </div>
        @include('layouts.footer')
      </div>
    </div>

    @include('layouts.script')
  </body>
</html>
