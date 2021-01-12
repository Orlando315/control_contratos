@extends('layouts.app')

@section('title', 'Ayudas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ayudas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Ayudas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mt-3 justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-question-circle"></i> Ayudas</h5>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group">
            @forelse($ayudas as $ayuda)
              <li class="list-group-item">
                <a class="text-primary" href="{{ route('ayuda.show', ['ayuda' => $ayuda->id]) }}">
                  {{ $ayuda->titulo }}
                </a>
              </li>
            @empty
              <li class="list-group-item">
                <h5 class="text-muted text-center">No hay información disponible.</h5>
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
