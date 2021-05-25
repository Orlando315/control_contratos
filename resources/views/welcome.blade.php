@extends('layouts.blank')

@section('title', 'Bienvenido')

@section('content')
  <div class="text-center my-5">
    <img src="{{ asset('images/logo-small.png') }}" alt="Vertrag" style="max-width: 250px">
  </div>

  <div class="row mt-3 justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-content">
          <h2 class="text-center">¡Bienvenido {{ Auth::user()->nombre() }}!</h2>
          <p class="text-center text-muted">{{ Auth::user()->rut }}</p>
          <p><strong>Role activo:</strong> {!! Auth::user()->role()->asTag() !!}</p>
          <form class="text-center mb-5" action="{{ route('role.toggle') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row justify-content-center mb-5 mt-4">
              @foreach($roles as $role)
                <div class="col-md-3 text-center">
                  <label class="border py-3 px-5" for="role-{{ $role->id }}">
                    <strong>{{ $role->name() }}</strong>
                    <div class="custom-control custom-radio">
                      <input id="role-{{ $role->id }}" class="custom-control-input" type="radio" name="role" value="{{ $role->id }}"{{ Auth::user()->role()->id == $role->id ? ' checked' : '' }}>
                      <span class="custom-control-label" for="role-{{ $role->id }}"></span>
                    </div>
                  </label>
                </div>
              @endforeach
            </div>

            <a class="btn btn-default" href="{{ route('dashboard') }}">Volver al sistema</a>
            <button class="btn btn-primary" type="submit">Cambiar de Role</button>
          </form>
        </div>
      </div>

      <h3 class="text-center mt-5 pb-3 border-bottom">Empresas a las que perteneces</h3>

      <div class="row">
        @foreach($empresas as $empresa)
          <div class="col-md-3">
            <div class="ibox">
              <div class="ibox-content no-padding border-left-right text-center">
                <img class="img-fluid" src="{{ $empresa->logo_url }}" style="max-height: 180px;">
              </div>
              <div class="ibox-content profile-content">
                <h4><strong>{{ $empresa->nombre }}</strong></h4>
                <p class="text-muted">{{ $empresa->rut }}</p>
                <p class="m-0">
                  @if($empresa->representante)
                    <strong>{{ $empresa->representante }}</strong><br>
                  @endif
                  @if($empresa->telefono)
                    <i class="fa fa-phone" aria-hidden="true"></i> {{ $empresa->telefono }}<br>
                  @endif
                  @if($empresa->email)
                    <i class="fa fa-envelope" aria-hidden="true"></i> {{ $empresa->email }}
                  @endif
                </p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
