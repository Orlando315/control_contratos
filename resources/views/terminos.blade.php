@extends('layouts.app')

@section('title', 'Terminos y condiciones')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Terminos y condiciones</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Terminos y condiciones</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mt-3 justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-content">
          {!! $terminos->terminos !!}
        </div>
      </div>
    </div>
  </div>
@endsection
