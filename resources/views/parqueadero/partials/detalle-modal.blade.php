<div x-cloak x-show="detailOpen" x-transition.opacity class="modal-backdrop" @keydown.escape.window="detailOpen = false">
    <div class="modal-card modal-card--small" @click.outside="detailOpen = false">
        <div class="modal-head">
            <div>
                <h3>Detalle del espacio</h3>
                <p>Consulta el estado, la zona y la acción disponible.</p>
            </div>
            <button type="button" class="modal-close" @click="detailOpen = false">×</button>
        </div>

        <div class="modal-body">
            <template x-if="selectedRow">
                <div class="modal-grid">
                    <div class="field"><label>Placa</label><input type="text" :value="selectedRow.plate" readonly></div>
                    <div class="field"><label>Cliente</label><input type="text" :value="selectedRow.client" readonly></div>
                    <div class="field"><label>Zona</label><input type="text" :value="selectedRow.zone" readonly></div>
                    <div class="field"><label>Espacio</label><input type="text" :value="selectedRow.slot" readonly></div>
                    <div class="field"><label>Ingreso</label><input type="text" :value="selectedRow.entry" readonly></div>
                    <div class="field"><label>Estado</label><input type="text" value="Activo" readonly></div>
                </div>
            </template>

            <template x-if="!selectedRow && selectedSlot">
                <div class="modal-grid">
                    <div class="field"><label>Código</label><input type="text" :value="selectedSlot.code" readonly></div>
                    <div class="field"><label>Estado</label><input type="text" :value="selectedSlot.state" readonly></div>
                    <div class="field" style="grid-column:1/-1"><label>Acción</label><input type="text" value="Pulsa Nuevo ingreso para abrir este espacio" readonly></div>
                </div>
            </template>
        </div>

        <div class="modal-footer">
            <button type="button" class="modal-secondary" @click="detailOpen = false">Cerrar</button>
            <button type="button" class="modal-primary" @click="newOpen = true; detailOpen = false">Nuevo ingreso</button>
        </div>
    </div>
</div>
