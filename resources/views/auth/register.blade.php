@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header"><h4 class="mb-0">Registro de Usuario</h4></div>
        <div class="card-body">

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nombre -->
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text"
                     name="nombre"
                     class="form-control @error('nombre') is-invalid @enderror"
                     value="{{ old('nombre') }}" required>
              @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Apellido -->
            <div class="mb-3">
              <label class="form-label">Apellido</label>
              <input type="text"
                     name="apellido"
                     class="form-control @error('apellido') is-invalid @enderror"
                     value="{{ old('apellido') }}" required>
              @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input type="email"
                     name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Fecha de nacimiento -->
            <div class="mb-3">
              <label class="form-label">Fecha de nacimiento</label>
              <input type="date"
                     name="fecha_nacimiento"
                     class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                     value="{{ old('fecha_nacimiento') }}" required>
              @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <input type="password"
                     name="password"
                     class="form-control @error('password') is-invalid @enderror" required>
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Confirmar contraseña -->
            <div class="mb-4">
              <label class="form-label">Confirmar contraseña</label>
              <input type="password"
                     name="password_confirmation"
                     class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              Registrarme
            </button>

            <div class="mt-3 text-center">
              <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
