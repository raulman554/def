@extends('layouts.admin')
@section('header', 'Premios para: ' . $tipo->nombre)
@section('content')
<div class="mb-3">
  <a href="{{ route('tipos-sorteo.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left"></i> Volver a Tipos de Sorteo
  </a>
  <a href="{{ route('tipos-sorteo.niveles-premio.create', $tipo) }}" class="btn btn-primary ms-2">
    <i class="bi bi-plus-circle"></i> Nuevo Premio
  </a>
</div>

<div class="card">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nivel</th>
          <th>Ganadores</th>
          <th>Monto USD</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      @foreach($niveles as $n)
        <tr>
          <td>{{ $n->nivel }}</td>
          <td>{{ $n->cantidad_ganadores }}</td>
          <td>${{ number_format($n->monto_premio_usd,2) }}</td>
          <td>
            <a href="{{ route('tipos-sorteo.niveles-premio.edit', [$tipo,$n]) }}"
              class="btn btn-sm btn-warning">
              <i class="bi bi-pencil"></i> Editar
            </a>
            <form action="{{ route('tipos-sorteo.niveles-premio.destroy', [$tipo,$n]) }}"
                  method="POST" class="d-inline"
                  onsubmit="return confirm('¿Eliminar este premio?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection