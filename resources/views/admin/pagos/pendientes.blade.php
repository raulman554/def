@extends('layouts.admin')

@section('title', 'Tickets Pendientes')

@section('header', 'Verificación de Pagos')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Comprobantes Pendientes de Verificación</h5>
    </div>
    <div class="card-body">
        @if($comprobantes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Tickets</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comprobantes as $comprobante)
                            <tr>
                                <td>{{ $comprobante->id }}</td>
                                <td>{{ $comprobante->usuario->nombre }} {{ $comprobante->usuario->apellido }}</td>
                                <td>${{ number_format($comprobante->monto_esperado_usd, 2) }}</td>
                                <td>{{ $comprobante->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ Storage::url($comprobante->ruta_archivo) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-file-earmark"></i> Ver
                                    </a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ticketsModal{{ $comprobante->id }}">
                                        <i class="bi bi-ticket-perforated"></i> {{ $comprobante->tickets->count() }} tickets
                                    </button>
                                    
                                    <!-- Modal de tickets -->
                                    <div class="modal fade" id="ticketsModal{{ $comprobante->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tickets del Comprobante #{{ $comprobante->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Sorteo</th>
                                                                    <th>Número</th>
                                                                    <th>Precio</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($comprobante->tickets as $ticket)
                                                                    <tr>
                                                                        <td>{{ $ticket->id }}</td>
                                                                        <td>{{ $ticket->instancia->tipo->nombre }}</td>
                                                                        <td>{{ $ticket->numero }}</td>
                                                                        <td>${{ number_format($ticket->precio_usd, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('admin.tickets.aprobar', $comprobante->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Está seguro de aprobar este comprobante?')">
                                                <i class="bi bi-check-circle"></i> Aprobar
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rechazarModal{{ $comprobante->id }}">
                                            <i class="bi bi-x-circle"></i> Rechazar
                                        </button>
                                        
                                        <!-- Modal de rechazo -->
                                        <div class="modal fade" id="rechazarModal{{ $comprobante->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rechazar Comprobante #{{ $comprobante->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.tickets.rechazar', $comprobante->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Motivo del rechazo</label>
                                                                <textarea name="motivo_rechazo" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                            <div class="alert alert-warning">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                                Al rechazar, los tickets serán liberados y volverán a estar disponibles.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Rechazar Comprobante</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $comprobantes->links() }}
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                No hay comprobantes pendientes de verificación.
            </div>
        @endif
    </div>
</div>
@endsection