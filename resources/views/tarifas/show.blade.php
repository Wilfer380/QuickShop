<x-app-layout>
    <x-clientes-styles />

    @php
        $state = $tarifa->displayEstado();
        $rateCards = $insights ?? [];
    @endphp

    @push('styles')
        <style>
            .tarifa-show-page{padding:24px 34px 34px;color:#f8fafc}.tarifa-show-head{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:18px}.tarifa-show-title{font-size:30px;font-weight:800;margin-bottom:4px}.tarifa-show-sub{font-size:14px;color:#94a3b8}.tarifa-show-actions{display:flex;gap:12px;flex-wrap:wrap}.tarifa-show-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(320px,.9fr);gap:18px}.tarifa-show-card,.tarifa-show-side{border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}.tarifa-show-card{padding:18px 20px}.tarifa-show-side{padding:18px 20px}.show-kpis{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:18px}.show-kpi{padding:14px;border-radius:12px;background:rgba(15,23,42,.88);border:1px solid rgba(148,163,184,.12)}.show-kpi span{display:block;color:#94a3b8;font-size:12px;margin-bottom:6px}.show-kpi strong{font-size:20px;color:#fff}.detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.detail-item{padding:14px;border-radius:12px;background:rgba(15,23,42,.88);border:1px solid rgba(148,163,184,.12)}.detail-item label{display:block;color:#94a3b8;font-size:12px;margin-bottom:6px}.detail-item strong{color:#fff;font-size:14px}.detail-full{grid-column:1/-1}.history-table{width:100%;border-collapse:collapse}.history-table th,.history-table td{padding:12px 14px;text-align:left}.history-table th{font-size:12px;color:#e2e8f0}.history-table td{border-top:1px solid rgba(148,163,184,.10);font-size:12px;color:#cbd5e1}.empty-state{padding:16px;color:#94a3b8;font-size:13px}.history-link{display:inline-block;margin-top:12px;color:#3b82f6;font-size:13px;font-weight:700;text-decoration:none}.zone-badge{display:inline-flex;align-items:center;padding:0 10px;height:24px;border-radius:999px;background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.18);color:#dbeafe;font-size:12px;font-weight:700}
            @media (max-width: 960px){.tarifa-show-grid{grid-template-columns:1fr}.show-kpis,.detail-grid{grid-template-columns:1fr}.tarifa-show-head{flex-direction:column}}
        </style>
    @endpush

    <div class="tarifa-show-page">
        <section class="tarifa-show-head">
            <div>
                <div class="tarifa-show-title">{{ $tarifa->nombre }}</div>
                <div class="tarifa-show-sub">{{ ucfirst((string) $tarifa->tipo_vehiculo) }} · {{ ucfirst((string) $tarifa->tipo_cobro) }} · <span class="zone-badge">{{ ucfirst($state) }}</span></div>
            </div>
            <div class="tarifa-show-actions">
                <a class="btn-secondary-tarifa" href="{{ route('tarifas.index') }}">Volver</a>
                <a class="btn-primary-tarifa" href="{{ route('tarifas.edit', $tarifa) }}">Editar tarifa</a>
            </div>
        </section>

        <section class="tarifa-show-grid">
            <div class="tarifa-show-card">
                <div class="show-kpis">
                    @foreach ($rateCards as $item)
                        <div class="show-kpi">
                            <span>{{ $item['label'] }}</span>
                            <strong>{{ $item['value'] }}</strong>
                        </div>
                    @endforeach
                </div>

                <div class="detail-grid">
                    <div class="detail-item"><label>Zona</label><strong>{{ $tarifa->zona ?? 'Sin zona' }}</strong></div>
                    <div class="detail-item"><label>Ícono</label><strong>{{ ucfirst((string) $tarifa->displayIcon()) }}</strong></div>
                    <div class="detail-item"><label>Estado</label><strong>{{ ucfirst($state) }}</strong></div>
                    <div class="detail-item"><label>Uso en movimientos</label><strong>{{ $tarifa->movimientos_parqueadero_count ?? 0 }}</strong></div>
                    <div class="detail-item detail-full"><label>Observaciones</label><strong>{{ $tarifa->observaciones ?? $tarifa->descripcion ?? 'Sin observaciones' }}</strong></div>
                </div>
            </div>

            <aside class="tarifa-show-side">
                <div class="panel-title">Historial de cambios</div>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historialTarifa as $item)
                            <tr>
                                <td>{{ $item->created_at?->format('d/m/Y h:i A') }}</td>
                                <td>{{ $item->user?->name ?? 'Sistema' }}</td>
                                <td>{{ ucfirst($item->accion) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><div class="empty-state">Sin registros de cambios.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
                <a class="history-link" href="{{ route('tarifas.index') }}#historial-completo">Ver historial completo</a>
            </aside>
        </section>
    </div>
</x-app-layout>
