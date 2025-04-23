@csrf
<div class="mb-3">
  <label class="form-label">Nombre</label>
  <input name="nombre" class="form-control" required
         value="{{ old('nombre', $tipo->nombre ?? '') }}">
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Precio ticket USD</label>
    <input type="number" step="0.01" name="precio_ticket_usd" class="form-control" required
           value="{{ old('precio_ticket_usd', $tipo->precio_ticket_usd ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Máx. tickets</label>
    <input type="number" name="max_tickets" class="form-control" required
           value="{{ old('max_tickets', $tipo->max_tickets ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">% Payout (0‑100)</label>
    <input type="number" step="0.01" min="0" max="100" name="payout_ratio" class="form-control" required
           value="{{ old('payout_ratio', isset($tipo->payout_ratio) ? ($tipo->payout_ratio * 100) : '') }}">
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Frecuencia</label>
  <input name="frecuencia_desc" class="form-control" required
         value="{{ old('frecuencia_desc', $tipo->frecuencia_desc ?? '') }}">
</div>

<div class="mb-3">
  <label class="form-label">Descripción breve</label>
  <textarea name="descripcion_breve" class="form-control" rows="3">{{ old('descripcion_breve', $tipo->descripcion_breve ?? '') }}</textarea>
</div>

<div class="form-check mb-4">
  <input class="form-check-input" type="checkbox" name="activo" value="1"
         {{ old('activo', $tipo->activo ?? true) ? 'checked' : '' }}>
  <label class="form-check-label">Activo</label>
</div>
