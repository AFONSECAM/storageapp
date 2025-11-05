@extends('layout')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3>Registro de usuario</h3>
    <form method="POST" action="{{ route('register') }}">@csrf
      <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-success w-100">Crear cuenta</button>
    </form>
  </div>
</div>
@endsection
