@extends('layout')

@section('content')
<h3>Nuevo grupo</h3>
<form method="POST" action="{{ route('admin.groups.store') }}">@csrf
  <div class="mb-3"><label>Nombre</label><input name="name" class="form-control" required></div>
  <div class="mb-3"><label>Cuota (bytes)</label><input name="quota_limit" type="number" class="form-control"></div>
  <button class="btn btn-primary">Guardar</button>
</form>
@endsection
