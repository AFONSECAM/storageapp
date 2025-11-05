@extends('layout')

@section('content')
<h3>Editar grupo</h3>
<form method="POST" action="{{ route('admin.groups.update', $group) }}">@csrf @method('PUT')
  <div class="mb-3"><label>Nombre</label><input name="name" class="form-control" value="{{ $group->name }}" required></div>
  <div class="mb-3"><label>Cuota (bytes)</label><input name="quota_limit" type="number" class="form-control" value="{{ $group->quota_limit }}"></div>
  <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
