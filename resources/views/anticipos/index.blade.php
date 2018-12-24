@extends( 'layouts.app' )
@section( 'title','Anticipos - '.config( 'app.name' ) )
@section( 'header','Anticipos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Anticipos</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-level-up"></i> Anticipos</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('anticipos.individual') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Individual</a>
            <a class="btn btn-success btn-flat" href="{{ route('anticipos.masivo') }}"><i class="fa fa-plus" aria-hidden="true"></i> Anticipo Masivo</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Anticipo</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($anticipos as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td><a href="{{ route('contratos.show', ['contrato' => $d->contrato->id]) }}">{{ $d->contrato->nombre }} </a></td>
                  <td><a href="{{ route('empleados.show', ['empleado' => $d->empleado->id]) }}">{{ $d->empleado->usuario->nombres }} {{ $d->empleado->usuario->apellidos }}</a></td>
                  <td>{{ $d->fecha }}</td>
                  <td>{{ $d->anticipo() }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('anticipos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('anticipos.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
