<div x-cloak x-show="exitOpen" x-transition.opacity class="modal-backdrop" @keydown.escape.window="exitOpen = false">
    <div class="modal-card modal-card--small" @click.outside="exitOpen = false">
        <div class="modal-head">
            <div>
                <h3>Registrar salida</h3>
                <p>Finaliza el movimiento, calcula el tiempo y registra el pago.</p>
            </div>
            <button type="button" class="modal-close" @click="exitOpen = false">×</button>
        </div>

        @isset($movimiento)
            <form method="POST" action="{{ route('parqueadero.salida', $movimiento) }}" id="salida-form">
                @csrf
                <div class="modal-body">
                    <div class="modal-grid">
                        <label class="field">
                            <span>Fecha y hora de salida</span>
                            <input type="datetime-local" name="salida_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                        </label>

                        <label class="field">
                            <span>Método de pago</span>
                            <select name="metodo_pago">
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </label>

                        <label class="field">
                            <span>Valor recibido</span>
                            <input type="text" name="pago_salida" inputmode="numeric" autocomplete="off" placeholder="0" data-money-input="true" value="0">
                        </label>

                        <label class="field">
                            <span>Referencia</span>
                            <input type="text" name="referencia" placeholder="Comprobante o referencia">
                        </label>

                        <label class="field" style="grid-column:1/-1">
                            <span>Observaciones</span>
                            <textarea name="observaciones" rows="4"></textarea>
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="modal-secondary" @click="exitOpen = false">Cancelar</button>
                    <button type="submit" class="modal-primary">Confirmar salida</button>
                </div>
            </form>
        @else
            <div class="modal-body">
                <div class="modal-grid">
                    <div class="field"><label>Placa</label><input type="text" :value="selectedRow ? selectedRow.plate : ''" readonly></div>
                    <div class="field"><label>Cliente</label><input type="text" :value="selectedRow ? selectedRow.client : ''" readonly></div>
                    <div class="field"><label>Zona</label><input type="text" :value="selectedRow ? selectedRow.zone : ''" readonly></div>
                    <div class="field"><label>Espacio</label><input type="text" :value="selectedRow ? selectedRow.slot : ''" readonly></div>
                </div>
                <p style="color:#94a3b8;margin:14px 0 0">La salida real se registra desde la vista del movimiento. Este panel es una vista previa rápida.</p>
            </div>
        @endisset
    </div>
</div>
