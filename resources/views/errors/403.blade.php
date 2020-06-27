@extends('layouts.blank')

@section('title', 'Prohibído')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>403</h1>
    <h3 class="font-bold">Prohibído.</h3>

    <div class="error-desc">
      Lo siento, tienes prohibído acceder a esta página.</br>
      Podrías <a href="{{ route('dashboard') }}">volver al Inicio</a>.
    </div>
  </div>
@endsection
