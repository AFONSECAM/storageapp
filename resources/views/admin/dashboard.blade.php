@extends('layout')

@section('content')
<h3>Panel de administración</h3>
<ul>
  <li><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
  <li><a href="{{ route('admin.groups.index') }}">Grupos</a></li>
  <li><a href="{{ route('admin.settings.index') }}">Configuración</a></li>
</ul>
@endsection
