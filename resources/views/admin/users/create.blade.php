@extends('layout')

@section('content')
<h3>Crear usuario</h3>
<form method="POST" action="{{ route('admin.users.store') }}">@csrf
  <div class="mb-3"><label>Nombre</label><input name="name" class="form-control" required></div>
  <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" required></div>
  <div class="mb-3"><label>Contraseña</label><input name="password" type="password" class="form-control" required></div>
  <div class="mb-3">
    <label>Rol</label>
    <select name="role" class="form-select">
      <option value="user">Usuario</option>
      <option value="admin">Administrador</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Grupo</label>
    <select name="group_id" class="form-select">
      <option value="">-- Ninguno --</option>
      @foreach($groups as $g)
        <option value="{{ $g->id }}">{{ $g->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="mb-3"><label>Cuota personalizada (bytes)</label><input name="quota_limit" type="number" class="form-control"></div>
  <button class="btn btn-primary">Guardar</button>
</form>
@endsection
