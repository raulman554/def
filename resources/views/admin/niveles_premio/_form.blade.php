@csrf
<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Nivel (1 = Mayor)</label>
    <input type="number" name="nivel" class="form-control" required
           value="{{ old('nivel', $nivel->nivel ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Cantidad de ganadores</label>
    <input type="number" name="cantidad_ganadores" class="form-control" required
           value="{{ old('cantidad_ganadores', $nivel->cantidad_ganadores ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Monto premio USD</label>
    <input type="number" step="0.01" name="monto_premio_usd" class="form-control" required
           value="{{ old('monto_premio_usd', $nivel->monto_premio_usd ?? '') }}">
  </div>
</div>
