@csrf
<div class="mb-3">
    <label class="form-label">Tipo de Sorteo</label>
    <select name="tipo_sorteo_id" class="form-select" required>
        <option value="">Seleccione un tipo de sorteo</option>
        @foreach($tipos as $tipo)
            <option value="{{ $tipo->id }}" 
                    {{ old('tipo_sorteo_id', $instancia->tipo_sorteo_id ?? '') == $tipo->id ? 'selected' : '' }}>
                {{ $tipo->nombre }} (${{ number_format($tipo->precio_ticket_usd, 2) }})
            </option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Fecha y Hora de Inicio</label>
        <input type="datetime-local" name="fecha_inicio" class="form-control" required
               value="{{ old('fecha_inicio', isset($instancia) ? $instancia->fecha_inicio->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Fecha y Hora de Cierre</label>
        <input type="datetime-local" name="fecha_cierre" class="form-control" required
               value="{{ old('fecha_cierre', isset($instancia) ? $instancia->fecha_cierre->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Lotería de Referencia</label>
    <input type="text" name="loteria_referencia" class="form-control"
           value="{{ old('loteria_referencia', $instancia->loteria_referencia ?? '') }}">
    <div class="form-text">Ejemplo: "Lotería Nacional de México, Sorteo Mayor #3789"</div>
</div>

<div class="mb-3">
    <label class="form-label">Estado</label>
    <select name="estado" class="form-select" required>
        <option value="pendiente" {{ old('estado', $instancia->estado ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        <option value="abierta" {{ old('estado', $instancia->estado ?? '') == 'abierta' ? 'selected' : '' }}>Abierta</option>
        <option value="cerrada" {{ old('estado', $instancia->estado ?? '') == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
    </select>
</div>