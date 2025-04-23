@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Completar / Editar Perfil</h4>
    </div>
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

      <form method="POST" action="{{ route('mi-perfil.update') }}">
        @csrf
        
        <div class="row mb-4">
          <div class="col-md-6 mb-3 mb-md-0">
            <!-- Nombre -->
            <label class="form-label">Nombre</label>
            <input type="text"
                  name="nombre"
                  class="form-control"
                  value="{{ old('nombre', $usuario->nombre) }}"
                  readonly>
            <small class="text-muted">No se puede cambiar el nombre</small>
          </div>
          <div class="col-md-6">
            <!-- Apellido -->
            <label class="form-label">Apellido</label>
            <input type="text"
                  name="apellido"
                  class="form-control"
                  value="{{ old('apellido', $usuario->apellido) }}"
                  required>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6 mb-3 mb-md-0">
            <!-- Email -->
            <label class="form-label">Email</label>
            <input type="email"
                  name="email"
                  class="form-control"
                  value="{{ old('email', $usuario->email) }}"
                  readonly>
            <small class="text-muted">El email no se puede cambiar</small>
          </div>
          <div class="col-md-6">
            <!-- Número de WhatsApp -->
            <label class="form-label">Número de WhatsApp</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
              <input type="text"
                     name="numero_whatsapp"
                     class="form-control"
                     placeholder="Ej: +521234567890"
                     value="{{ old('numero_whatsapp', $usuario->numero_whatsapp) }}">
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6 mb-3 mb-md-0">
            <!-- Fecha de nacimiento -->
            <label class="form-label">Fecha de nacimiento</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-calendar"></i></span>
              <input type="date"
                     name="fecha_nacimiento"
                     class="form-control"
                     value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}">
            </div>
          </div>
          <div class="col-md-6">
            <!-- ID Binance -->
            <label class="form-label">ID de Binance (opcional)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-currency-bitcoin"></i></span>
              <input type="text"
                     name="id_binance"
                     class="form-control"
                     placeholder="Tu identificador de Binance"
                     value="{{ old('id_binance', $usuario->id_binance) }}">
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Guardar cambios
          </button>
          <a href="{{ route('mi-perfil') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a mi perfil
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection