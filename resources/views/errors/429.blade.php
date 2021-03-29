@extends('layouts.blank')

@section('title', 'Demasiadas peticiones')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>429</h1>
    <h3 class="font-bold">Demasiadas peticiones.</h3>

    <div class="error-desc">
      Lo siento, estas haciendo demasiadas peticiones a nuestros servidores.
    </div>
  </div>
@endsection
