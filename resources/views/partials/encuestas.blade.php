@if(Auth::user()->tipo > 1 && empty($encuesta))
  @foreach(Auth::user()->encuestasPendientes()->get() as $encuesta)
  <div class="alert alert-info fade in alert-important" role="alert">
    <h4><i class="icon fa fa-question-circle"></i> Nueva encuesta</h4>
    <p>{{ $encuesta->titulo }}</p>
    <p>
      <a class="btn btn-flat btn-outline" href="{{ route('encuesta.show', ['encuesta' => $encuesta->id]) }}" title="Responder encuesta">Responder encuesta</a>
    </p>
  </div>
  @endforeach
@endif
