@extends('layouts.app')

@section('title', 'Editar')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuarios</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar administrador</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.usuarios.update', ['usuario' => $usuario->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                  <label for="role">Role: *</label>
                  <select id="role" class="form-control" name="role" required>
                    <option value="">Seleccione...</option>
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}"{{ old('role', $usuario->role()->id) == $role->id ? ' selected' : '' }}>
                        {{ $role->name() }} {{ $role->description ? '('.$role->description.')' : '' }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                  <label for="nombres">Nombres: *</label>
                  <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres', $usuario->nombres) }}" placeholder="Nombres" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                  <label for="apellidos">Apellidos: *</label>
                  <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos', $usuario->apellidos) }}" placeholder="Apellidos" required>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
              <label for="rut">RUT: *</label>
              <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="\d{4,9}-[\dk])$" value="{{ old('rut', $usuario->rut) }}" placeholder="RUT" required>
              <span class="help-block">Ejemplo: 00000000-0</span>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono:</label>
                  <input id="telefono" class="form-control" type="telefono" name="telefono" maxlength="20" value="{{ old('telefono', $usuario->telefono) }}" placeholder="Teléfono">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email:</label>
                  <input id="email" class="form-control" type="text" name="email" maxlength="50" value="{{ old('email', $usuario->email) }}" placeholder="Email">
                </div>
              </div>
            </div>

            <section>
              <legend class="form-legend">Permisos</legend>
              <p>Los permisos básicos de los Roles no pueden ser removidos.</p>

              @foreach($modulos as $modulo)
                <div class="form-group">
                  <label>{{ $modulo->name() }}:</label>

                  <div class="row">
                    @foreach($modulo->permissions as $permission)
                      <div class="col-md-6">
                        <div class="custom-control custom-checkbox">
                          <input id="permission-{{ $permission->id }}" class="custom-control-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"{{ $usuario->permissions->contains($permission->id) ? ' checked' : '' }}>
                          <label class="custom-control-label" for="permission-{{ $permission->id }}" title="{{ $permission->description }}">
                            {{ $permission->display_name ?? 'N/A' }}
                          </label>
                          @if($permission->description)
                            </br>
                            <small class="text-muted">({{ $permission->description }})</small>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </section>

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
              <a class="btn btn-default btn-sm" href="{{ route('admin.usuarios.show', ['usuario' => $usuario->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    const ROLES = @json($roles);
    const OLD_PERMISSIONS = @json($usuario->permissions->pluck('id'));

    $(document).ready( function () {
      $('#role').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#role').change(function () {
        let id = +$(this).val();
        let role = ROLES.find(role => (role.id === id));
        
        $.each($('input[id^="permission-"]'), function (k, v) {
          let permission = +$(v).val();
          let exist = OLD_PERMISSIONS.includes(permission);

          $(v).prop({'checked': exist, 'disabled': false});
        });

        $.each(role.permissions, function (k, v) {
          $('#permission-'+v.id).prop({'checked': true, 'disabled': true});
        });
      });

      $('#role').change();
    });
  </script>
@endsection
