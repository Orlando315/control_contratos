@extends( 'layouts.app' )
@section( 'title','Sueldos - '.config( 'app.name' ) )
@section( 'header','Sueldos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}">Contrato</a></li>
    <li class="active">Sueldos</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-money"></i> Sueldos</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('sueldos.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Realizar pagos</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Alcance líquido</th>
                <th class="text-center">Sueldo líquido</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($sueldos as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $d->created_at }}</td>
                  <td>
                    <a href="{{ route('empleados.show', ['empleado' => $d->empleado_id]) }}">
                      {{ $d->nombreEmpleado() }}
                    </a>
                  </td>
                  <td>{{ $d->alcanceLiquido() }}</td>
                  <td>{{ $d->sueldoLiquido() }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('sueldos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
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
