<aside class="sales-side-stack">
    <section class="sales-side-card">
        <div class="side-title">
            <h3>Resumen del dia</h3>
            <span>Hoy</span>
        </div>
        <div class="day-grid">
            @foreach ($dashboard['today'] as $item)
                <article class="day-card">
                    <span>{{ $item['label'] }}</span>
                    <strong>{{ $item['value'] }}</strong>
                    <small>{{ $item['hint'] }}</small>
                </article>
            @endforeach
        </div>
    </section>

    <section class="sales-side-card">
        <div class="side-title">
            <h3>Venta seleccionada</h3>
            <span x-text="selected.invoice || 'Sin seleccion'"></span>
        </div>
        <template x-if="selected.id">
            <div>
                <div class="selected-vehicle">
                    <template x-if="selected.image">
                        <img :src="selected.image" alt="Vehiculo de la venta seleccionada">
                    </template>
                    <template x-if="!selected.image">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 15h16l-1.8-5.2A3 3 0 0 0 15.4 8H8.6a3 3 0 0 0-2.8 1.8L4 15Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M6 15v2.5M18 15v2.5M8 18h.01M16 18h.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    </template>
                </div>
                <div class="detail-list">
                    <div class="detail-line"><span>Cliente</span><strong x-text="selected.cliente"></strong></div>
                    <div class="detail-line"><span>Vehiculo</span><strong x-text="selected.vehiculo"></strong></div>
                    <div class="detail-line"><span>Placa</span><strong x-text="selected.placa || 'Sin placa'"></strong></div>
                    <div class="detail-line"><span>Total</span><strong x-text="selected.total"></strong></div>
                    <div class="detail-line"><span>Saldo</span><strong x-text="selected.saldo"></strong></div>
                    <div class="detail-line"><span>Asesor</span><strong x-text="selected.vendedor"></strong></div>
                </div>
                <div class="side-actions">
                    <button class="btn-secondary" type="button" @click="open('show', selected)">Ver detalle</button>
                    <button class="btn-primary" type="button" @click="open('abono', selected)">Registrar abono</button>
                </div>
            </div>
        </template>
        <template x-if="!selected.id">
            <p class="page-subtitle">Selecciona una venta en la tabla para ver el detalle comercial.</p>
        </template>
    </section>

    <section class="sales-side-card">
        <div class="side-title">
            <h3>Proximos cobros</h3>
            <span>Cartera</span>
        </div>
        <div class="collection-list">
            @forelse ($dashboard['upcomingCollections'] as $collection)
                <article class="collection-item">
                    <div>
                        <strong>{{ $collection['cliente'] }}</strong>
                        <span>{{ $collection['vehiculo'] }} · {{ $collection['fecha'] }}</span>
                    </div>
                    <div class="collection-amount">
                        {{ $collection['saldo'] }}
                        <span>{{ $collection['estado'] }}</span>
                    </div>
                </article>
            @empty
                <p class="page-subtitle">No hay cobros pendientes.</p>
            @endforelse
        </div>
    </section>
</aside>
