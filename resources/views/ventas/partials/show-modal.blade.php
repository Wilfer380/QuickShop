<div x-cloak x-show="showOpen" class="client-modal-backdrop" @keydown.escape.window="showOpen = false">
    <div class="client-modal" @click.outside="showOpen = false">
        <div class="client-modal__head">
            <div><h2>Venta #<span x-text="selected.id"></span></h2><p x-text="`${selected.cliente} · ${selected.vehiculo}`"></p></div>
            <button class="client-modal__close" type="button" @click="showOpen = false">×</button>
        </div>
        <div class="client-modal__body client-modal__section">
            <div class="sale-detail-grid">
                <div><span>Cliente</span><strong x-text="selected.cliente"></strong></div>
                <div><span>Documento</span><strong x-text="selected.documento"></strong></div>
                <div><span>Vehículo</span><strong x-text="selected.vehiculo"></strong></div>
                <div><span>Placa</span><strong x-text="selected.placa || 'Sin placa'"></strong></div>
                <div><span>Total</span><strong x-text="selected.total"></strong></div>
                <div><span>Saldo</span><strong x-text="selected.saldo"></strong></div>
                <div><span>Estado</span><strong x-text="selected.estado"></strong></div>
                <div><span>Vendedor</span><strong x-text="selected.vendedor"></strong></div>
            </div>
            <div class="sale-actions"><a class="btn-primary" :href="`{{ url('/panel/ventas') }}/${selected.id}`">Abrir detalle</a><button class="btn-secondary" type="button" @click="showOpen = false">Cerrar</button></div>
        </div>
    </div>
</div>
