<x-app-layout>
    <x-clientes-styles />
    @push('styles')
        <style>
            .parking-page{padding:24px 34px 34px;color:#f8fafc}.parking-header{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:22px}.page-title{font-size:30px;font-weight:800;color:#f8fafc;margin-bottom:4px}.page-subtitle{font-size:14px;color:#94a3b8}.parking-actions{display:flex;gap:12px;align-items:center;flex-wrap:wrap}.btn-secondary-parking{height:44px;padding:0 20px;border-radius:10px;background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0;font-size:14px;font-weight:700;display:inline-flex;align-items:center;gap:8px;text-decoration:none}.panel-card{border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}.modal-backdrop{position:fixed;inset:0;background:rgba(2,6,23,.72);display:grid;place-items:center;z-index:80;padding:18px}.modal-card{width:min(980px,100%);border-radius:18px;background:linear-gradient(180deg,rgba(17,24,39,.98),rgba(15,23,42,.98));border:1px solid rgba(148,163,184,.16);box-shadow:0 36px 88px rgba(0,0,0,.42);overflow:hidden}.modal-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;padding:18px 20px;border-bottom:1px solid rgba(148,163,184,.12)}.modal-head h3{margin:0;font-size:20px;font-weight:800;color:#fff}.modal-head p{margin:4px 0 0;color:#94a3b8;font-size:13px}.modal-close{width:38px;height:38px;border-radius:12px;border:1px solid rgba(148,163,184,.16);background:rgba(15,23,42,.86);color:#e2e8f0;display:grid;place-items:center;font-size:22px}.modal-body{padding:20px}.modal-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.field{display:grid;gap:6px}.field label,.field span{font-size:12px;font-weight:700;color:#cbd5e1}.field input,.field select,.field textarea{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.18);color:#e2e8f0;padding:0 14px;outline:none}.field textarea{height:auto;min-height:94px;padding:12px 14px;resize:vertical}.modal-footer{display:flex;justify-content:flex-end;gap:10px;padding:0 20px 20px}.modal-secondary,.modal-primary{height:42px;padding:0 18px;border-radius:10px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.modal-secondary{background:rgba(15,23,42,.88);border:1px solid rgba(59,130,246,.45);color:#e2e8f0}.modal-primary{background:linear-gradient(90deg,#2563eb,#7c3aed);color:#fff}.current-parking-card{margin-top:18px;overflow:hidden}.current-parking-header{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:18px 20px 12px}.panel-title{font-size:18px;font-weight:700;color:#f8fafc}.sidebar-help-card{display:grid;gap:12px;background:rgba(15,23,42,.72);border:1px solid rgba(148,163,184,.12);border-radius:18px;padding:12px}.sidebar-help-card__copy{display:grid;gap:8px;color:#e2e8f0}.sidebar-help-card__copy strong{font-size:16px;color:#fff}.sidebar-help-card__copy span{color:#94a3b8;font-size:13px;line-height:1.5}.sidebar-help-card__button{height:42px;border-radius:10px;background:linear-gradient(90deg,#2563eb,#7c3aed);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;text-decoration:none}.sidebar-footer__profile{display:flex;align-items:center;gap:12px;margin-top:6px;padding-top:14px;border-top:1px solid rgba(148,163,184,.12)}.sidebar-footer__avatar{width:40px;height:40px;border-radius:999px;background:linear-gradient(135deg,#7c3aed,#3b82f6);display:grid;place-items:center;font-weight:800;color:#fff;flex:none}.sidebar-footer__meta strong{display:block;color:#fff}.sidebar-footer__meta span,.sidebar-footer__meta p{color:#94a3b8;margin:0;font-size:13px}.sidebar-footer__meta p{display:flex;align-items:center;gap:8px}.sidebar-footer__car{width:100%;max-width:220px;margin:0 auto;display:block;filter:drop-shadow(0 18px 18px rgba(0,0,0,.35));opacity:.92;border-radius:18px}.sidebar-status-dot{width:10px;height:10px;border-radius:999px;background:#22c55e;box-shadow:0 0 0 4px rgba(34,197,94,.12);display:inline-block}
            @media (max-width: 640px){.parking-header{flex-direction:column;gap:14px}.modal-grid{grid-template-columns:1fr}}
        </style>
    @endpush
    <div class="parking-page" x-data="{ newOpen:true }">
        <section class="parking-header">
            <div>
                <h1 class="page-title">Nuevo ingreso</h1>
                <p class="page-subtitle">Abre un movimiento activo para un vehículo del parqueadero</p>
            </div>
            <div class="parking-actions">
                <a class="btn-secondary-parking" href="{{ route('parqueadero.index') }}">Volver al tablero</a>
            </div>
        </section>

        <section class="panel-card" style="padding:22px">
            <p style="margin:0;color:#94a3b8">Este acceso abre el mismo formulario operativo que el botón <strong>Nuevo ingreso</strong> del tablero.</p>
        </section>

        @include('parqueadero.partials.nuevo-ingreso-modal')
    </div>
</x-app-layout>
