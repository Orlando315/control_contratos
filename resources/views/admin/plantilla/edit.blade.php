@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Plantillas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.plantilla.documento.index') }}">Plantillas</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar plantilla</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.plantilla.update', ['plantilla' => $plantilla->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label for="nombre">Nombre de la plantilla: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $plantilla->nombre) }}" placeholder="Nombre" required>
            </div>
            
            <div class="box-secciones">
              <label  for="nombre">Secciones: *</label>
              @foreach($plantilla->secciones as $seccion)
                <section id="seccion-{{ $loop->iteration }}" class="plantilla-seccion mb-3" data-seccion="{{ $loop->iteration }}">
                  <input type="hidden" name="secciones[{{ $loop->iteration }}][id]" value="{{ $seccion->id }}">
                  <div class="input-group">
                    <input id="seccion-{{ $loop->iteration }}-nombre" class="form-control" type="text" name="secciones[{{ $loop->iteration }}][nombre]" maxlength="50" value="{{ old('secciones.'.$loop->iteration.'.nombre', $seccion->nombre) }}" placeholder="Sección {{ $loop->iteration }}">
                    <span class="input-group-append">
                      <button class="btn btn-danger rounded-0 btn-delete-seccion" type="button" data-seccion="{{ $loop->iteration }}" {{ $loop->first ? ' disabled' : '' }}><i class="fa fa-times"></i></button>
                    </span>
                  </div>
                  <textarea id="seccion-{{ $loop->iteration }}-contenido" class="form-control" name="secciones[{{ $loop->iteration }}][contenido]" required>{!! old('secciones.'.$loop->iteration.'.contenido', $seccion->contenido) !!}</textarea>
                </section>
              @endforeach

              @if(old('secciones'))
                @foreach(old('secciones') as $seccion)
                  @continue($loop->iteration <= $plantilla->secciones->count())
                  <section id="seccion-{{ $loop->iteration }}" class="plantilla-seccion mb-3" data-seccion="{{ $loop->iteration }}">
                    <div class="input-group">
                      <input id="seccion-{{ $loop->iteration }}-nombre" class="form-control" type="text" name="secciones[{{ $loop->iteration }}][nombre]" maxlength="50" value="{{ old('secciones.'.$loop->iteration.'.nombre') }}" placeholder="Sección {{ $loop->iteration }}">
                      <span class="input-group-append">
                        <button class="btn btn-danger rounded-0 btn-delete-seccion" type="button" data-seccion="{{ $loop->iteration }}"><i class="fa fa-times"></i></button>
                      </span>
                    </div>
                    <textarea id="seccion-{{ $loop->iteration }}-contenido" class="form-control" name="secciones[{{ $loop->iteration }}][contenido]" required>{{ old('secciones.'.$loop->iteration.'.contenido') }}</textarea>
                  </section>
                @endforeach
              @endif
            </div>

            <button id="btn-seccion" class="btn btn-block btn-default btn-sm mb-3" type="button" role="button">Agregar sección</button>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.plantilla.show', ['plantilla' => $plantilla->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- CkEditor -->
  <script type="text/javascript" src="{{ asset('js/plugins/ckeditor/ckeditor.js') }}"></script>
  <script type="text/javascript">
    const templateSeccion = function (index) {
      return `<section id="seccion-${index}" class="plantilla-seccion mb-3" data-seccion="${index}">
                <div class="input-group">
                  <input id="seccion-${index}-nombre" class="form-control" type="text" name="secciones[${index}][nombre]" maxlength="50" placeholder="Sección ${index}">
                  <span class="input-group-append">
                    <button class="btn btn-danger rounded-0 btn-delete-seccion" type="button" data-seccion="${index}"><i class="fa fa-times"></i></button>
                  </span>
                </div>
                <textarea id="seccion-${index}-contenido" class="form-control" name="secciones[${index}][contenido]" data-seccion="${index}" required></textarea>
              </section>
      `;
    }

    const variables = @json($variables);
    const boxSecciones = $('.box-secciones');
    
    $(document).ready( function(){
      $('#btn-seccion').click(function () {
        let newIndex = $('.plantilla-seccion').length + 1

        boxSecciones.append(templateSeccion(newIndex))
        initEditor(`seccion-${newIndex}-contenido`)
      })

      $('.box-secciones').on('click', '.btn-delete-seccion', function () {
        let seccion = +$(this).data('seccion')

        if(seccion > 1){
          CKEDITOR.instances[`seccion-${seccion}-contenido`].destroy()
          $(`#seccion-${seccion}`).remove()
        }
      })

      $('textarea.form-control').each(function(k, v){
        initEditor($(v).attr('id'))
      })

      CKEDITOR.on('dialogDefinition', function (ev) {
        // Take the dialog name and its definition from the event data.
        let dialogName = ev.data.name;
        let dialogDefinition = ev.data.definition;

        // Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
        if(dialogName == 'token'){
          dialogDefinition.title = 'Insertar Variable'
          dialogDefinition.contents[0].elements[0].label = 'Seleccionar variable';
        }
      })
    });

    function initEditor(editor){
      CKEDITOR.replace(editor, {
        language: 'es',
        availableTokens: variables,
      });
    }
  </script>
@endsection
