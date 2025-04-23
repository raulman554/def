@extends('layouts.admin')
@section('header', 'Instancias de Sorteo')
@section('content')

<a href="{{ route('instancias-sorteo.create') }}" class="btn btn-primary mb-3">
  <i class="bi bi-plus-circle"></i> Nueva instancia
</a>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Fecha Inicio</th>
            <th>Fecha Cierre</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @forelse($instancias as $i)
          <tr>
            <td>{{ $i->id }}</td>
            <td>{{ $i->tipo->nombre ?? 'N/A' }}</td>
            <td>{{ $i->fecha_inicio instanceof \DateTime ? $i->fecha_inicio->format('d/m/Y H:i') : $i->fecha_inicio }}</td>
            <td>{{ $i->fecha_cierre instanceof \DateTime ? $i->fecha_cierre->format('d/m/Y H:i') : $i->fecha_cierre }}</td>
            <td>
              @if($i->estado == 'abierta')
                <span class="badge bg-success">Abierta</span>
              @elseif($i->estado == 'cerrada')
                <span class="badge bg-danger">Cerrada</span>
              @else
                <span class="badge bg-warning">Pendiente</span>
              @endif
            </td>
            <td>
              <a href="{{ route('instancias-sorteo.edit', $i->id) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i> Editar
              </a>
              <form action="{{ route('instancias-sorteo.destroy', $i->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta instancia?')">
                @csrf 
                @method('DELETE')
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i> Borrar
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">No hay instancias de sorteo registradas</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    
    @if(isset($instancias) && method_exists($instancias, 'links'))
      {{ $instancias->links() }}
    @endif
  </div>
</div>
@endsection