@extends('layouts.app')

@section('title', 'Completar Pago - Lotoup')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Completar Pago</h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <h5 class="mb-4">Detalles de tu compra:</h5>
                    
                    <div class="mb-4">
                        <p><strong>Sorteo:</strong> {{ $sorteo->tipo->nombre }}</p>
                        <p><strong>Números seleccionados:</strong> {{ implode(', ', $numeros) }}</p>
                        <p><strong>Total a pagar:</strong> ${{ number_format($total, 2) }} USD</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h5><i class="bi bi-exclamation-triangle-fill me-2"></i> ¡Importante!</h5>
                        <p>Tienes <strong id="countdown">15:00</strong> minutos para completar tu pago o tus números serán liberados.</p>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Instrucciones de Pago</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordionPagos">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            <i class="bi bi-credit-card me-2"></i> Pago con Tarjeta
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionPagos">
                                        <div class="accordion-body">
                                            <p>Para pagar con tarjeta, realiza una transferencia a:</p>
                                            <p><strong>Número de tarjeta:</strong> 4152 3156 8945 7812</p>
                                            <p><strong>Titular:</strong> Lotoup Inc.</p>
                                            <p>Una vez realizado el pago, guarda una captura de pantalla o comprobante.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            <i class="bi bi-shop me-2"></i> Pago en OXXO
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionPagos">
                                        <div class="accordion-body">
                                            <p>Para pagar en OXXO, utiliza la siguiente referencia:</p>
                                            <p><strong>Referencia:</strong> 9356 7812 3451</p>
                                            <p>Una vez realizado el pago, toma una foto del recibo.</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                            <i class="bi bi-currency-bitcoin me-2"></i> Pago con USDT
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionPagos">
                                        <div class="accordion-body">
                                            <p>Para pagar con USDT, envía la cantidad exacta a la siguiente dirección:</p>
                                            <p><strong>Dirección USDT (TRC20):</strong> <code>TJkSU87xYTR8Jy2NKrSpJKZo7SgGNsbwC9</code></p>
                                            <p>Una vez realizado el pago, guarda el hash de la transacción.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('sorteos.comprobante', $sorteo->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @foreach($numeros as $numero)
                            <input type="hidden" name="numeros[]" value="{{ $numero }}">
                        @endforeach
                        <input type="hidden" name="total" value="{{ $total }}">
                        
                        <div class="mb-3">
                            <label for="comprobante" class="form-label">Subir Comprobante de Pago</label>
                            <input class="form-control @error('comprobante') is-invalid @enderror" 
                                   type="file" id="comprobante" name="comprobante" required>
                            @error('comprobante')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Formatos aceptados: JPG, PNG, PDF. Tamaño máximo: 5MB.</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cloud-upload"></i> Subir Comprobante y Finalizar
                            </button>
                            <a href="{{ route('mis-tickets') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Contador regresivo
    document.addEventListener('DOMContentLoaded', function() {
        let timeLeft = 15 * 60; // 15 minutos en segundos
        const countdownEl = document.getElementById('countdown');
        
        const countdownInterval = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                alert('El tiempo ha expirado. Tus números han sido liberados.');
                window.location.href = '{{ route("sorteos.index") }}';
                return;
            }
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            timeLeft--;
        }, 1000);
    });
</script>
@endsection