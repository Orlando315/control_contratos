@extends('layouts.blank')

@section('title', 'No autorizado')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>419</h1>
    <h3 class="font-bold">No autorizado.</h3>

    <div class="error-desc">
      No estás autorizado para acceder a esta página.</br>
      Podrías <a href="{{ route('dashboard') }}">volver al Inicio</a>.
    </div>
  </div>
@endsection
