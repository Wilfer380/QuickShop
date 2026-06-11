<x-app-layout>
    <x-clientes-styles />
    @push('styles')
        <style>
            .parking-page{padding:24px 34px 34px;color:#f8fafc}.parking-header{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:22px}.page-title{font-size:30px;font-weight:800;color:#f8fafc;margin-bottom:4px}.page-subtitle{font-size:14px;color:#94a3b8}.parking-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap}.btn-secondary-parking{height:44px;padding:0 20px;border-radius:10px;background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0;font-size:14px;font-weight:700;display:inline-flex;align-items:center;gap:8px;text-decoration:none}.panel-card{border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}.parking-main-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(360px,.9fr);gap:18px;align-items:start}.parking-map-card{padding:18px 20px}.parking-map-header{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:18px}.panel-title{font-size:18px;font-weight:700;color:#f8fafc}.parking-legend{display:flex;align-items:center;gap:14px;font-size:12px;color:#cbd5e1;flex-wrap:wrap}.legend-dot{width:10px;height:10px;border-radius:999px;display:inline-block;margin-right:6px}.legend-row{display:inline-flex;align-items:center}.status-content{display:grid;grid-template-columns:150px 1fr;gap:18px;align-items:center}.status-legend{display:flex;flex-direction:column;gap:12px}.status-legend-item,.recent-entry-row{display:flex;justify-content:space-between;align-items:center;gap:10px;color:#cbd5e1;font-size:13px}.status-legend-item strong{color:#fff}.current-parking-card,.parking-list-card,.parking-status-card{padding:18px}.recent-entry-row{padding:11px 0;border-bottom:1px solid rgba(148,163,184,.10)}.recent-entry-row:last-child{border-bottom:0}.entry-icon{width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;flex:none;background:rgba(34,197,94,.14);color:#22c55e}.row-main{display:flex;align-items:center;gap:10px;min-width:0;flex:1}.row-main strong{color:#fff;font-weight:800;white-space:nowrap}.row-main span{color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.row-time{white-space:nowrap;color:#cbd5e1}.modal-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.field{display:grid;gap:6px}.field label,.field span{font-size:12px;font-weight:700;color:#cbd5e1}.field input,.field select,.field textarea{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.18);color:#e2e8f0;padding:0 14px;outline:none}.field textarea{height:auto;min-height:94px;padding:12px 14px;resize:vertical}.modal-footer{display:flex;justify-content:flex-end;gap:10px;padding:0 20px 20px}.modal-secondary,.modal-primary{height:42px;padding:0 18px;border-radius:10px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.modal-secondary{background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0}.modal-primary{background:linear-gradient(90deg,#2563eb,#7c3aed);color:#fff}.value-active{color:#fff;font-weight:700}.time-active{color:#22c55e;font-weight:700}.parking-right-column{display:grid;gap:18px}.status-chart{position:relative;height:150px}.status-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center;pointer-events:none}.status-center strong{display:block;font-size:32px;font-weight:800;color:#fff;line-height:1}.status-center span{font-size:14px;color:#94a3b8}.panel-title a{color:#60a5fa;text-decoration:none;font-size:13px}.panel-card .panel-title{margin-bottom:10px}.sidebar-help-card{display:grid;gap:12px;background:rgba(15,23,42,.72);border:1px solid rgba(148,163,184,.12);border-radius:18px;padding:12px}.sidebar-help-card__copy{display:grid;gap:8px;color:#e2e8f0}.sidebar-help-card__copy strong{font-size:16px;color:#fff}.sidebar-help-card__copy span{color:#94a3b8;font-size:13px;line-height:1.5}.sidebar-help-card__button{height:42px;border-radius:10px;background:linear-gradient(90deg,#2563eb,#7c3aed);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;text-decoration:none}.sidebar-footer__profile{display:flex;align-items:center;gap:12px;margin-top:6px;padding-top:14px;border-top:1px solid rgba(148,163,184,.12)}.sidebar-footer__avatar{width:40px;height:40px;border-radius:999px;background:linear-gradient(135deg,#7c3aed,#3b82f6);display:grid;place-items:center;font-weight:800;color:#fff;flex:none}.sidebar-footer__meta strong{display:block;color:#fff}.sidebar-footer__meta span,.sidebar-footer__meta p{color:#94a3b8;margin:0;font-size:13px}.sidebar-footer__meta p{display:flex;align-items:center;gap:8px}.sidebar-footer__car{width:100%;max-width:220px;margin:0 auto;display:block;filter:drop-shadow(0 18px 18px rgba(0,0,0,.35));opacity:.92;border-radius:18px}.sidebar-status-dot{width:10px;height:10px;border-radius:999px;background:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.12);display:inline-block}
            @media (max-width: 1200px){.parking-main-grid{grid-template-columns:1fr}.modal-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
            @media (max-width: 640px){.parking-header{flex-direction:column;gap:14px}.parking-main-grid{gap:14px}.status-content{grid-template-columns:1fr}.modal-grid{grid-template-columns:1fr}}
        </style>
    @endpush
    <div class="parking-page">
        <section class="parking-header">
            <div>
                <h1 class="page-title">Movimiento #{{ $movimiento->id }}</h1>
                <p class="page-subtitle">{{ $movimiento->vehiculo?->placa ?? 'Sin placa' }} · {{ $movimiento->vehiculo?->marca ?? 'Sin marca' }} {{ $movimiento->vehiculo?->modelo ?? '' }}</p>
            </div>
            <div class="parking-actions">
                <a class="btn-secondary-parking" href="{{ route('parqueadero.index') }}">Volver al tablero</a>
            </div>
        </section>

        <section class="parking-main-grid">
            <div>
                <section class="panel-card parking-map-card">
                    <div class="parking-map-header">
                        <div class="panel-title">Detalle operativo</div>
                        <div class="parking-legend"><span class="legend-row"><i class="legend-dot" style="background:#22c55e"></i>Activo</span><span class="legend-row"><i class="legend-dot" style="background:#f59e0b"></i>Salida pendiente</span></div>
                    </div>

                    <div class="modal-grid">
                        <div class="field"><label>Placa</label><input type="text" value="{{ $movimiento->vehiculo?->placa ?? '-' }}" readonly></div>
                        <div class="field"><label>Cliente</label><input type="text" value="{{ trim(($movimiento->cliente?->nombres ?? '') . ' ' . ($movimiento->cliente?->apellidos ?? '')) ?: 'Sin cliente' }}" readonly></div>
                        <div class="field"><label>Zona</label><input type="text" value="{{ $movimiento->cupo?->zona ? 'Zona ' . $movimiento->cupo->zona : 'Sin zona' }}" readonly></div>
                        <div class="field"><label>Espacio</label><input type="text" value="{{ $movimiento->cupo?->codigo ?? 'Sin cupo' }}" readonly></div>
                        <div class="field"><label>Ingreso</label><input type="text" value="{{ optional($movimiento->entrada_at)->format('d/m/Y H:i') }}" readonly></div>
                        <div class="field"><label>Estado</label><input type="text" value="{{ ucfirst($movimiento->estado) }}" readonly></div>
                        <div class="field"><label>Total</label><input type="text" value="{{ $movimiento->total ? '$' . number_format((float) $movimiento->total, 0, ',', '.') : 'Pendiente' }}" readonly></div>
                        <div class="field"><label>Tiempo</label><input type="text" value="{{ $movimiento->minutos ? floor($movimiento->minutos / 60) . 'h ' . str_pad((string) ($movimiento->minutos % 60), 2, '0', STR_PAD_LEFT) . 'm' : 'Activo' }}" readonly></div>
                    </div>
                </section>

                <section class="panel-card current-parking-card" id="salida-form">
                    <div class="current-parking-header">
                        <div class="panel-title">Registrar salida</div>
                    </div>

                    <form method="POST" action="{{ route('parqueadero.salida', $movimiento) }}">
                        @csrf
                        <div class="modal-grid" style="padding:0 20px 20px">
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
                                <input type="text" name="referencia" placeholder="Comprobante o transferencia">
                            </label>
                            <label class="field" style="grid-column:1/-1">
                                <span>Observaciones</span>
                                <textarea name="observaciones" rows="4" placeholder="Notas adicionales"></textarea>
                            </label>
                        </div>

                        <div class="modal-footer" style="padding-top:0">
                            <button type="submit" class="modal-primary">Confirmar salida</button>
                        </div>
                    </form>
                </section>
            </div>

            <aside class="parking-right-column">
                <section class="panel-card parking-status-card">
                    <div class="panel-title" style="margin-bottom:14px">Ticket</div>
                    <div class="status-legend">
                        <div class="status-legend-item"><span>Vehículo</span><strong>{{ $movimiento->vehiculo?->placa ?? '—' }}</strong></div>
                        <div class="status-legend-item"><span>Cliente</span><strong>{{ trim(($movimiento->cliente?->nombres ?? '') . ' ' . ($movimiento->cliente?->apellidos ?? '')) ?: '—' }}</strong></div>
                        <div class="status-legend-item"><span>Tarifa</span><strong>{{ $movimiento->tarifa?->nombre ?? '—' }}</strong></div>
                        <div class="status-legend-item"><span>Observaciones</span><strong>{{ $movimiento->observaciones ?: 'Sin notas' }}</strong></div>
                    </div>
                </section>

                <section class="panel-card parking-list-card">
                    <div class="panel-title" style="margin-bottom:10px">Pagos</div>
                    @forelse ($movimiento->pagos as $pago)
                        <div class="recent-entry-row">
                            <div class="entry-icon">$</div>
                            <div class="row-main"><strong>${{ number_format((float) $pago->valor, 0, ',', '.') }}</strong><span>{{ ucfirst($pago->metodo_pago) }}</span></div>
                            <div class="row-time">{{ optional($pago->pagado_at)->format('d/m/Y H:i') }}</div>
                        </div>
                    @empty
                        <p style="margin:0;color:#94a3b8">Sin pagos registrados.</p>
                    @endforelse
                </section>
            </aside>
        </section>
    </div>
</x-app-layout>
