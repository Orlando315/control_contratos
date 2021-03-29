@extends('layouts.blank')

@section('title', 'Página expirada')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>419</h1>
    <h3 class="font-bold">La página ha expirado por inactividad.</h3>

    <div class="error-desc">
      Por favor intente de nuevo o regrese al <a href="{{ route('dashboard') }}" title="Volver al inicio">inicio</a>.
    </div>
  </div>
@endsection
