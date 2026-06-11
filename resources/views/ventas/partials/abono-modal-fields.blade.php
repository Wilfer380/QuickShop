<div class="sale-grid">
    <div class="sale-field"><label><span>Valor</span></label><input type="text" name="valor" inputmode="numeric" autocomplete="off" placeholder="5.000" data-money-input="true" value="{{ old('valor') }}" required></div>
    <div class="sale-field"><label><span>Método</span></label><select name="metodo_pago" required><option value="efectivo">Efectivo</option><option value="tarjeta">Tarjeta</option><option value="transferencia">Transferencia</option></select></div>
</div>
<div class="sale-grid">
    <div class="sale-field"><label><span>Fecha pago</span></label><input type="datetime-local" name="pagado_at" value="{{ old('pagado_at') }}"></div>
    <div class="sale-field"><label><span>Referencia</span></label><input type="text" name="referencia" value="{{ old('referencia') }}"></div>
</div>
<div class="sale-field sale-field--full"><label><span>Notas</span></label><textarea name="notas">{{ old('notas') }}</textarea></div>
<div class="sale-actions"><button class="btn-primary" type="submit">Registrar abono</button><button class="btn-secondary" type="button" @click="abonoOpen = false">Cancelar</button></div>
