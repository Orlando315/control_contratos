@extends('layouts.blank')

@section('title', 'Error')

@section('content')
  <div class="middle-box text-center animated fadeInDown">
    <center><img class="img-responsive" src="{{ asset( 'images/logo.png' ) }}" alt="Logo" style="height:80px"></center>
    <h1>500</h1>
    <h3 class="font-bold">Error.</h3>

    <div class="error-desc">
      Oops, algo salió mal en nuestros servidores.
    </div>
  </div>
@endsection
