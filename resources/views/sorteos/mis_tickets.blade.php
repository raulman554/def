@extends('layouts.app')

@section('title', 'Mis Tickets - Lotoup')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mis Tickets</h1>
    
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs mb-4" id="ticketsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button" role="tab">
                        Activos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes" type="button" role="tab">
                        Pendientes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab">
                        Histórico
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="ticketsTabContent">
                <!-- Tickets Activos -->
                <div class="tab-pane fade show active" id="activos" role="tabpanel">
                    @php 
                        $ticketsActivos = $ticketsAgrupados->filter(function($grupo, $key) {
                            return strpos($key, '_vendido') !== false;
                        });
                    @endphp
                    
                    @if($ticketsActivos->count() > 0)
                        @foreach($ticketsActivos as $key => $grupo)
                            @php
                                $primerTicket = $grupo->first();
                                $instancia = $primerTicket->instancia;
                                $tipo = $instancia->tipo;
                            @endphp
                            
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">{{ $tipo->nombre }} - Sorteo #{{ $instancia->id }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Fecha del sorteo:</strong> {{ $instancia->fecha_cierre->format('d/m/Y H:i') }}</p>
                                            <p><strong>Precio por ticket:</strong> ${{ number_format($tipo->precio_ticket_usd, 2) }} USD</p>
                                            <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Números:</strong> 
                                                @foreach($grupo as $ticket)
                                                    <span class="badge bg-primary me-1">{{ $ticket->numero }}</span>
                                                @endforeach
                                            </p>
                                            <p><strong>Total pagado:</strong> ${{ number_format($grupo->count() * $tipo->precio_ticket_usd, 2) }} USD</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            No tienes tickets activos en este momento.
                        </div>
                    @endif
                </div>
                
                <!-- Tickets Pendientes -->
                <div class="tab-pane fade" id="pendientes" role="tabpanel">
                    @php 
                        $ticketsPendientes = $ticketsAgrupados->filter(function($grupo, $key) {
                            return strpos($key, '_reservado') !== false || strpos($key, '_Pendiente_Verificacion') !== false;
                        });
                    @endphp
                    
                    @if($ticketsPendientes->count() > 0)
                        @foreach($ticketsPendientes as $key => $grupo)
                            @php
                                $primerTicket = $grupo->first();
                                $instancia = $primerTicket->instancia;
                                $tipo = $instancia->tipo;
                                $estado = $primerTicket->estado;
                            @endphp
                            
                            <div class="card mb-4">
                                <div class="card-header {{ $estado == 'reservado' ? 'bg-warning' : 'bg-info' }} text-white">
                                    <h5 class="mb-0">{{ $tipo->nombre }} - Sorteo #{{ $instancia->id }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Fecha del sorteo:</strong> {{ $instancia->fecha_cierre->format('d/m/Y H:i') }}</p>
                                            <p><strong>Precio por ticket:</strong> ${{ number_format($tipo->precio_ticket_usd, 2) }} USD</p>
                                            <p><strong>Estado:</strong> 
                                                @if($estado == 'reservado')
                                                    <span class="badge bg-warning text-dark">Reservado</span>
                                                @else
                                                    <span class="badge bg-info">Pendiente de verificación</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Números:</strong> 
                                                @foreach($grupo as $ticket)
                                                    <span class="badge bg-primary me-1">{{ $ticket->numero }}</span>
                                                @endforeach
                                            </p>
                                            <p><strong>Total a pagar:</strong> ${{ number_format($grupo->count() * $tipo->precio_ticket_usd, 2) }} USD</p>
                                            
                                            @if($estado == 'reservado')
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('sorteos.pago', $instancia->id) }}" class="btn btn-primary">
                                                        <i class="bi bi-cash"></i> Completar Pago
                                                    </a>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle-fill me-2"></i>
                                                    Tu pago está siendo verificado. Esto puede tomar hasta 24 horas.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            No tienes tickets pendientes en este momento.
                        </div>
                    @endif
                </div>
                
                <!-- Tickets Históricos (ganados/perdidos) -->
                <div class="tab-pane fade" id="historico" role="tabpanel">
                    @php 
                        $ticketsHistoricos = $ticketsAgrupados->filter(function($grupo, $key) {
                            return strpos($key, '_gano') !== false || strpos($key, '_perdio') !== false;
                        });
                    @endphp
                    
                    @if($ticketsHistoricos->count() > 0)
                        @foreach($ticketsHistoricos as $key => $grupo)
                            @php
                                $primerTicket = $grupo->first();
                                $instancia = $primerTicket->instancia;
                                $tipo = $instancia->tipo;
                                $estado = $primerTicket->estado;
                            @endphp
                            
                            <div class="card mb-4">
                                <div class="card-header {{ $estado == 'gano' ? 'bg-success' : 'bg-secondary' }} text-white">
                                    <h5 class="mb-0">{{ $tipo->nombre }} - Sorteo #{{ $instancia->id }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Fecha del sorteo:</strong> {{ $instancia->fecha_cierre->format('d/m/Y H:i') }}</p>
                                            <p><strong>Precio por ticket:</strong> ${{ number_format($tipo->precio_ticket_usd, 2) }} USD</p>
                                            <p><strong>Estado:</strong> 
                                                @if($estado == 'gano')
                                                    <span class="badge bg-success">¡Ganador!</span>
                                                @else
                                                    <span class="badge bg-secondary">No ganador</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Números:</strong> 
                                                @foreach($grupo as $ticket)
                                                    <span class="badge bg-primary me-1">{{ $ticket->numero }}</span>
                                                @endforeach
                                            </p>
                                            <p><strong>Total pagado:</strong> ${{ number_format($grupo->count() * $tipo->precio_ticket_usd, 2) }} USD</p>
                                            
                                            @if($estado == 'gano')
                                                <p><strong>Premio:</strong> $0.00 USD</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            No tienes tickets históricos en este momento.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection