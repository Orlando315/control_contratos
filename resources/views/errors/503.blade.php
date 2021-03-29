@extends('layouts.blank')

@section('title', 'Servicio no disponible')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>503</h1>
    <h3 class="font-bold">Servicio no disponible.</h3>

    <div class="error-desc">
      Lo siento, estamos en mantenimiento. Por favor, intenta más tarde.
    </div>
  </div>
@endsection
