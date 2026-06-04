<x-app-layout>
    @php
        $modules = [
            ['label' => 'Clientes', 'route' => 'clientes.index', 'metric' => 'CRM', 'description' => 'Datos, historial y seguimiento comercial.'],
            ['label' => 'Vehiculos', 'route' => 'vehiculos.index', 'metric' => $vehicleStats['total'], 'description' => 'Inventario, estado y disponibilidad.'],
            ['label' => 'Ventas', 'route' => 'ventas.index', 'metric' => '$', 'description' => 'Oportunidades, cierre y facturacion.'],
            ['label' => 'Parqueadero', 'route' => 'parqueadero.index', 'metric' => 'Ops', 'description' => 'Ingresos, salidas y ocupacion.'],
            ['label' => 'Cupos', 'route' => 'cupos.index', 'metric' => $vehicleStats['available'], 'description' => 'Zonas, cupos y asignaciones.'],
            ['label' => 'Tarifas', 'route' => 'tarifas.index', 'metric' => 'Rules', 'description' => 'Planes y reglas de cobro.'],
            ['label' => 'Pagos', 'route' => 'pagos.index', 'metric' => 'Cash', 'description' => 'Recaudo, comprobantes y saldos.'],
            ['label' => 'Reportes', 'route' => 'reportes.index', 'metric' => 'BI', 'description' => 'Indicadores de operacion y ventas.'],
        ];
    @endphp

    <div class="admin-dashboard">
        <section class="admin-hero">
            <div class="admin-hero__copy">
                <span class="admin-eyebrow">Administracion interna</span>
                <h1>Panel principal VehiPark</h1>
                <p>Gestiona vehiculos, ventas, parqueadero, cupos, tarifas, pagos y empleados desde un panel interno limpio y directo.</p>
                <div class="admin-hero__actions">
                    <a href="{{ route('vehiculos.index') }}">Abrir vehiculos</a>
                    <a href="{{ route('parqueadero.index') }}">Ver parqueadero</a>
                </div>
            </div>
            <div class="admin-command-card">
                <span>VehiPark OS</span>
                <strong>{{ now()->format('H:i') }}</strong>
                <small>Sesion operativa activa</small>
            </div>
        </section>

        <section class="admin-stats" aria-label="Resumen ejecutivo">
            <article><span>Vehiculos</span><strong>{{ $vehicleStats['total'] }}</strong></article>
            <article><span>Disponibles</span><strong>{{ $vehicleStats['available'] }}</strong></article>
            <article><span>Segmentos</span><strong>{{ $vehicleStats['segments'] }}</strong></article>
            <article><span>Empleados</span><strong>{{ $vehicleStats['employees'] }}</strong></article>
        </section>

        <section class="admin-section">
            <div class="admin-section__heading">
                <span class="admin-eyebrow">Arquitectura modular</span>
                <h2>Panel principal</h2>
                <p>Cada area mantiene ruta, controlador, request, servicio y vista base.</p>
            </div>
            <div class="admin-modules">
                @foreach ($modules as $module)
                    <a href="{{ route($module['route']) }}" class="admin-module-card">
                        <span>{{ $module['label'] }}</span>
                        <strong>{{ $module['metric'] }}</strong>
                        <p>{{ $module['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="admin-section">
            <div class="admin-section__heading">
                <span class="admin-eyebrow">Accesos rapidos</span>
                <h2>Operación diaria</h2>
                <p>Entrá directo a los módulos que usás todos los días.</p>
            </div>

            <div class="admin-modules">
                <a href="{{ route('vehiculos.index') }}" class="admin-module-card"><span>Vehiculos</span><strong>{{ $vehicleStats['total'] }}</strong><p>Inventario y unidades activas.</p></a>
                <a href="{{ route('parqueadero.index') }}" class="admin-module-card"><span>Parqueadero</span><strong>Ops</strong><p>Ingresos, salidas y ocupacion.</p></a>
                <a href="{{ route('ventas.index') }}" class="admin-module-card"><span>Ventas</span><strong>$</strong><p>Cierres y facturacion.</p></a>
                <a href="{{ route('reportes.index') }}" class="admin-module-card"><span>Reportes</span><strong>BI</strong><p>Indicadores operativos.</p></a>
            </div>
        </section>
    </div>

    <style>
        .admin-dashboard{min-height:100vh;padding:28px;background:radial-gradient(circle at 12% 0%,rgba(14,165,233,.22),transparent 26%),radial-gradient(circle at 88% 4%,rgba(249,115,22,.18),transparent 28%),linear-gradient(180deg,#020617,#0f172a 48%,#020617);color:#e5e7eb}.admin-hero,.admin-panel,.admin-stats article,.admin-module-card,.admin-command-card{border:1px solid rgba(255,255,255,.12);background:rgba(15,23,42,.78);box-shadow:0 28px 90px rgba(0,0,0,.38);backdrop-filter:blur(20px)}.admin-hero{display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:24px;align-items:stretch;border-radius:34px;padding:34px;margin-bottom:18px;overflow:hidden;position:relative}.admin-hero:after{content:"";position:absolute;right:-8%;bottom:-44%;width:56%;height:95%;background:url("{{ asset('resources/backgrounds/vehipark-car-parking-scene.svg') }}") center/cover no-repeat;opacity:.28;pointer-events:none}.admin-hero>*{position:relative;z-index:1}.admin-eyebrow{display:inline-flex;color:#38bdf8;font-size:12px;font-weight:900;letter-spacing:.2em;text-transform:uppercase}.admin-hero h1{max-width:900px;margin:12px 0;font-size:clamp(2.4rem,5vw,5.6rem);line-height:.92;color:#fff;letter-spacing:-.06em}.admin-hero p,.admin-section__heading p,.admin-panel p,.admin-module-card p{color:#b8c2d3;line-height:1.7}.admin-hero__actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}.admin-hero__actions a,.admin-filters button,.admin-form button{border:0;border-radius:999px;background:linear-gradient(135deg,#38bdf8,#f97316);color:#020617;font-weight:950;padding:12px 18px;text-decoration:none}.admin-command-card{display:grid;place-content:end;border-radius:30px;padding:24px;color:#fff}.admin-command-card span,.admin-command-card small,.admin-stats span,.admin-table-head span,.admin-row small{color:#94a3b8;font-size:12px;text-transform:uppercase;letter-spacing:.12em;font-weight:800}.admin-command-card strong{display:block;font-size:56px;line-height:1}.admin-stats,.admin-modules{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}.admin-stats article,.admin-module-card{border-radius:24px;padding:20px;text-decoration:none;color:inherit}.admin-stats strong,.admin-module-card strong{display:block;margin-top:8px;color:#fff;font-size:32px}.admin-section,.admin-grid{margin-bottom:18px}.admin-section__heading{margin-bottom:14px}.admin-section__heading h2,.admin-toolbar h2,.admin-table-head h2,.admin-form h2{margin:8px 0;color:#fff;font-size:26px}.admin-grid{display:grid;grid-template-columns:minmax(0,1fr) 390px;gap:18px;align-items:start}.admin-main{display:grid;gap:18px}.admin-panel{border-radius:28px;padding:24px}.admin-toolbar{display:grid;grid-template-columns:1fr minmax(420px,.9fr);gap:22px;align-items:end}.admin-filters{display:grid;grid-template-columns:1fr 1fr auto;gap:10px}.admin-filters input,.admin-filters select,.admin-form input,.admin-form select,.admin-form textarea{width:100%;border:1px solid rgba(148,163,184,.28);border-radius:16px;background:rgba(2,6,23,.74);color:#f8fafc;padding:13px 14px}.admin-table{display:grid;gap:10px}.admin-table-head{display:flex;justify-content:space-between;gap:14px;align-items:center}.admin-row{display:grid;grid-template-columns:1.5fr 1fr .55fr .8fr 1fr 1fr;gap:14px;align-items:center;border-radius:18px;background:rgba(15,23,42,.68);padding:14px}.admin-row--head{background:rgba(255,255,255,.1);color:#cbd5e1;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.1em}.admin-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap}.admin-actions a,.admin-actions button,.admin-cancel{border:0;background:transparent;color:#38bdf8;font-weight:900;text-decoration:none}.admin-actions button{color:#fb7185;cursor:pointer}.admin-form form,.admin-form label{display:grid;gap:10px}.admin-form form{margin-top:18px}.admin-form label{color:#cbd5e1;font-size:13px;font-weight:900}.admin-form__split{display:grid;grid-template-columns:1fr 1fr;gap:10px}.admin-alert{border:1px solid rgba(248,113,113,.35);border-radius:18px;background:rgba(127,29,29,.36);padding:12px;color:#fecaca}@media(max-width:1180px){.admin-hero,.admin-grid,.admin-toolbar{grid-template-columns:1fr}.admin-stats,.admin-modules{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:760px){.admin-dashboard{padding:16px}.admin-hero{padding:24px}.admin-stats,.admin-modules,.admin-row,.admin-filters,.admin-form__split{grid-template-columns:1fr}.admin-row--head{display:none}}
    </style>
    <style>
        .admin-hero:after{background-image:url('https://images.pexels.com/photos/170811/pexels-photo-170811.jpeg?auto=compress&cs=tinysrgb&w=1800');opacity:.4;background-position:center left}
    </style>
</x-app-layout>
