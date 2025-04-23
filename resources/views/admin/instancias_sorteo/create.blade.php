@extends('layouts.admin')
@section('header', 'Nueva Instancia de Sorteo')
@section('content')

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('instancias-sorteo.store') }}">
      @csrf
      
      <div class="mb-3">
        <label class="form-label fw-bold">Tipo de Sorteo</label>
        <select name="tipo_sorteo_id" class="form-select" required>
          <option value="">Seleccione un tipo de sorteo...</option>
          @foreach($tipos as $t)
            <option value="{{ $t->id }}" {{ old('tipo_sorteo_id') == $t->id ? 'selected' : '' }}>
              {{ $t->nombre }} - ${{ number_format($t->precio_ticket_usd, 2) }} USD
            </option>
          @endforeach
        </select>
      </div>
      
<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label fw-bold">Fecha y Hora de Inicio</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-calendar-plus"></i></span>
      <input type="datetime-local" name="fecha_inicio" class="form-control" required 
            value="{{ old('fecha_inicio') }}" min="{{ date('Y-m-d\TH:i') }}">
    </div>
    <small class="text-muted">Fecha a partir de la cual se abrirá la venta de tickets</small>
  </div>
  
  <div class="col-md-6 mb-3">
    <label class="form-label fw-bold">Fecha y Hora de Cierre</label>
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-calendar-x"></i></span>
      <input type="datetime-local" name="fecha_cierre" class="form-control" required
            value="{{ old('fecha_cierre') }}" min="{{ date('Y-m-d\TH:i') }}">
    </div>
    <small class="text-muted">Fecha en la que se cerrará la venta de tickets (debe ser posterior a la fecha de inicio)</small>
  </div>
</div>
      
      <div class="mb-3">
        <label class="form-label fw-bold">Lotería de Referencia</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-ticket-perforated"></i></span>
          <input type="text" name="loteria_referencia" class="form-control"
                value="{{ old('loteria_referencia') }}" placeholder="Ej: Lotería Nacional - Sorteo Especial #123">
        </div>
        <small class="text-muted">Referencia opcional a la lotería externa cuyos resultados se utilizarán</small>
      </div>
      
      <div class="mb-4">
        <label class="form-label fw-bold">Estado Inicial</label>
        <div class="d-flex">
          <div class="form-check me-4">
            <input class="form-check-input" type="radio" name="estado" id="estado_pendiente" 
                  value="pendiente" {{ old('estado', 'pendiente') == 'pendiente' ? 'checked' : '' }}>
            <label class="form-check-label" for="estado_pendiente">
              <span class="badge bg-warning">Pendiente</span>
            </label>
          </div>
          
          <div class="form-check me-4">
            <input class="form-check-input" type="radio" name="estado" id="estado_abierta" 
                  value="abierta" {{ old('estado') == 'abierta' ? 'checked' : '' }}>
            <label class="form-check-label" for="estado_abierta">
              <span class="badge bg-success">Abierta</span>
            </label>
          </div>
          
          <div class="form-check">
            <input class="form-check-input" type="radio" name="estado" id="estado_cerrada" 
                  value="cerrada" {{ old('estado') == 'cerrada' ? 'checked' : '' }}>
            <label class="form-check-label" for="estado_cerrada">
              <span class="badge bg-danger">Cerrada</span>
            </label>
          </div>
        </div>
      </div>
      
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle"></i> Guardar Instancia de Sorteo
        </button>
        <a href="{{ route('instancias-sorteo.index') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
      </div>
    </form>
  </div>
</div>

<script>
  // Script para validar fechas
  document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
    const fechaCierre = document.querySelector('input[name="fecha_cierre"]');
    
    // Actualizar fecha cierre mínima cuando cambia fecha inicio
    fechaInicio.addEventListener('change', function() {
      if (this.value) {
        // Convertir a objetos Date para comparación
        const fechaInicioDate = new Date(this.value);
        
        // Añadir un minuto a la fecha de inicio para el mínimo de fecha de cierre
        fechaInicioDate.setMinutes(fechaInicioDate.getMinutes() + 1);
        const minFechaCierre = fechaInicioDate.toISOString().slice(0, 16);
        
        fechaCierre.min = minFechaCierre;
        
        // Verificar si la fecha de cierre actual es anterior a la nueva fecha mínima
        const fechaCierreDate = fechaCierre.value ? new Date(fechaCierre.value) : null;
        if (fechaCierreDate && fechaCierreDate < fechaInicioDate) {
          fechaCierre.value = minFechaCierre;
        }
      }
    });
    
    // Validar al enviar el formulario
    document.querySelector('form').addEventListener('submit', function(e) {
      if (fechaInicio.value && fechaCierre.value) {
        const fechaInicioDate = new Date(fechaInicio.value);
        const fechaCierreDate = new Date(fechaCierre.value);
        
        if (fechaInicioDate >= fechaCierreDate) {
          e.preventDefault();
          alert('La fecha de cierre debe ser posterior a la fecha de inicio');
          fechaCierre.focus();
        }
      }
    });
    
    // Inicializar al cargar la página
    if (fechaInicio.value) {
      fechaInicio.dispatchEvent(new Event('change'));
    }
  });
</script>
@endsection