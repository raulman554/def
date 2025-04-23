@extends('layouts.app')

@section('title', 'Solicitar Retiro - Lotoup')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Solicitar Retiro</h1>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Formulario de Retiro</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('retiro.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Saldo Disponible</label>
                            <div class="form-control bg-light">${{ number_format($usuario->saldo_usd, 2) }} USD</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="monto_usd" class="form-label">Monto a Retirar (USD)</label>
                            <input type="number" step="0.01" min="10" max="{{ $usuario->saldo_usd }}" 
                                  class="form-control @error('monto_usd') is-invalid @enderror" 
                                  id="monto_usd" name="monto_usd" required
                                  value="{{ old('monto_usd') }}">
                            <div class="form-text">Mínimo: $10.00 USD. Máximo: ${{ number_format($usuario->saldo_usd, 2) }} USD</div>
                            @error('monto_usd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="medio_pago" class="form-label">Método de Pago</label>
                            <select class="form-select @error('medio_pago') is-invalid @enderror" 
                                   id="medio_pago" name="medio_pago" required>
                                <option value="">Selecciona un método</option>
                                <option value="binance" {{ old('medio_pago') == 'binance' ? 'selected' : '' }}>Binance (USDT)</option>
                                <option value="banco" {{ old('medio_pago') == 'banco' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="oxxo" {{ old('medio_pago') == 'oxxo' ? 'selected' : '' }}>OXXO</option>
                            </select>
                            @error('medio_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="detalles_pago" class="form-label">Detalles del Pago</label>
                            <textarea class="form-control @error('detalles_pago') is-invalid @enderror" 
                                     id="detalles_pago" name="detalles_pago" rows="3" required>{{ old('detalles_pago') }}</textarea>
                            <div class="form-text" id="detalles_ayuda">Añade los datos necesarios según el método seleccionado.</div>
                            @error('detalles_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Los retiros pueden tardar hasta 72 horas hábiles en ser procesados.
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cash-coin"></i> Solicitar Retiro
                            </button>
                            <a href="{{ route('mi-perfil') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Instrucciones</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="bi bi-currency-bitcoin me-2"></i> Binance (USDT)</h6>
                        <p class="small">Proporciona tu ID de Binance o dirección de wallet para recibir USDT (TRC20).</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-bank me-2"></i> Transferencia Bancaria</h6>
                        <p class="small">Ingresa el nombre del banco, CLABE interbancaria o número de cuenta, y nombre del titular.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6><i class="bi bi-shop me-2"></i> OXXO</h6>
                        <p class="small">Proporciona el nombre completo de la persona que recogerá el dinero y su número de teléfono.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const medioPago = document.getElementById('medio_pago');
        const detallesAyuda = document.getElementById('detalles_ayuda');
        
        medioPago.addEventListener('change', function() {
            switch(this.value) {
                case 'binance':
                    detallesAyuda.textContent = 'Ingresa tu ID de Binance o dirección de wallet para recibir USDT (TRC20).';
                    break;
                case 'banco':
                    detallesAyuda.textContent = 'Ingresa el nombre del banco, CLABE interbancaria o número de cuenta, y nombre del titular.';
                    break;
                case 'oxxo':
                    detallesAyuda.textContent = 'Proporciona el nombre completo de la persona que recogerá el dinero y su número de teléfono.';
                    break;
                default:
                    detallesAyuda.textContent = 'Añade los datos necesarios según el método seleccionado.';
            }
        });
    });
</script>
@endsection