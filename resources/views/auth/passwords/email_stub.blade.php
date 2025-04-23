@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header"><h4>Recuperar contraseña</h4></div>
        <div class="card-body">
          @if(session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
          @endif

          <p>Esta funcionalidad está en desarrollo. Muy pronto podrás solicitar un enlace para restablecer tu contraseña.</p>

          <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input id="email" type="email"
                     class="form-control"
                     name="email"
                     placeholder="tu@correo.com"
                     required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
              Enviar petición
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
