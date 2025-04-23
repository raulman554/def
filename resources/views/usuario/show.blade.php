@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h2>Mi Perfil</h2>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <p><strong>Nombre:</strong> {{ $usuario->nombre }} {{ $usuario->apellido }}</p>
      <p><strong>Email:</strong> {{ $usuario->email }}</p>

      @if($usuario->numero_whatsapp)
        <p><strong>WhatsApp:</strong> {{ $usuario->numero_whatsapp }}</p>
      @endif

      @if($usuario->fecha_nacimiento)
        <p><strong>Fecha de nacimiento:</strong>
           {{ \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('d/m/Y') }}
        </p>
      @endif

      @if($usuario->id_binance)
        <p><strong>ID Binance:</strong> {{ $usuario->id_binance }}</p>
      @endif

      <p><strong>Saldo USD:</strong> ${{ number_format($usuario->saldo_usd, 2) }}</p>

      <p><strong>Perfil completo:</strong>
         {{ $usuario->perfil_completo ? 'Sí' : 'No' }}
      </p>
    </div>
  </div>

  <a href="{{ route('mi-perfil.editar') }}" class="btn btn-primary">
    Completar / Editar perfil
  </a>
</div>
@endsection
