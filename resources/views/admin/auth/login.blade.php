@extends('layouts.app')

@section('title', 'Login de Administraci�n')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Panel de Administraci�n</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electr�nico</label>
                            <input id="email" type="email"
                                 class="form-control @error('email') is-invalid @enderror"
                                 name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contrase�a -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Contrase�a</label>
                            <input id="password" type="password"
                                 class="form-control @error('password') is-invalid @enderror"
                                 name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bot�n de login -->
                        <button type="submit" class="btn btn-primary w-100">
                            Acceder al Panel de Administraci�n
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection