<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
    <title>@yield('title', config('app.name'))</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- CSS Files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <style type="text/css">
      *{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }
      header, footer{
        position: fixed;
        left: 0;
        right: 0;
        height: 2cm;
      }
      header{
        top: 0;
      }
      footer{
        bottom: 0;
      }
      h1{
        font-size: 20px;
      }
      h2{
        font-size: 18px;
      }
      h3{
        font-size: 16px;
      }
      h4{
        font-size: 14px;
      }
      h5{
        font-size: 12px;
      }
      h1,h2,h3,h4,h5,h6,p,span,div{ 
        font-family: DejaVu Sans !important;
        font-weight: normal;
      }
      h6,p,span,div{
        font-size: 10px;
      }
      p,span{
        line-height: 1.2em;
      }
      th,td{ 
        font-family: DejaVu Sans; 
        font-size: 10px;
      }
      table{
        width: 100%;
        max-width: 100%;
        margin-bottom: 0px;
        border-spacing: 0;
        border-collapse: collapse;
        background-color: transparent;
      }
      thead{
        text-align: left;
        vertical-align: middle;
      }
      th, td{
        border: 1px solid #ddd;
        padding: 6px;
      }
      .page-break{
        page-break-after: always;
      }
      .row{
        display: block;
        position: relative;
        margin: 0;
        width: 100%;
        max-width: 100%;
        margin-bottom: 10px;
      }
      .row:before,
      .row:after{
        display: table;
        content: " ";
      }
      .row:after{
        clear: both;
      }
      .col-4, .col-6, .col-8,
      .pdf-col-4, .pdf-col-6, .pdf-col-8{
        position: relative;
        float: left;
      }
      .pdf-col-4, .pdf-col-6, .pdf-col-8{
        min-height: 1px;
        padding-left: 10px;
        padding-right: 10px;
      }
      .pdf-col-4{
        width: 31.333%;
        max-width: 31.333%;
      }
      .pdf-col-6{
        width: 48.5%;
        max-width: 48.5%;
      }
      .pdf-col-8{
        width: 64.666%;
        max-width: 64.666%;
      }
      .light-background{
        background-color: #f7f7f7;
      }
    </style>

    @yield('head', '')
  </head>
  <body>
    <footer class="text-center">
      {{ config('app.name') }} - {{ date('Y') }}
    </footer>
    <div class="container-fluid p-0">
      @yield('content')
    </div>
  </body>
</html>
