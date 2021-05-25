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
        
        <div class="wrapper wrapper-content mb-3">
          @include('partials.egreso')
          @include('partials.sueldos')
          @include('partials.flash')

          @yield('content')
        </div>
        @include('layouts.footer')
      </div>
      @include('layouts.banner')
    </div>

    @include('layouts.script')
  </body>
</html>
