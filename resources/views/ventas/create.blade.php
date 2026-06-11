<x-app-layout>
    <x-clientes-styles />

    <section class="sale-form-page">
        <div class="sale-form-header">
            <div>
                <h1 class="page-title">Nueva venta</h1>
                <p class="page-subtitle">Registra el cierre, marca el vehículo como vendido y guarda el abono inicial si existe.</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="btn-new-sale btn-secondary">Volver</a>
        </div>

        @if ($errors->any())
            <div class="crud-alert">Revisa los datos del formulario.</div>
        @endif

        <div class="sale-form-layout">
            <section class="sale-form-card">
                <form class="sale-form" method="POST" action="{{ $action }}">
                    @include('ventas.partials.fields')
                </form>
            </section>
            <aside class="sale-side-card">
                <span>Cierre comercial</span>
                <h2>Venta segura</h2>
                <p>La creación conserva la transacción existente: bloquea el vehículo, cambia su estado a vendido y crea el pago inicial únicamente si informás un valor mayor a cero.</p>
            </aside>
        </div>
    </section>

    @push('styles')
        <style>
            .sale-form-page{padding:24px 34px 34px;color:#F8FAFC}.sale-form-header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:18px}.btn-new-sale{height:44px;padding:0 22px;border-radius:10px;background:linear-gradient(90deg,#2563EB,#7C3AED);color:#fff;font-size:14px;font-weight:700;display:inline-flex;align-items:center;gap:10px;box-shadow:0 12px 26px rgba(37,99,235,.28);text-decoration:none}.btn-secondary{background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.16);box-shadow:none}.sale-form-layout{display:grid;grid-template-columns:minmax(0,1.25fr) 360px;gap:16px;align-items:start}.sale-form-card,.sale-side-card{border-radius:14px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}.sale-form-card{padding:22px}.sale-form{display:grid;gap:16px}.sale-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.sale-grid--3{grid-template-columns:repeat(3,minmax(0,1fr))}.sale-field{display:grid;gap:8px;min-width:0}.sale-field--full{grid-column:1 / -1}.sale-field span{font-size:13px;font-weight:700;color:#E2E8F0}.sale-field input,.sale-field select,.sale-field textarea{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.90);border:1px solid rgba(148,163,184,.18);color:#E2E8F0;padding:0 14px;font-size:13px;outline:none;box-sizing:border-box;color-scheme:dark}.sale-field textarea{height:108px;padding:12px 14px;resize:vertical}.sale-field input:focus,.sale-field select:focus,.sale-field textarea:focus{border-color:#3B82F6;box-shadow:0 0 0 3px rgba(59,130,246,.14)}.crud-error{font-size:12px;color:#F87171}.sale-actions{display:flex;gap:10px;flex-wrap:wrap}.sale-actions .btn-primary,.sale-actions .btn-secondary{height:42px;padding:0 18px;border-radius:10px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.sale-actions .btn-primary{background:linear-gradient(90deg,#2563EB,#7C3AED);border:0;color:#fff}.sale-side-card{padding:22px}.sale-side-card span{width:max-content;padding:6px 10px;border-radius:999px;background:rgba(37,99,235,.16);color:#60A5FA;font-size:12px;font-weight:700}.sale-side-card h2{margin:14px 0 8px;color:#F8FAFC;font-size:22px;font-weight:800}.sale-side-card p{color:#94A3B8;line-height:1.7}@media(max-width:1024px){.sale-form-page{padding:20px 16px 28px}.sale-form-layout,.sale-grid,.sale-grid--3{grid-template-columns:1fr}.sale-form-header{flex-direction:column;align-items:flex-start}}
        </style>
    @endpush
</x-app-layout>
