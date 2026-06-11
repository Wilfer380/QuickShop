<x-app-layout>
    <x-clientes-styles />
    @php
        $kpis = $kpis ?? [
            ['label' => 'Espacios totales', 'value' => 120, 'subtitle' => 'Distribuidos en 3 zonas', 'tone' => 'blue', 'icon' => 'car'],
            ['label' => 'Ocupados', 'value' => 78, 'subtitle' => '65% del total', 'tone' => 'green', 'icon' => 'trend'],
            ['label' => 'Disponibles', 'value' => 42, 'subtitle' => '35% del total', 'tone' => 'orange', 'icon' => 'clock'],
            ['label' => 'Ingresos hoy', 'value' => '$1.248.000', 'subtitle' => '36 vehículos', 'tone' => 'violet', 'icon' => 'calendar'],
            ['label' => 'Ingresos del mes', 'value' => '$18.650.000', 'subtitle' => '11.5% vs. mes anterior', 'tone' => 'teal', 'icon' => 'money'],
        ];

        $slotStateClasses = [
            'disponible' => 'slot-available',
            'occupied' => 'slot-occupied',
            'ocupado' => 'slot-occupied',
            'available' => 'slot-available',
            'reserved' => 'slot-reserved',
            'reservado' => 'slot-reserved',
            'maintenance' => 'slot-maintenance',
            'mantenimiento' => 'slot-maintenance',
        ];

        $zoneTitles = [
            'A' => 'Zona A - Planta Baja',
            'B' => 'Zona B - Sótano 1',
            'C' => 'Zona C - Sótano 2',
        ];

        $parkingSlotGroups = $cupos
            ->groupBy(fn ($cupo) => strtoupper((string) $cupo->zona))
            ->map(function ($items, $zona) use ($zoneTitles, $activeMovements) {
                return [
                    'title' => $zoneTitles[$zona] ?? 'Zona ' . $zona,
                    'slots' => $items->values()->map(function ($cupo) use ($activeMovements) {
                        $movement = $activeMovements->get($cupo->codigo);

                        $state = $movement
                            ? 'occupied'
                            : (in_array(strtolower((string) $cupo->estado), ['reserved', 'reservado', 'maintenance', 'mantenimiento'], true)
                                ? strtolower((string) $cupo->estado)
                                : 'available');

                        return [
                            'code' => $cupo->codigo,
                            'state' => $state,
                            'plate' => $movement?->vehiculo?->placa,
                            'image' => $movement?->vehiculo?->imagen ? route('vehiculos.imagen', $movement->vehiculo) : null,
                            'movement_id' => $movement?->id,
                            'cliente' => trim(($movement?->cliente?->nombres ?? '') . ' ' . ($movement?->cliente?->apellidos ?? '')),
                            'model' => trim(($movement?->vehiculo?->marca ?? '') . ' ' . ($movement?->vehiculo?->modelo ?? '')),
                        ];
                    })->all(),
                ];
            })->all();

        $recentEntries = $recentEntries ?? collect();

        $upcomingExits = $upcomingExits ?? collect();

        $parkingChartData = [
            $parkingStats['occupied'] ?? 0,
            $parkingStats['available'] ?? 0,
            $parkingStats['reserved'] ?? 0,
            $parkingStats['maintenance'] ?? 0,
        ];

        $tableRows = $movimientos->count()
            ? $movimientos->getCollection()->map(function ($movimiento) {
                $cliente = trim(($movimiento->cliente?->nombres ?? '') . ' ' . ($movimiento->cliente?->apellidos ?? '')) ?: ($movimiento->vehiculo?->cliente?->nombres ?? 'Cliente sin nombre');

                return [
                    'id' => $movimiento->id,
                    'plate' => $movimiento->vehiculo->placa ?? 'SIN-PLACA',
                    'client' => $cliente,
                    'zone' => $movimiento->cupo?->zona ? ('Zona ' . $movimiento->cupo->zona) : 'Zona A',
                    'slot' => $movimiento->cupo?->codigo ?? 'A01',
                    'entry' => optional($movimiento->entrada_at)->format('d/m/Y H:i') ?? '30/05/2025 08:15',
                    'type' => ucfirst($movimiento->vehiculo->tipo ?? 'Carro'),
                    'time' => $movimiento->minutos ? floor($movimiento->minutos / 60) . 'h ' . str_pad((string) ($movimiento->minutos % 60), 2, '0', STR_PAD_LEFT) . 'm' : '-',
                    'value' => $movimiento->total ? '$' . number_format((float) $movimiento->total, 0, ',', '.') : '-',
                    'demo' => false,
                ];
            })
            : collect();
    @endphp

    @push('styles')
        <style>
            .parking-page{padding:24px 34px 34px;color:#f8fafc}
            .parking-header{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:22px}
            .page-title{font-size:30px;font-weight:800;color:#f8fafc;margin-bottom:4px}
            .page-subtitle{font-size:14px;color:#94a3b8}
            .parking-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
            .btn-primary-parking,.btn-secondary-parking{height:44px;padding:0 20px;border-radius:10px;display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:700;text-decoration:none}
            .btn-primary-parking{background:linear-gradient(90deg,#2563eb,#7c3aed);color:#fff;box-shadow:0 12px 26px rgba(37,99,235,.28)}
            .btn-secondary-parking{background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0}
            .parking-kpi-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:14px;margin-bottom:18px}
            .parking-kpi-card{min-height:112px;padding:18px 20px;border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18);display:flex;align-items:center;gap:16px}
            .parking-kpi-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;flex:none}
            .parking-kpi-card__copy{min-width:0}
            .parking-kpi-label{font-size:13px;color:#e2e8f0;font-weight:700}
            .parking-kpi-value{font-size:26px;font-weight:800;color:#fff;line-height:1.1}
            .parking-kpi-subtitle{font-size:12px;color:#94a3b8;margin-top:4px}
            .bg-blue{background:#2563eb}.bg-green{background:#22c55e}.bg-orange{background:#f97316}.bg-violet{background:#7c3aed}.bg-teal{background:#14b8a6}
            .parking-main-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(360px,.9fr);gap:18px;align-items:start}
            .panel-card{border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}
            .parking-map-card{padding:18px 20px}
            .parking-map-header{display:flex;justify-content:space-between;align-items:center;gap:14px;margin-bottom:18px}
            .panel-title{font-size:18px;font-weight:700;color:#f8fafc}
            .parking-legend{display:flex;align-items:center;gap:14px;font-size:12px;color:#cbd5e1;flex-wrap:wrap}
            .legend-dot{width:10px;height:10px;border-radius:999px;display:inline-block;margin-right:6px}
            .legend-row{display:inline-flex;align-items:center}
            .map-filter{height:36px;border-radius:10px;background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.18);color:#e2e8f0;padding:0 12px}
            .zone-title{font-size:14px;font-weight:700;color:#e2e8f0;margin:18px 0 10px}
            .parking-zone-grid{display:grid;grid-template-columns:repeat(10,minmax(0,1fr));gap:10px}
            .parking-slot{min-height:52px;border-radius:8px;background:rgba(15,23,42,.70);border:1px solid rgba(148,163,184,.16);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;font-size:12px;font-weight:700;cursor:pointer;transition:all .2s ease;min-width:0}
            .parking-slot:hover{transform:translateY(-1px)}
            .parking-slot span{font-size:12px;line-height:1}
            .parking-slot--occupied{min-height:90px;padding:8px 8px 7px;justify-content:space-between}
            .parking-slot__vehicle{width:100%;height:34px;display:grid;place-items:center;overflow:hidden;border-radius:7px;background:rgba(2,6,23,.35)}
            .parking-slot__vehicle img{width:100%;height:100%;object-fit:cover;display:block;border-radius:inherit}
            .parking-slot__vehicle svg{width:18px;height:18px}
            .parking-slot__plate{width:100%;padding:3px 6px;border-radius:6px;background:rgba(127,29,29,.88);color:#fff;font-size:11px;font-weight:800;line-height:1.1;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
            .parking-slot__status{width:100%;padding:2px 4px;border-radius:6px;font-size:10px;font-weight:800;text-align:center;letter-spacing:.04em;text-transform:uppercase}
            .parking-slot__status--occupied{background:rgba(220,38,38,.18);color:#ef4444}
            .slot-occupied{color:#ef4444;border-color:rgba(239,68,68,.70);background:rgba(239,68,68,.10)}
            .slot-available{color:#22c55e;border-color:rgba(34,197,94,.70);background:rgba(34,197,94,.10)}
            .slot-reserved{color:#f59e0b;border-color:rgba(245,158,11,.70);background:rgba(245,158,11,.10)}
            .slot-maintenance{color:#3b82f6;border-color:rgba(59,130,246,.70);background:rgba(59,130,246,.10)}
            .parking-map-note{margin-top:8px;color:#94a3b8;font-size:12px}
            .parking-right-column{display:grid;gap:18px}
            .parking-status-card,.parking-list-card,.current-parking-card{padding:18px}
            .status-content{display:grid;grid-template-columns:150px 1fr;gap:18px;align-items:center}
            .status-chart{position:relative;height:150px}
            .status-center{position:absolute;inset:0;display:grid;place-items:center;text-align:center;pointer-events:none}
            .status-center strong{display:block;font-size:32px;font-weight:800;color:#fff;line-height:1}
            .status-center span{font-size:14px;color:#94a3b8}
            .status-legend{display:flex;flex-direction:column;gap:12px}
            .status-legend-item,.recent-entry-row,.exit-row{display:flex;justify-content:space-between;align-items:center;gap:10px;color:#cbd5e1;font-size:13px}
            .status-legend-item strong{color:#fff}
            .recent-entry-row,.exit-row{padding:11px 0;border-bottom:1px solid rgba(148,163,184,.10)}
            .recent-entry-row:last-child,.exit-row:last-child{border-bottom:0}
            .entry-icon,.exit-icon{width:24px;height:24px;border-radius:7px;display:flex;align-items:center;justify-content:center;flex:none}
            .entry-icon{background:rgba(34,197,94,.14);color:#22c55e}
            .exit-icon{background:rgba(245,158,11,.14);color:#f59e0b}
            .row-main{display:flex;align-items:center;gap:10px;min-width:0;flex:1}
            .row-main strong{color:#fff;font-weight:800;white-space:nowrap}
            .row-main span{color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
            .row-time,.row-slot{white-space:nowrap;color:#cbd5e1}
            .current-parking-card{margin-top:18px;overflow:hidden}
            .current-parking-header{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:18px 20px 12px}
            .current-parking-tools{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
            .parking-search{height:40px;min-width:280px;border-radius:10px;background:rgba(15,23,42,.80);border:1px solid rgba(148,163,184,.16);color:#cbd5e1;padding:0 14px}
            .parking-state-filter{height:40px;border-radius:10px;background:rgba(15,23,42,.80);border:1px solid rgba(148,163,184,.16);color:#cbd5e1;padding:0 14px}
            .current-parking-table-wrap{overflow:auto}
            .current-parking-table{width:100%;border-collapse:collapse;min-width:1100px}
            .current-parking-table th{padding:13px 14px;text-align:left;color:#e2e8f0;font-size:12px;font-weight:700}
            .current-parking-table td{padding:13px 14px;color:#cbd5e1;font-size:12px;border-top:1px solid rgba(148,163,184,.10)}
            .current-parking-table tbody tr:hover{background:rgba(59,130,246,.05)}
            .time-active{color:#22c55e;font-weight:700}
            .value-active{color:#fff;font-weight:700}
            .action-group{display:flex;gap:8px;align-items:center}
            .icon-btn{width:32px;height:32px;border-radius:8px;border:1px solid rgba(148,163,184,.16);background:rgba(15,23,42,.85);color:#cbd5e1;display:grid;place-items:center;text-decoration:none}
            .icon-btn--danger{color:#ef4444}
            .table-footer{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;color:#94a3b8;font-size:13px;gap:12px;flex-wrap:wrap}
            .pagination{display:flex;gap:6px;align-items:center;flex-wrap:wrap}
            .page-btn{height:34px;min-width:34px;padding:0 12px;border-radius:8px;background:rgba(15,23,42,.80);border:1px solid rgba(148,163,184,.16);color:#cbd5e1;display:inline-flex;align-items:center;justify-content:center;text-decoration:none}
            .page-btn.active{background:linear-gradient(90deg,#2563eb,#7c3aed);color:#fff}
            .modal-backdrop{position:fixed;inset:0;background:rgba(2,6,23,.72);display:grid;place-items:center;z-index:80;padding:18px}
            .modal-card{width:min(980px,100%);border-radius:18px;background:linear-gradient(180deg,rgba(17,24,39,.98),rgba(15,23,42,.98));border:1px solid rgba(148,163,184,.16);box-shadow:0 36px 88px rgba(0,0,0,.42);overflow:hidden}
            .modal-card--small{width:min(760px,100%)}
            .modal-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;padding:18px 20px;border-bottom:1px solid rgba(148,163,184,.12)}
            .modal-head h3{margin:0;font-size:20px;font-weight:800;color:#fff}
            .modal-head p{margin:4px 0 0;color:#94a3b8;font-size:13px}
            .modal-close{width:38px;height:38px;border-radius:12px;border:1px solid rgba(148,163,184,.16);background:rgba(15,23,42,.86);color:#e2e8f0;display:grid;place-items:center;font-size:22px}
            .modal-body{padding:20px}
            .modal-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
            .field{display:grid;gap:6px}
            .field label{font-size:12px;font-weight:700;color:#cbd5e1}
            .field input,.field select,.field textarea{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.18);color:#e2e8f0;padding:0 14px;outline:none}
            .field textarea{height:auto;min-height:94px;padding:12px 14px;resize:vertical}
            .modal-footer{display:flex;justify-content:flex-end;gap:10px;padding:0 20px 20px}
            .modal-secondary,.modal-primary{height:42px;padding:0 18px;border-radius:10px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}
            .modal-secondary{background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0}
            .modal-primary{background:linear-gradient(90deg,#2563eb,#7c3aed);color:#fff}
            .sidebar-help-card{display:grid;gap:12px;background:rgba(15,23,42,.72);border:1px solid rgba(148,163,184,.12);border-radius:18px;padding:12px}
            .sidebar-help-card__copy{display:grid;gap:8px;color:#e2e8f0}
            .sidebar-help-card__copy strong{font-size:16px;color:#fff}
            .sidebar-help-card__copy span{color:#94a3b8;font-size:13px;line-height:1.5}
            .sidebar-help-card__button{height:42px;border-radius:10px;background:linear-gradient(90deg,#2563eb,#7c3aed);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;text-decoration:none}
            .sidebar-footer__profile{display:flex;align-items:center;gap:12px;margin-top:6px;padding-top:14px;border-top:1px solid rgba(148,163,184,.12)}
            .sidebar-footer__avatar{width:40px;height:40px;border-radius:999px;background:linear-gradient(135deg,#7c3aed,#3b82f6);display:grid;place-items:center;font-weight:800;color:#fff;flex:none}
            .sidebar-footer__meta strong{display:block;color:#fff}
            .sidebar-footer__meta span,.sidebar-footer__meta p{color:#94a3b8;margin:0;font-size:13px}
            .sidebar-footer__meta p{display:flex;align-items:center;gap:8px}
            @media (max-width: 1200px){.parking-main-grid{grid-template-columns:1fr}.parking-kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.parking-zone-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
            @media (max-width: 640px){.parking-kpi-grid{grid-template-columns:1fr}.parking-zone-grid{grid-template-columns:repeat(3,minmax(0,1fr))}.parking-header{flex-direction:column;gap:14px}.current-parking-header{flex-direction:column;align-items:flex-start}.parking-search{min-width:0;width:100%}.parking-main-grid{gap:14px}.status-content{grid-template-columns:1fr}.modal-grid{grid-template-columns:1fr}}
        </style>
    @endpush

    <div class="parking-page" x-data="{ newOpen:false, reportOpen:false, detailOpen:false, exitOpen:false, selectedSlot:null, selectedRow:null }">
        <section class="parking-header">
            <div>
                <h1 class="page-title">Parqueaderos</h1>
                <p class="page-subtitle">Administra los espacios de parqueo, ingresos y salidas</p>
            </div>

            <div class="parking-actions">
                <button type="button" class="btn-primary-parking" @click="newOpen = true">
                    <span>＋</span> <span>Nuevo ingreso</span>
                </button>
                <button type="button" class="btn-secondary-parking" @click="reportOpen = true">
                    <span>⧉</span> <span>Reporte de parqueadero</span>
                </button>
            </div>
        </section>

        <section class="parking-kpi-grid">
            @foreach ($kpis as $kpi)
                <article class="parking-kpi-card">
                    <div class="parking-kpi-icon bg-{{ $kpi['tone'] }}">
                        @if ($kpi['icon'] === 'car')
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 14h14l-1.2-4.2A2 2 0 0 0 16.9 8H7.1a2 2 0 0 0-1.9 1.8L5 14Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="8" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/><circle cx="16" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/></svg>
                        @elseif ($kpi['icon'] === 'trend')
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 16l5-5 4 4 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 8h4v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @elseif ($kpi['icon'] === 'clock')
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.7"/><path d="M12 7.5V12l3 2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @elseif ($kpi['icon'] === 'calendar')
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3v3M17 3v3M4 8h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M5 8h9a4 4 0 0 1 0 8H9a4 4 0 0 1 0-8h10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </div>
                    <div class="parking-kpi-card__copy">
                        <div class="parking-kpi-label">{{ $kpi['label'] }}</div>
                        <div class="parking-kpi-value">{{ $kpi['value'] }}</div>
                        <div class="parking-kpi-subtitle">{{ $kpi['subtitle'] }}</div>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="parking-main-grid">
            <div>
                <section class="panel-card parking-map-card">
                    <div class="parking-map-header">
                        <div class="panel-title">Mapa de parqueaderos</div>
                        <div class="parking-legend">
                            <span class="legend-row"><i class="legend-dot" style="background:#ef4444"></i>Ocupado</span>
                            <span class="legend-row"><i class="legend-dot" style="background:#22c55e"></i>Disponible</span>
                            <span class="legend-row"><i class="legend-dot" style="background:#f59e0b"></i>Reservado</span>
                            <span class="legend-row"><i class="legend-dot" style="background:#3b82f6"></i>Zona / Mantenimiento</span>
                            <select class="map-filter" aria-label="Filtrar mapa">
                                <option>Zona A</option>
                                <option>Zona B</option>
                                <option>Zona C</option>
                            </select>
                        </div>
                    </div>

                    @foreach ($parkingSlotGroups as $zone)
                        <div class="zone-title">{{ $zone['title'] }}</div>
                        <div class="parking-zone-grid">
                            @foreach ($zone['slots'] as $slot)
                                @php
                                    $occupied = $slot['state'] === 'occupied';
                                @endphp
                                <button
                                    type="button"
                                    class="parking-slot {{ $slotStateClasses[$slot['state']] }} {{ $occupied ? 'parking-slot--occupied' : '' }}"
                                    @click="selectedSlot = @js($slot); detailOpen = true"
                                >
                                    @if ($occupied)
                                        <div class="parking-slot__vehicle">
                                            @if (!empty($slot['image']))
                                                <img src="{{ $slot['image'] }}" alt="{{ $slot['plate'] }}">
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 14h14l-1.2-4.2A2 2 0 0 0 16.9 8H7.1a2 2 0 0 0-1.9 1.8L5 14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="8" cy="17" r="1.2" stroke="currentColor" stroke-width="1.6"/><circle cx="16" cy="17" r="1.2" stroke="currentColor" stroke-width="1.6"/></svg>
                                            @endif
                                        </div>
                                        <div class="parking-slot__plate">{{ $slot['plate'] ?? $slot['code'] }}</div>
                                        <div class="parking-slot__status parking-slot__status--occupied">Ocupado</div>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" width="18" height="18"><path d="M5 14h14l-1.2-4.2A2 2 0 0 0 16.9 8H7.1a2 2 0 0 0-1.9 1.8L5 14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="8" cy="17" r="1.2" stroke="currentColor" stroke-width="1.6"/><circle cx="16" cy="17" r="1.2" stroke="currentColor" stroke-width="1.6"/></svg>
                                        <span>{{ $slot['code'] }}</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @endforeach

                    <p class="parking-map-note">Selecciona un espacio para ver detalle o abrir un ingreso directo.</p>
                </section>

                <section class="panel-card current-parking-card">
                    <div class="current-parking-header">
                        <div class="panel-title">Vehículos actualmente en parqueadero</div>
                        <div class="current-parking-tools">
                            <input type="search" class="parking-search" placeholder="Buscar por placa o cliente…" aria-label="Buscar por placa o cliente…">
                            <select class="parking-state-filter" aria-label="Filtro de estado">
                                <option>Todos los estados</option>
                                <option>Activo</option>
                                <option>Pagado</option>
                                <option>Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="current-parking-table-wrap">
                        <table class="current-parking-table">
                            <thead>
                                <tr>
                                    <th>Placa</th>
                                    <th>Cliente</th>
                                    <th>Zona</th>
                                    <th>Espacio</th>
                                    <th>Ingreso</th>
                                    <th>Tipo</th>
                                    <th>Tiempo</th>
                                    <th>Valor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tableRows as $row)
                                    <tr>
                                        <td><strong>{{ $row['plate'] }}</strong></td>
                                        <td>{{ $row['client'] }}</td>
                                        <td>{{ $row['zone'] }}</td>
                                        <td>{{ $row['slot'] }}</td>
                                        <td>{{ $row['entry'] }}</td>
                                        <td>{{ $row['type'] }}</td>
                                        <td class="time-active">{{ $row['time'] }}</td>
                                        <td class="value-active">{{ $row['value'] }}</td>
                                        <td>
                                            <div class="action-group">
                                                @if (! $row['demo'])
                                                    <a class="icon-btn" href="{{ route('parqueadero.show', $row['id']) }}" title="Ver detalle">◉</a>
                                                    <a class="icon-btn" href="{{ route('parqueadero.show', $row['id']) }}#salida-form" title="Registrar salida">⏱</a>
                                                @else
                                                    <button type="button" class="icon-btn" @click="selectedRow = @js($row); detailOpen = true" title="Ver detalle">◉</button>
                                                    <button type="button" class="icon-btn" @click="selectedRow = @js($row); exitOpen = true" title="Registrar salida">⏱</button>
                                                @endif
                                                <button type="button" class="icon-btn icon-btn--danger" @click="selectedRow = @js($row); detailOpen = true" title="Cancelar movimiento">✕</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="padding:28px 18px;text-align:center;color:#94a3b8">No hay vehículos actualmente en parqueadero.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <div>
                            @if ($movimientos->total() > 0)
                                Mostrando {{ $movimientos->firstItem() }} a {{ $movimientos->lastItem() }} de {{ $movimientos->total() }} registros
                            @else
                                No hay movimientos registrados.
                            @endif
                        </div>
                        <div class="pagination">
                            @if ($movimientos->onFirstPage())
                                <span class="page-btn">Anterior</span>
                            @else
                                <a class="page-btn" href="{{ $movimientos->previousPageUrl() }}">Anterior</a>
                            @endif
                            <span class="page-btn active">{{ $movimientos->currentPage() }}</span>
                            @if ($movimientos->hasMorePages())
                                <a class="page-btn" href="{{ $movimientos->nextPageUrl() }}">Siguiente</a>
                            @else
                                <span class="page-btn">Siguiente</span>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            <aside class="parking-right-column">
                <section class="panel-card parking-status-card">
                    <div class="panel-title" style="margin-bottom:14px">Estado general</div>
                    <div class="status-content">
                        <div class="status-chart">
                            <canvas id="parkingStatusChart"></canvas>
                            <div class="status-center"><div><strong>{{ $cupos->count() > 0 ? round((($parkingStats['occupied'] ?? 0) / $cupos->count()) * 100) : 0 }}%</strong><span>Ocupación</span></div></div>
                        </div>
                        <div class="status-legend">
                            <div class="status-legend-item"><span><i class="legend-dot" style="background:#ef4444"></i>Ocupados</span><strong>{{ $parkingStats['occupied'] ?? 0 }} ({{ $cupos->count() > 0 ? round((($parkingStats['occupied'] ?? 0) / $cupos->count()) * 100) : 0 }}%)</strong></div>
                            <div class="status-legend-item"><span><i class="legend-dot" style="background:#22c55e"></i>Disponibles</span><strong>{{ $parkingStats['available'] ?? 0 }} ({{ $cupos->count() > 0 ? round((($parkingStats['available'] ?? 0) / $cupos->count()) * 100) : 0 }}%)</strong></div>
                            <div class="status-legend-item"><span><i class="legend-dot" style="background:#f59e0b"></i>Reservados</span><strong>{{ $parkingStats['reserved'] ?? 0 }} ({{ $cupos->count() > 0 ? round((($parkingStats['reserved'] ?? 0) / $cupos->count()) * 100) : 0 }}%)</strong></div>
                            <div class="status-legend-item"><span><i class="legend-dot" style="background:#3b82f6"></i>Mantenimiento</span><strong>{{ $parkingStats['maintenance'] ?? 0 }} ({{ $cupos->count() > 0 ? round((($parkingStats['maintenance'] ?? 0) / $cupos->count()) * 100) : 0 }}%)</strong></div>
                        </div>
                    </div>
                </section>

                <section class="panel-card parking-list-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:10px">
                        <div class="panel-title">Ingresos recientes</div>
                        <a href="#" style="color:#60a5fa;text-decoration:none;font-size:13px">Ver todos</a>
                    </div>
                    @foreach ($recentEntries as $entry)
                        <div class="recent-entry-row">
                            <div class="entry-icon">↗</div>
                            <div class="row-main"><strong>{{ $entry['plate'] }}</strong><span>{{ $entry['client'] }}</span></div>
                            <div class="row-time">{{ $entry['time'] }}</div>
                            <div class="row-slot">{{ $entry['slot'] }}</div>
                        </div>
                    @endforeach
                </section>

                <section class="panel-card parking-list-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:10px">
                        <div class="panel-title">Próximas salidas</div>
                        <a href="#" style="color:#60a5fa;text-decoration:none;font-size:13px">Ver todas</a>
                    </div>
                    @foreach ($upcomingExits as $exit)
                        <div class="exit-row">
                            <div class="exit-icon">◔</div>
                            <div class="row-main"><strong>{{ $exit['plate'] }}</strong><span>{{ $exit['client'] }}</span></div>
                            <div class="row-time">{{ $exit['time'] }}</div>
                            <div class="row-slot">{{ $exit['slot'] }}</div>
                        </div>
                    @endforeach
                </section>
            </aside>
        </section>

        @include('parqueadero.partials.nuevo-ingreso-modal')
        @include('parqueadero.partials.salida-modal')
        @include('parqueadero.partials.detalle-modal')
        @include('parqueadero.partials.reporte-modal')
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ctx = document.getElementById('parkingStatusChart');
                if (!ctx) return;

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Ocupados', 'Disponibles', 'Reservados', 'Mantenimiento'],
                        datasets: [{
                            data: @json($parkingChartData),
                            backgroundColor: ['#ef4444', '#22c55e', '#f59e0b', '#3b82f6'],
                            borderWidth: 0,
                            cutout: '72%',
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
