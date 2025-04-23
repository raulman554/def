@extends('layouts.admin')

@section('title', 'Gestión de Retiros')

@section('header', 'Solicitudes de Retiro')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Listado de Solicitudes de Retiro</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Monto USD</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($retiros as $retiro)
                    <tr>
                        <td>{{ $retiro->id }}</td>
                        <td>{{ $retiro->usuario->nombre }} {{ $retiro->usuario->apellido }}</td>
                        <td>${{ number_format($retiro->monto_usd, 2) }}</td>
                        <td>{{ $retiro->medio_pago }}</td>
                        <td>
                            @if($retiro->estado == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @elseif($retiro->estado == 'completado')
                                <span class="badge bg-success">Completado</span>
                            @else
                                <span class="badge bg-danger">Rechazado</span>
                            @endif
                        </td>
                        <td>{{ $retiro->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($retiro->estado == 'pendiente')
                                <button type="button" class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#procesarRetiroModal{{ $retiro->id }}">
                                    <i class="bi bi-check-circle"></i> Procesar
                                </button>
                                
                                <!-- Modal para procesar el retiro -->
                                <div class="modal fade" id="procesarRetiroModal{{ $retiro->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Procesar Solicitud de Retiro #{{ $retiro->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Usuario:</strong> {{ $retiro->usuario->nombre }} {{ $retiro->usuario->apellido }}</p>
                                                <p><strong>Monto:</strong> ${{ number_format($retiro->monto_usd, 2) }}</p>
                                                <p><strong>Método:</strong> {{ $retiro->medio_pago }}</p>
                                                
                                                <form id="procesarRetiroForm{{ $retiro->id }}" action="{{ route('retiros.update', $retiro->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Acción a realizar</label>
                                                        <select name="accion" class="form-select" required>
                                                            <option value="aprobar">Aprobar y Completar</option>
                                                            <option value="rechazar">Rechazar y Devolver Saldo</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Notas / Comentarios</label>
                                                        <textarea name="notas_admin" class="form-control" rows="2"></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" form="procesarRetiroForm{{ $retiro->id }}" class="btn btn-primary">Procesar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="button" class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detalleRetiroModal{{ $retiro->id }}">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </button>
                                
                                <!-- Modal para ver detalles -->
                                <div class="modal fade" id="detalleRetiroModal{{ $retiro->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles de Retiro #{{ $retiro->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Usuario:</strong> {{ $retiro->usuario->nombre }} {{ $retiro->usuario->apellido }}</p>
                                                <p><strong>Monto:</strong> ${{ number_format($retiro->monto_usd, 2) }}</p>
                                                <p><strong>Método:</strong> {{ $retiro->medio_pago }}</p>
                                                <p><strong>Estado:</strong> 
                                                    @if($retiro->estado == 'completado')
                                                        <span class="badge bg-success">Completado</span>
                                                    @else
                                                        <span class="badge bg-danger">Rechazado</span>
                                                    @endif
                                                </p>
                                                <p><strong>Comentarios:</strong> {{ $retiro->comentarios ?: 'Sin comentarios' }}</p>
                                                <p><strong>Procesado por:</strong> Admin #{{ $retiro->id_admin_proceso }}</p>
                                                <p><strong>Fecha de procesamiento:</strong> {{ $retiro->fecha_proceso ? $retiro->fecha_proceso->format('d/m/Y H:i') : 'N/A' }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay solicitudes de retiro registradas.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $retiros->links() }}
    </div>
</div>
@endsection