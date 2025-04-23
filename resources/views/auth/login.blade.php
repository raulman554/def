@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header">
          <h4 class="mb-0">Inicio de Sesión</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input id="email" type="email"
                     class="form-control @error('email') is-invalid @enderror"
                     name="email" value="{{ old('email') }}" required autofocus>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input id="password" type="password"
                     class="form-control @error('password') is-invalid @enderror"
                     name="password" required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Recordarme -->
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox"
                     name="remember" id="remember"
                     {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember">
                Recordarme
              </label>
            </div>

            <!-- Botón de login -->
            <button type="submit" class="btn btn-primary w-100">
              Iniciar Sesión
            </button>

            <!-- Enlace a recuperación de contraseña -->
            <div class="mt-3 text-center">
              <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </div>

            <!-- Enlace a registro -->
            <div class="mt-2 text-center">
              <span>¿No tienes cuenta? </span>
              <a href="{{ route('register') }}">Regístrate aquí</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
