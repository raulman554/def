@extends('layouts.app')

@section('title', $sorteo->tipo->nombre . ' - Lotoup')

@section('styles')
<style>
    .numero-selector {
        display: inline-block;
        width: 60px;
        height: 60px;
        margin: 5px;
        text-align: center;
        line-height: 60px;
        border: 1px solid #ddd;
        cursor: pointer;
        border-radius: 5px;
        font-weight: bold;
    }
    .numero-selector.selected {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .numero-selector.reservado {
        background-color: #f8f9fa;
        color: #adb5bd;
        cursor: not-allowed;
        text-decoration: line-through;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-3">{{ $sorteo->tipo->nombre }}</h1>
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Precio por ticket:</strong> ${{ number_format($sorteo->tipo->precio_ticket_usd, 2) }} USD</p>
                            <p><strong>Cierra en:</strong> {{ $sorteo->fecha_cierre->diffForHumans() }}</p>
                            <p><strong>Fecha de cierre:</strong> {{ $sorteo->fecha_cierre->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Máximo tickets:</strong> {{ $sorteo->tipo->max_tickets }}</p>
                          <p><strong>Porcentaje de pago:</strong> {{ $sorteo->tipo->payout_ratio * 100 }}%</p>
                            <p><strong>Referencia:</strong> {{ $sorteo->loteria_referencia ?? 'No especificada' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <p>{{ $sorteo->tipo->descripcion_breve }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Selecciona tus números de la suerte</h5>
                </div>
                <div class="card-body">
                    <form id="ticketForm" action="{{ route('sorteos.reservar', $sorteo->id) }}" method="POST">
                        @csrf
                        <div id="numeros-container" class="mb-4 text-center">
                            @for($i = 1; $i <= min(100, $sorteo->tipo->max_tickets); $i++)
                                <div class="numero-selector {{ in_array(strval($i), $numerosReservados) ? 'reservado' : '' }}"
                                     data-numero="{{ $i }}">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                        
                        <div id="numeros-seleccionados" class="mb-3">
                            <p>Números seleccionados: <span id="contador-seleccionados">0</span></p>
                            <div id="lista-numeros-seleccionados"></div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Selecciona uno o más números y haz clic en "Reservar". Tendrás 15 minutos para completar el pago.
                        </div>
                        
                        <div id="selected-numbers-inputs"></div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn-reservar" disabled>
                                <i class="bi bi-lock"></i> Reservar Números Seleccionados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Premios</h5>
                </div>
                <div class="card-body">
                    <p class="text-center text-muted">La información de premios se mostrará aquí pronto.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Ganadores Anteriores</h5>
                </div>
                <div class="card-body">
                    <p class="text-center text-muted">Los ganadores de sorteos anteriores se mostrarán aquí.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numeroSelectors = document.querySelectorAll('.numero-selector:not(.reservado)');
        const btnReservar = document.getElementById('btn-reservar');
        const contadorSeleccionados = document.getElementById('contador-seleccionados');
        const listaNumeros = document.getElementById('lista-numeros-seleccionados');
        const selectedNumbersInputs = document.getElementById('selected-numbers-inputs');
        const selectedNumbers = [];
        
        numeroSelectors.forEach(selector => {
            selector.addEventListener('click', function() {
                const numero = this.dataset.numero;
                
                if (this.classList.contains('selected')) {
                    // Deseleccionar
                    this.classList.remove('selected');
                    const index = selectedNumbers.indexOf(numero);
                    if (index > -1) {
                        selectedNumbers.splice(index, 1);
                    }
                } else {
                    // Seleccionar
                    this.classList.add('selected');
                    selectedNumbers.push(numero);
                }
                
                // Actualizar contador
                contadorSeleccionados.textContent = selectedNumbers.length;
                
                // Actualizar lista visible
                listaNumeros.innerHTML = selectedNumbers.length > 0 
                    ? `<p class="mb-0">Números: ${selectedNumbers.join(', ')}</p>` 
                    : '';
                
                // Actualizar inputs ocultos
                selectedNumbersInputs.innerHTML = '';
                selectedNumbers.forEach(num => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'numeros[]';
                    input.value = num;
                    selectedNumbersInputs.appendChild(input);
                });
                
                // Habilitar/deshabilitar botón
                btnReservar.disabled = selectedNumbers.length === 0;
            });
        });
    });
</script>
@endsection