@extends('layouts.app')

@section('title', 'Sorteos Disponibles - Lotoup')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Sorteos Disponibles</h1>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @php
            $hoy = now();
            $sorteos = App\Models\InstanciaSorteo::with('tipo')
                        ->where('estado', 'abierta')
                        ->where('fecha_inicio', '<=', $hoy)
                        ->where('fecha_cierre', '>=', $hoy)
                        ->get();
        @endphp

        @forelse($sorteos as $sorteo)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $sorteo->tipo->nombre }}</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Precio:</strong> ${{ number_format($sorteo->tipo->precio_ticket_usd, 2) }} USD</p>
                        <p><strong>Cierra:</strong> {{ $sorteo->fecha_cierre instanceof \Carbon\Carbon ? $sorteo->fecha_cierre->format('d/m/Y H:i') : 'N/A' }}</p>
                        <p>{{ $sorteo->tipo->descripcion_breve }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('sorteos.show', $sorteo->id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-ticket-perforated"></i> Participar
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    No hay sorteos disponibles en este momento. Vuelve más tarde.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection