@extends('layouts.app')

@section('title', 'Lotoup - Lotería Online')

@section('content')
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Lotería online con mejores probabilidades</h1>
            <p class="lead mb-4">
                En Lotoup ofrecemos una experiencia de lotería completamente online,
                con mejores probabilidades de ganar que las loterías tradicionales y
                premios garantizados desde $1 USD.
            </p>
            
            <div class="d-grid gap-2 d-md-flex mt-4">
                @guest('usuario')
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 me-md-2">Regístrate</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">Iniciar sesión</a>
                @else
                    <a href="{{ route('sorteos.index') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                        Ver Sorteos Disponibles
                    </a>
                @endguest
            </div>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h4 class="text-center mb-4">Próximos Sorteos</h4>
                    
                    <div class="alert alert-info"><i class="bi bi-info-circle-fill me-2"></i>
                        Los sorteos estarán disponibles pronto. ¡Regístrate para ser el primero en participar!
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Características -->
    <div class="row mt-5 pt-5">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">¿Por qué elegir Lotoup?</h2>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-trophy text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Mejores Probabilidades</h5>
                    <p class="card-text">
                        Ofrecemos mejores probabilidades de ganar que las loterías tradicionales.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-laptop text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">100% Online</h5>
                    <p class="card-text">
                        Participa desde cualquier lugar, sin necesidad de comprar boletos físicos.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-currency-dollar text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="card-title">Desde $1 USD</h5>
                    <p class="card-text">
                        Participa con una inversión mínima y gana premios garantizados.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection