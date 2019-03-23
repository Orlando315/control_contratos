@extends('layouts.app')
@section('title','Encuestas - '.config( 'app.name'))
@section('header','Encuestas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Encuesta</li>
  </ol>
@endsection

@section('content')

  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="box box-solid">
        <form action="{{ route('respuestas.store', ['encuesta' => $encuesta->id]) }}" method="POST">
          {{ csrf_field() }}
          <div class="box-header">
            <h3>{{ $encuesta->titulo }}</h3>
          </div>
          <div class="box-body">
            @foreach($encuesta->preguntas()->get() as $pregunta)
              <fieldset>
                <legend>
                  {{ $pregunta->pregunta }}
                </legend>
                <div class="form-group {{ $errors->has('pregunta.'.$pregunta->id) ? 'has-error' : '' }}">
                  @foreach($pregunta->opciones()->get() as $opcion)
                  <div class="radio">
                    <label>
                      <input type="radio" name="pregunta[{{$pregunta->id}}]" id="opcion{{$opcion->id}}" value="{{ $opcion->id }}" {{ old('pregunta.'.$pregunta->id) == $opcion->id ? 'checked' : '' }} required>
                      {{ $opcion->opcion }}
                    </label>
                  </div>
                  @endforeach
                </div>
              </fieldset>
            @endforeach

            @if(count($errors) > 0)
            <div class="alert alert-danger alert-important">
              Debe responder todas las preguntas.
            </div>
            @endif
          </div>
          <div class="box-footer">
            <button class="btn btn-flat btn-block btn-primary" type="submit">Enviar respuestas</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
