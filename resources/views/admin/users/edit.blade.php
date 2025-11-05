@extends('layout')

@section('content')
<h3>Editar usuario</h3>
<form method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
  <div class="mb-3"><label>Nombre</label><input name="name" class="form-control" value="{{ $user->name }}" required></div>
  <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" value="{{ $user->email }}" required></div>
  <div class="mb-3"><label>Nueva contraseña (opcional)</label><input name="password" type="password" class="form-control"></div>
  <div class="mb-3">
    <label>Rol</label>
    <select name="role" class="form-select">
      <option value="user" @selected($user->role=='user')>Usuario</option>
      <option value="admin" @selected($user->role=='admin')>Administrador</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Grupo</label>
    <select name="group_id" class="form-select">
      <option value="">-- Ninguno --</option>
      @foreach($groups as $g)
        <option value="{{ $g->id }}" @selected($user->group_id==$g->id)>{{ $g->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="mb-3"><label>Cuota personalizada (bytes)</label><input name="quota_limit" type="number" class="form-control" value="{{ $user->quota_limit }}"></div>
  <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
