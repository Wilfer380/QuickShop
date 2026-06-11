<x-app-layout>
    <x-clientes-styles />

    @php
        $stateLabels = ['activa' => 'Activa', 'inactiva' => 'Inactiva'];
        $stateClasses = ['activa' => 'badge-active', 'inactiva' => 'badge-inactive'];
        $vehicleIcons = [
            'carro' => ['color' => '#3B82F6', 'label' => '🚗'],
            'automovil' => ['color' => '#3B82F6', 'label' => '🚗'],
            'moto' => ['color' => '#22C55E', 'label' => '🏍'],
            'motocicleta' => ['color' => '#22C55E', 'label' => '🏍'],
            'camioneta' => ['color' => '#7C3AED', 'label' => '🚙'],
            'camion' => ['color' => '#F97316', 'label' => '🚚'],
            'bicicleta' => ['color' => '#F59E0B', 'label' => '🚲'],
            'otro' => ['color' => '#94A3B8', 'label' => '⛔'],
        ];
        $zonesPalette = ['#3B82F6', '#22C55E', '#F97316', '#7C3AED', '#F59E0B'];
        $count = method_exists($tarifas, 'total') ? $tarifas->total() : $tarifas->count();
    @endphp

    @push('styles')
        <style>
            .tarifas-page{padding:24px 34px 34px;color:#f8fafc}.tarifas-header{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:22px}.page-title{font-size:30px;font-weight:800;color:#f8fafc;margin-bottom:4px}.page-subtitle{font-size:14px;color:#94a3b8}.tarifas-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap}.btn-primary-tarifa{height:44px;padding:0 22px;border-radius:10px;background:linear-gradient(90deg,#2563EB,#7C3AED);color:#fff;font-size:14px;font-weight:700;display:inline-flex;align-items:center;gap:8px;box-shadow:0 12px 26px rgba(37,99,235,.28);text-decoration:none;border:0}.btn-secondary-tarifa{height:44px;padding:0 20px;border-radius:10px;background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0;font-size:14px;font-weight:600;display:inline-flex;align-items:center;gap:8px;text-decoration:none}.tarifas-kpi-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:18px}.tarifas-kpi-card,.tarifas-table-card,.tarifa-config-card,.zone-rates-card,.tarifa-history-card{border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}.tarifas-kpi-card{min-height:112px;padding:18px 20px;display:flex;align-items:center;gap:16px}.tarifas-kpi-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:800}.tarifas-kpi-label{font-size:13px;color:#e2e8f0;font-weight:700}.tarifas-kpi-value{font-size:26px;font-weight:800;color:#fff;line-height:1.1}.tarifas-kpi-subtitle{font-size:12px;color:#94a3b8}.tarifas-main-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(360px,.9fr);gap:18px;align-items:start}.tarifas-table-card{overflow:hidden}.tarifas-table-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px 12px;gap:12px;flex-wrap:wrap}.tarifas-table-title{font-size:18px;font-weight:800;color:#f8fafc}.tarifas-table-tools{display:flex;gap:10px;align-items:center;flex-wrap:wrap}.tarifas-search,.tarifas-filter{background:rgba(15,23,42,.9);border:1px solid rgba(148,163,184,.16);border-radius:10px;color:#f8fafc;padding:11px 14px;font-size:14px;outline:none}.tarifas-search{min-width:260px}.tarifas-filter{min-width:190px}.tarifas-table-wrap{overflow:auto}.tarifas-table{width:100%;border-collapse:collapse;min-width:1060px}.tarifas-table th{padding:13px 14px;text-align:left;color:#e2e8f0;font-size:12px;font-weight:700}.tarifas-table td{padding:14px;color:#cbd5e1;font-size:13px;border-top:1px solid rgba(148,163,184,.10)}.tarifas-table tbody tr:hover{background:rgba(59,130,246,.05)}.vehicle-type-cell{display:flex;align-items:center;gap:10px}.vehicle-type-icon{width:34px;height:34px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:18px;font-weight:800}.badge{display:inline-flex;align-items:center;height:24px;padding:0 10px;border-radius:7px;font-size:12px;font-weight:700}.badge-active{color:#4ade80;background:rgba(34,197,94,.18)}.badge-inactive{color:#f87171;background:rgba(239,68,68,.18)}.action-buttons{display:flex;gap:6px;align-items:center}.action-btn{width:30px;height:30px;border-radius:7px;background:rgba(15,23,42,.80);border:1px solid rgba(148,163,184,.16);color:#cbd5e1;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px}.action-btn.edit{color:#f59e0b;border-color:rgba(245,158,11,.30)}.action-btn.copy{color:#3b82f6;border-color:rgba(59,130,246,.30)}.action-btn.delete{color:#ef4444;border-color:rgba(239,68,68,.30)}.table-footer{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;color:#94a3b8;font-size:13px;gap:12px;flex-wrap:wrap}.pagination{display:flex;gap:6px;align-items:center;flex-wrap:wrap}.page-btn{height:34px;min-width:34px;padding:0 12px;border-radius:8px;background:rgba(15,23,42,.80);border:1px solid rgba(148,163,184,.16);color:#cbd5e1;display:inline-flex;align-items:center;justify-content:center;text-decoration:none}.page-btn.active{background:linear-gradient(90deg,#2563EB,#7C3AED);color:#fff;border-color:transparent}.panel-card{padding:18px 20px}.panel-title{font-size:18px;font-weight:800;color:#f8fafc;margin-bottom:14px}.config-list,.zone-list,.history-list{display:flex;flex-direction:column}.config-item,.zone-rate-row,.history-row{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:14px 0;border-bottom:1px solid rgba(148,163,184,.10)}.config-label,.zone-info,.history-main{display:flex;align-items:center;gap:10px;color:#e2e8f0;font-size:13px;font-weight:700}.config-value,.zone-rate,.history-detail{color:#cbd5e1;font-size:13px;text-align:right}.config-ico,.zone-dot,.history-dot{width:10px;height:10px;border-radius:999px;display:inline-block}.btn-edit-config{width:100%;height:40px;margin-top:16px;border-radius:9px;background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#3b82f6;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.history-table{width:100%;border-collapse:collapse}.history-table th{padding:12px 14px;color:#e2e8f0;font-size:12px;font-weight:700;text-align:left}.history-table td{padding:12px 14px;color:#cbd5e1;font-size:12px;border-top:1px solid rgba(148,163,184,.10)}.history-link{display:inline-block;margin-top:14px;color:#3b82f6;font-size:13px;font-weight:700;text-decoration:none}.zone-link{display:inline-block;margin-top:10px;color:#3b82f6;font-size:13px;font-weight:700;text-decoration:none}.empty-state{padding:20px;color:#94a3b8;font-size:13px}.tarifa-history-card{margin-top:18px}.tarifas-right-col{display:flex;flex-direction:column}.search-form{display:flex;gap:10px;flex-wrap:wrap;align-items:center;width:100%}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
            @media (max-width:1200px){.tarifas-main-grid{grid-template-columns:1fr}.tarifas-kpi-grid{grid-template-columns:repeat(2,1fr)}}
            @media (max-width:640px){.tarifas-page{padding:18px}.tarifas-kpi-grid{grid-template-columns:1fr}.tarifas-header{flex-direction:column;gap:14px}.tarifas-actions{width:100%;flex-direction:column}.btn-primary-tarifa,.btn-secondary-tarifa{width:100%;justify-content:center}.tarifas-search,.tarifas-filter{width:100%}}
        </style>
    @endpush

    <div class="tarifas-page">
        <section class="tarifas-header">
            <div>
                <div class="page-title">Tarifas</div>
                <div class="page-subtitle">Gestiona las tarifas de parqueadero por tipo de vehículo, tiempo y zona</div>
            </div>
            <div class="tarifas-actions">
                <a class="btn-primary-tarifa" href="{{ route('tarifas.create') }}">+ Nueva tarifa</a>
                <a class="btn-secondary-tarifa" href="{{ route('cupos.index') }}">Configurar zonas</a>
            </div>
        </section>

        <section class="tarifas-kpi-grid">
            @foreach ($metrics as $metric)
                <article class="tarifas-kpi-card">
                    <div class="tarifas-kpi-icon" style="background:linear-gradient(180deg,{{ ['blue' => '#2563EB', 'green' => '#16A34A', 'orange' => '#F97316', 'purple' => '#7C3AED', 'teal' => '#14B8A6'][$metric['tone']] ?? '#3B82F6' }}, rgba(255,255,255,.08))">
                        {{ ['car' => '🚗', 'money' => '◉', 'calendar' => '▣', 'tag' => '🏷', 'chart' => '↗'][$metric['icon']] ?? '•' }}
                    </div>
                    <div>
                        <div class="tarifas-kpi-label">{{ $metric['label'] }}</div>
                        <div class="tarifas-kpi-value">{{ $metric['value'] }}</div>
                        <div class="tarifas-kpi-subtitle">{{ $metric['subtitle'] }}</div>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="tarifas-main-grid">
            <div>
                <section class="tarifas-table-card">
                    <div class="tarifas-table-header">
                        <div class="tarifas-table-title">Tarifas por tipo de vehículo</div>
                        <form class="search-form" method="GET" action="{{ route('tarifas.index') }}">
                            <label class="sr-only" for="q">Buscar tipo de vehículo</label>
                            <input id="q" name="q" class="tarifas-search" placeholder="Buscar tipo de vehículo…" value="{{ $search }}">
                            <select name="estado" class="tarifas-filter">
                                <option value="todos" @selected($estado === 'todos')>Todos los estados</option>
                                <option value="activa" @selected($estado === 'activa')>Activas</option>
                                <option value="inactiva" @selected($estado === 'inactiva')>Inactivas</option>
                            </select>
                            <button class="btn-secondary-tarifa" type="submit" style="height:42px">Filtrar</button>
                        </form>
                    </div>

                    <div class="tarifas-table-wrap">
                        <table class="tarifas-table">
                            <thead>
                                <tr>
                                    <th>Tipo de vehículo</th>
                                    <th>Icono</th>
                                    <th>Tarifa por minuto</th>
                                    <th>Tarifa por hora</th>
                                    <th>Tarifa día completo</th>
                                    <th>Tarifa noche (12AM - 6AM)</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tarifas as $tarifa)
                                    @php
                                        $icon = $vehicleIcons[strtolower((string) $tarifa->displayIcon())] ?? $vehicleIcons['otro'];
                                        $estadoReal = $tarifa->displayEstado();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="vehicle-type-cell">
                                                <strong style="color:#fff">{{ ucfirst((string) $tarifa->tipo_vehiculo) }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="vehicle-type-icon" style="background:rgba(255,255,255,.06);color:{{ $icon['color'] }}">{{ $icon['label'] }}</span>
                                        </td>
                                        <td>{{ $money = '$' . number_format((float) $tarifa->baseMinute(), 0, ',', '.') }}</td>
                                        <td>{{ '$' . number_format((float) $tarifa->baseHour(), 0, ',', '.') }}</td>
                                        <td>{{ '$' . number_format((float) $tarifa->baseDay(), 0, ',', '.') }}</td>
                                        <td>{{ '$' . number_format((float) $tarifa->baseNight(), 0, ',', '.') }}</td>
                                        <td><span class="badge {{ $stateClasses[$estadoReal] ?? 'badge-inactive' }}">{{ $stateLabels[$estadoReal] ?? ucfirst($estadoReal) }}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a class="action-btn edit" href="{{ route('tarifas.edit', $tarifa) }}" title="Editar">✎</a>
                                                <form method="POST" action="{{ route('tarifas.duplicate', $tarifa) }}">
                                                    @csrf
                                                    <button class="action-btn copy" type="submit" title="Duplicar">⧉</button>
                                                </form>
                                                <form method="POST" action="{{ route('tarifas.destroy', $tarifa) }}" onsubmit="return confirm('¿Inactivar o eliminar esta tarifa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="action-btn delete" type="submit" title="Eliminar/Inactivar">🗑</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8"><div class="empty-state">No hay tarifas registradas.</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <div>Mostrando {{ $tarifas->firstItem() ?? 0 }} a {{ $tarifas->lastItem() ?? 0 }} de {{ $count }} registros</div>
                        <div class="pagination">
                            @if ($tarifas->onFirstPage())
                                <span class="page-btn">Anterior</span>
                            @else
                                <a class="page-btn" href="{{ $tarifas->previousPageUrl() }}">Anterior</a>
                            @endif

                            <span class="page-btn active">{{ $tarifas->currentPage() }}</span>

                            @if ($tarifas->hasMorePages())
                                <a class="page-btn" href="{{ $tarifas->nextPageUrl() }}">Siguiente</a>
                            @else
                                <span class="page-btn">Siguiente</span>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="tarifa-history-card" id="historial-completo">
                    <div class="panel-card">
                        <div class="panel-title">Historial de cambios en tarifas</div>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Descripción</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($historialTarifas as $item)
                                    <tr>
                                        <td>{{ $item->created_at?->format('d/m/Y h:i A') }}</td>
                                        <td>{{ $item->user?->name ?? 'Sistema' }}</td>
                                        <td>{{ ucfirst($item->accion) }}</td>
                                        <td>{{ $item->datos_nuevos['nombre'] ?? $item->datos_anteriores['nombre'] ?? 'Tarifa actualizada' }}</td>
                                        <td>
                                            @php
                                                $before = $item->datos_anteriores['tarifa_hora'] ?? $item->datos_anteriores['valor'] ?? null;
                                                $after = $item->datos_nuevos['tarifa_hora'] ?? $item->datos_nuevos['valor'] ?? null;
                                            @endphp
                                            {{ $before !== null && $after !== null && $before !== $after ? 'De $' . number_format((float) $before, 0, ',', '.') . ' a $' . number_format((float) $after, 0, ',', '.') : ($item->datos_nuevos['estado'] ?? 'Tarifa registrada') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5"><div class="empty-state">Sin registros de cambios.</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <a class="history-link" href="#historial-completo">Ver historial completo</a>
                    </div>
                </section>
            </div>

            <aside class="tarifas-right-col">
                <section class="tarifa-config-card panel-card">
                    <div class="panel-title">Configuración de tarifas</div>
                    <div class="config-list">
                        @foreach ($configuracionTarifas as $item)
                            <div class="config-item">
                                <div class="config-label"><span class="config-ico" style="background:{{ ['clock' => '#3B82F6', 'timer' => '#22C55E', 'arrow-right' => '#F97316', 'ticket' => '#7C3AED', 'percent' => '#14B8A6'][$item['icon']] ?? '#3B82F6' }}"></span>{{ $item['label'] }}</div>
                                <div class="config-value">{{ $item['value'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <a class="btn-primary-tarifa btn-edit-config" href="{{ route('configuracion.index') }}" style="width:100%;justify-content:center;margin-top:14px;">Editar configuración</a>
                </section>

                <section class="zone-rates-card panel-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:10px">
                        <div class="panel-title" style="margin:0">Tarifas por zona</div>
                        <a class="zone-link" href="{{ route('cupos.index') }}">Ver detalle</a>
                    </div>
                    <div class="zone-list">
                        @forelse ($tarifasPorZona as $index => $zone)
                            <div class="zone-rate-row">
                                <div class="zone-info">
                                    <span class="zone-dot" style="background:{{ $zone['color'] ?? $zonesPalette[$index % count($zonesPalette)] }}"></span>
                                    <div>
                                        <div class="zone-name">{{ $zone['name'] }}</div>
                                        <div class="zone-subtitle">{{ $zone['subtitle'] }}</div>
                                    </div>
                                </div>
                                <div class="zone-rate">{{ $zone['rate'] }} / hora</div>
                            </div>
                        @empty
                            <div class="empty-state">No hay zonas configuradas todavía.</div>
                        @endforelse
                    </div>
                </section>
            </aside>
        </section>
    </div>
</x-app-layout>
