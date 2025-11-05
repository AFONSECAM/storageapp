@extends('layout')

@section('content')
<h3>Mis archivos</h3>
<p>Uso actual: <strong>{{ number_format($used / 1024, 2) }} KB</strong> /
Límite: <strong>{{ number_format($quota / 1024, 2) }} KB</strong></p>

<form id="uploadForm" action="{{ route('files.store') }}" enctype="multipart/form-data">
  @csrf
  <div class="mb-3">
    <input type="file" name="file" class="form-control" required>
  </div>
  <button class="btn btn-primary">Subir archivo</button>

  <div id="progressContainer" class="mt-3"></div>
</form>

<hr>
<table class="table table-striped">
  <thead>
    <tr><th>Nombre</th><th>Tamaño (KB)</th><th>Acción</th></tr>
  </thead>
  <tbody>
    @foreach($files as $f)
      <tr>
        <td>{{ $f->name }}</td>
        <td>{{ number_format($f->size / 1024, 2) }}</td>
        <td>
          <form method="POST" action="{{ route('files.destroy', $f) }}">@csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
