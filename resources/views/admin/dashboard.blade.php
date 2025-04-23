@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Tipos de Sorteo</h5>
        <p class="card-text">Gestiona los diferentes tipos de sorteo disponibles.</p>
        <a href="{{ route('tipos-sorteo.index') }}" class="btn btn-primary">Administrar</a>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Instancias de Sorteo</h5>
        <p class="card-text">Crea y administra las instancias activas de sorteos.</p>
        <a href="{{ route('instancias-sorteo.index') }}" class="btn btn-primary">Administrar</a>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Tickets Pendientes</h5>
        <p class="card-text">Verifica y gestiona los tickets con pagos pendientes.</p>
        <a href="{{ route('admin.tickets.pendientes') }}" class="btn btn-primary">Administrar</a>
      </div>
    </div>
  </div>
</div>
@endsection