@extends('layouts.admin')
@section('header', 'Tipos de Sorteo')
@section('content')

<div class="container mt-4">
  <h3>Tipos de Sorteo</h3>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if (session('warning'))
    <div class="alert alert-warning">
      {!! session('warning') !!}
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <a href="{{ route('tipos-sorteo.create') }}" class="btn btn-primary mb-3">
    Nuevo tipo
  </a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Precio</th><th>Máx.</th>
        <th>Payout</th><th>Activo</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    @foreach($tipos as $t)
      <tr>
        <td>{{ $t->id }}</td>
        <td>{{ $t->nombre }}</td>
        <td>${{ number_format($t->precio_ticket_usd,2) }}</td>
        <td>{{ $t->max_tickets }}</td>
        <td>{{ $t->payout_ratio * 100 }}%</td>
        <td>{{ $t->activo ? 'Sí' : 'No' }}</td>
        <td>
          <a href="{{ route('tipos-sorteo.niveles-premio.index', $t) }}"
             class="btn btn-sm btn-info mb-1">Premios</a>
          <a href="{{ route('tipos-sorteo.edit',$t) }}" class="btn btn-sm btn-warning">Editar</a>
          <form action="{{ route('tipos-sorteo.destroy',$t) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de querer eliminar este tipo de sorteo?')">
            @csrf 
            @method('DELETE')
            <button class="btn btn-sm btn-danger">Borrar</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $tipos->links() }}
</div>
@endsection