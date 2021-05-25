@extends('layouts.pdf')

@section('title', 'PDF')

@section('content')
  <h1 class="text-center">{{ $nombre }}</h1>
  @foreach($documento->plantilla->secciones as $seccion)
    <h3>{{ $seccion->nombre }}</h3>
    <div class="w-100">
      {!! $documento->fillSeccionVariables($seccion) !!}
    </div>
  @endforeach
@endsection
