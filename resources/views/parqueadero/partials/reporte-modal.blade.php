<div x-cloak x-show="reportOpen" x-transition.opacity class="modal-backdrop" @keydown.escape.window="reportOpen = false">
    <div class="modal-card modal-card--small" @click.outside="reportOpen = false">
        <div class="modal-head">
            <div>
                <h3>Reporte de parqueadero</h3>
                <p>Resumen operativo del día, mes y zonas.</p>
            </div>
            <button type="button" class="modal-close" @click="reportOpen = false">×</button>
        </div>

        <div class="modal-body">
            <div class="modal-grid">
                <div class="field"><label>Ingresos del día</label><input type="text" value="$1.248.000" readonly></div>
                <div class="field"><label>Ingresos del mes</label><input type="text" value="$18.650.000" readonly></div>
                <div class="field"><label>Vehículos activos</label><input type="text" value="78" readonly></div>
                <div class="field"><label>Ocupación total</label><input type="text" value="65%" readonly></div>
                <div class="field"><label>PDF</label><input type="text" value="Disponible desde reportes" readonly></div>
                <div class="field"><label>Excel</label><input type="text" value="Disponible desde reportes" readonly></div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="modal-secondary" onclick="window.print()">Imprimir</button>
            <a href="{{ route('reportes.index') }}" class="modal-secondary">Ir a reportes</a>
            <button type="button" class="modal-primary" @click="reportOpen = false">Cerrar</button>
        </div>
    </div>
</div>
