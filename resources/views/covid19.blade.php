@extends('layouts.app')

@section('title', 'Covid-19')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Covid-19</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Covid-19</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mt-3 justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-content">
          <form action="{{ route('covid19.store') }}" method="POST">
            @csrf

            <h2 class="text-center">Condiciones de Salud Consideradas de Alto Riesgo de COVID-19</h2>
            <p class="text-center">Minsal Protocolo de manejo de contactos de casos COVID-19. Ord. B1 N°939</p>
            <hr>

            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="text-center">¿Usted presenta algunas de estas condiciones médicas y de edad mencionadas a continuación?</th>
                  <th class="text-center"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($preguntas as $pregunta)
                  <tr>
                    <td>{{ $pregunta->pregunta }}</td>
                    <td>
                      <div class="form-group m-0{{ $errors->has('respuestas.'.$pregunta->id) }}">
                        <div class="custom-control custom-radio">
                          <input id="pregunta-{{ $pregunta->id }}-no" class="custom-control-input" type="radio" name="respuestas[{{ $pregunta->id }}]" value="0"{{ old('respuestas.'.$pregunta->id, '0') == '0' ? ' checked' : '' }}>
                          <label class="custom-control-label" for="pregunta-{{ $pregunta->id }}-no">No</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input id="pregunta-{{ $pregunta->id }}-si" class="custom-control-input" type="radio" name="respuestas[{{ $pregunta->id }}]" value="1"{{ old('respuestas.'.$pregunta->id) == '1' ? ' checked' : '' }}>
                          <label class="custom-control-label" for="pregunta-{{ $pregunta->id }}-si">Sí</label>
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            @if($errors->any())
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
