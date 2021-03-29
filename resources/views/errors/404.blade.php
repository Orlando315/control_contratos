@extends('layouts.blank')

@section('title', 'Página no encontrada')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>404</h1>
    <h3 class="font-bold">Oops! Página no encontrada.</h3>

    <div class="error-desc">
      No pudimos encontrar la página que estas buscando.</br>
      Podrías <a href="{{ route('dashboard') }}">volver al Inicio</a>.
    </div>
  </div>
@endsection
