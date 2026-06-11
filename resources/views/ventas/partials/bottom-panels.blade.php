<section class="sales-bottom-grid">
    <article class="sales-bottom-card">
        <div class="side-title">
            <h3>Proceso de nueva venta</h3>
            <span>Flujo comercial</span>
        </div>
        <div class="process-steps">
            <div class="process-step" data-step="1">
                <strong>Seleccionar cliente</strong>
                <span>Valida documento, datos de contacto y responsable del cierre.</span>
            </div>
            <div class="process-step" data-step="2">
                <strong>Asignar vehiculo</strong>
                <span>Usa unidades disponibles y confirma placa, precio base y estado.</span>
            </div>
            <div class="process-step" data-step="3">
                <strong>Registrar pago</strong>
                <span>Captura abono inicial, metodo, referencia y notas de recaudo.</span>
            </div>
            <div class="process-step" data-step="4">
                <strong>Facturar y seguir</strong>
                <span>Genera factura, revisa saldo y programa cobranza si aplica.</span>
            </div>
        </div>
    </article>

    <article class="sales-bottom-card">
        <div class="side-title">
            <h3>Actividad reciente</h3>
            <span>Ventas y abonos</span>
        </div>
        <div class="activity-list">
            @forelse ($dashboard['recentActivity'] as $activity)
                <article class="activity-item">
                    <div>
                        <strong>{{ $activity['title'] }}</strong>
                        <span>{{ $activity['meta'] }}</span>
                    </div>
                    <div class="activity-amount">
                        {{ $activity['amount'] }}
                        <span>{{ $activity['time'] }}</span>
                    </div>
                </article>
            @empty
                <p class="page-subtitle">No hay actividad comercial reciente.</p>
            @endforelse
        </div>
    </article>
</section>
