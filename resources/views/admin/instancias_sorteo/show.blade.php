@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h3>Detalles de Instancia de Sorteo</h3>

  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $instancia->tipo->nombre }}</h5>
        <span class="badge bg-{{ $instancia->estado == 'abierta' ? 'success' : ($instancia->estado == 'pendiente' ? 'warning' : 'secondary') }}">
          {{ ucfirst($instancia->estado) }}
        </span>
      </div>
    </div>
    <div class="card-body">
      <p><strong>ID:</strong> {{ $instancia->id }}</p>
      <p><strong>Precio del Ticket:</strong> ${{ number_format($instancia->tipo->precio_ticket_usd, 2) }}</p>
      <p><strong>Fecha de Inicio:</strong> {{ $instancia->fecha_inicio->format('d/m/Y H:i') }}</p>
      <p><strong>Fecha de Cierre:</strong> {{ $instancia->fecha_cierre->format('d/m/Y H:i') }}</p>
      <p><strong>Lotería de Referencia:</strong> {{ $instancia->loteria_referencia ?: 'No especificada' }}</p>
      <p><strong>Tickets Máximos:</strong> {{ $instancia->tipo->max_tickets }}</p>
      <p><strong>Payout Ratio:</strong> {{ number_format($instancia->tipo->payout_ratio * 100, 0) }}%</p>
      
      <div class="mt-4">
        <h6>Estadísticas de Tickets</h6>
        @php
          $disponibles = $instancia->tickets()->where('estado', 'disponible')->count();
          $reservados = $instancia->tickets()->where('estado', 'reservado')->count();
          $vendidos = $instancia->tickets()->where('estado', 'vendido')->count();
          $total = $disponibles + $reservados + $vendidos;
          
          // Prevenir división por cero
          $porcentajeDisponibles = $total > 0 ? ($disponibles / $total) * 100 : 0;
          $porcentajeReservados = $total > 0 ? ($reservados / $total) * 100 : 0;
          $porcentajeVendidos = $total > 0 ? ($vendidos / $total) * 100 : 0;
        @endphp
        
        <div class="row text-center">
          <div class="col-md-4">
            <div class="card bg-light">
              <div class="card-body">
                <h3>{{ $disponibles }}</h3>
                <p class="mb-0">Disponibles</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <h3>{{ $reservados }}</h3>
                <p class="mb-0">Reservados</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-success text-white">
              <div class="card-body">
                <h3>{{ $vendidos }}</h3>
                <p class="mb-0">Vendidos</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="progress mt-3">
          <div class="progress-bar bg-light text-dark" style="width: {{ $porcentajeDisponibles }}%">
            {{ number_format($porcentajeDisponibles, 0) }}%
          </div>
          <div class="progress-bar bg-warning" style="width: {{ $porcentajeReservados }}%">
            {{ number_format($porcentajeReservados, 0) }}%
          </div>
          <div class="progress-bar bg-success" style="width: {{ $porcentajeVendidos }}%">
            {{ number_format($porcentajeVendidos, 0) }}%
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <div class="d-flex gap-2">
        <a href="{{ route('instancias-sorteo.edit', $instancia->id) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('instancias-sorteo.index') }}" class="btn btn-secondary">Volver al listado</a>
      </div>
    </div>
  </div>
</div>
@endsection