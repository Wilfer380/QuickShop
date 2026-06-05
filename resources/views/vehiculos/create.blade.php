<x-app-layout>
    <x-clientes-styles />

    <section class="vehicle-form-page">
        <div class="vehicle-form-header">
            <div>
                <h1 class="page-title">Crear vehículo</h1>
                <p class="page-subtitle">Registra una nueva unidad con foto, ubicación, precio y estado.</p>
            </div>
            <a href="{{ route('vehiculos.index') }}" class="btn-new-vehicle btn-secondary">Volver</a>
        </div>

        <div class="vehicle-form-layout">
            <section class="vehicle-form-card">
                <form class="vehicle-form" action="{{ route('vehiculos.store') }}" method="POST" enctype="multipart/form-data">
                    @include('vehiculos._form')
                </form>
            </section>

            <aside class="vehicle-form-side">
                <article class="vehicle-preview-card">
                    <div class="vehicle-preview-badge">Vista previa</div>
                    <div class="vehicle-preview-image">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 14h14l-1.2-4.2A2 2 0 0 0 16.9 8H7.1a2 2 0 0 0-1.9 1.8L5 14Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="8" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/><circle cx="16" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/></svg>
                    </div>
                    <h2>Vehículo en inventario</h2>
                    <p>La foto cargada se mostrará aquí cuando guardes la unidad.</p>
                </article>

                <article class="vehicle-tip-card">
                    <h3>Importante</h3>
                    <ul>
                        <li>La foto es opcional, pero recomendada.</li>
                        <li>Ubicación y precio de compra ayudan al control interno.</li>
                        <li>Si no hay cliente, deja el selector en blanco.</li>
                    </ul>
                </article>
            </aside>
        </div>
    </section>

    @push('styles')
        <style>
            .vehicle-form-page{padding:24px 34px 34px;color:#F8FAFC}
            .vehicle-form-header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:18px}
            .btn-secondary{background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.16);box-shadow:none}
            .vehicle-form-layout{display:grid;grid-template-columns:minmax(0,1.25fr) 360px;gap:16px;align-items:start}
            .vehicle-form-card,.vehicle-preview-card,.vehicle-tip-card{border-radius:14px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}
            .vehicle-form-card{padding:22px}
            .vehicle-form{display:grid;gap:16px}
            .vehicle-form .vehicle-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
            .vehicle-form .vehicle-grid--3{grid-template-columns:repeat(3,minmax(0,1fr))}
            .vehicle-form .vehicle-field{display:grid;gap:8px;min-width:0}
            .vehicle-form .vehicle-field--full{grid-column:1 / -1}
            .vehicle-form .vehicle-field label{display:block}
            .vehicle-form .vehicle-field label span{font-size:13px;font-weight:700;color:#E2E8F0}
            .vehicle-form .vehicle-field input,.vehicle-form .vehicle-field select,.vehicle-form .vehicle-field textarea{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.90);border:1px solid rgba(148,163,184,.18);color:#E2E8F0;padding:0 14px;font-size:13px;outline:none;box-sizing:border-box;appearance:none;-webkit-appearance:none;-moz-appearance:none;min-width:0;color-scheme:dark}
            .vehicle-form .vehicle-field textarea{height:108px;padding:12px 14px;resize:vertical}
            .vehicle-form .vehicle-field input:focus,.vehicle-form .vehicle-field select:focus,.vehicle-form .vehicle-field textarea:focus{border-color:#3B82F6;box-shadow:0 0 0 3px rgba(59,130,246,.14)}
            .vehicle-form .vehicle-upload input{padding:10px 12px;height:auto}
            .vehicle-form .vehicle-upload input::file-selector-button{height:30px;margin-right:12px;border:0;border-radius:8px;padding:0 12px;background:rgba(37,99,235,.18);color:#93C5FD;font-weight:700;cursor:pointer}
            .vehicle-form .vehicle-upload input::-webkit-file-upload-button{height:30px;margin-right:12px;border:0;border-radius:8px;padding:0 12px;background:rgba(37,99,235,.18);color:#93C5FD;font-weight:700;cursor:pointer}
            .vehicle-form .crud-error{font-size:12px;color:#F87171}
            .vehicle-form .crud-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:4px}
            .vehicle-form .crud-button,.vehicle-form .crud-link{height:42px;padding:0 18px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;text-decoration:none}
            .vehicle-form .crud-button{background:linear-gradient(90deg,#2563EB,#7C3AED);color:#fff;border:0}
            .vehicle-form .crud-link{background:rgba(15,23,42,.76);border:1px solid rgba(148,163,184,.16);color:#CBD5E1}
            .vehicle-preview-card{padding:22px;display:grid;gap:12px;margin-bottom:16px}
            .vehicle-preview-badge{width:max-content;padding:6px 10px;border-radius:999px;background:rgba(37,99,235,.16);color:#60A5FA;font-size:12px;font-weight:700}
            .vehicle-preview-image{height:170px;border-radius:12px;background:rgba(15,23,42,.75);border:1px solid rgba(148,163,184,.16);display:flex;align-items:center;justify-content:center;color:#3B82F6}
            .vehicle-preview-image svg{width:72px;height:72px}
            .vehicle-preview-card h2{margin:0;color:#F8FAFC;font-size:20px;font-weight:800}
            .vehicle-preview-card p,.vehicle-tip-card li{color:#94A3B8;line-height:1.7}
            .vehicle-tip-card{padding:20px}
            .vehicle-tip-card h3{margin:0 0 10px;color:#F8FAFC;font-size:16px;font-weight:800}
            .vehicle-tip-card ul{margin:0;padding-left:18px;display:grid;gap:8px}
            @media (max-width:1024px){.vehicle-form-page{padding:20px 16px 28px}.vehicle-form-layout{grid-template-columns:1fr}.vehicle-form .vehicle-grid,.vehicle-form .vehicle-grid--3{grid-template-columns:1fr}}
        </style>
    @endpush
</x-app-layout>
