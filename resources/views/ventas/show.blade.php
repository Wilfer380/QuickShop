<x-app-layout>
    <x-clientes-styles />

    @php
        $pagado = (float) $venta->pagos->sum('valor');
        $saldo = max(0, (float) $venta->total - $pagado);
        $estadoBadges = ['pendiente' => 'badge-orange', 'abono' => 'badge-blue', 'pagada' => 'badge-green'];
    @endphp

    <section class="sale-detail-page" x-data="{ abonoOpen:false, facturaOpen:false }">
        <div class="sale-detail-header">
            <div>
                <h1 class="page-title">Venta #{{ $venta->id }}</h1>
                <p class="page-subtitle">{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }} · {{ $venta->vehiculo->marca }} {{ $venta->vehiculo->modelo }}</p>
            </div>
            <div class="detail-actions">
                <button type="button" class="btn-new-sale btn-secondary" @click="abonoOpen = true">Registrar abono</button>
                <button type="button" class="btn-new-sale btn-secondary" @click="facturaOpen = true">Generar factura</button>
                <a href="{{ route('ventas.edit', $venta) }}" class="btn-new-sale btn-secondary">Editar</a>
                <a href="{{ route('ventas.index') }}" class="btn-new-sale">Volver</a>
            </div>
        </div>

        @if (session('status'))
            <div class="crud-alert">{{ session('status') }}</div>
        @endif

        <div class="sale-detail-grid-main">
            <article class="sale-detail-card sale-summary-card">
                <span class="badge {{ $estadoBadges[$venta->estado] ?? 'badge-blue' }}">{{ ucfirst($venta->estado) }}</span>
                <h2>${{ number_format((float) $venta->total, 0, ',', '.') }}</h2>
                <p>Total de la venta</p>
                <div class="sale-money-grid">
                    <div><span>Pagado</span><strong>${{ number_format($pagado, 0, ',', '.') }}</strong></div>
                    <div><span>Saldo</span><strong>${{ number_format($saldo, 0, ',', '.') }}</strong></div>
                </div>
            </article>

            <article class="sale-detail-card sale-specs">
                <div><span>Cliente</span><strong>{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</strong></div>
                <div><span>Documento</span><strong>{{ $venta->cliente->documento }}</strong></div>
                <div><span>Vehículo</span><strong>{{ $venta->vehiculo->marca }} {{ $venta->vehiculo->modelo }}</strong></div>
                <div><span>Placa</span><strong>{{ $venta->vehiculo->placa ?? 'Sin placa' }}</strong></div>
                <div><span>Fecha</span><strong>{{ $venta->fecha_venta?->format('d/m/Y') }}</strong></div>
                <div><span>Vendedor</span><strong>{{ $venta->vendedor?->name ?? 'Sin asignar' }}</strong></div>
            </article>
        </div>

        <section class="sale-detail-card sale-payments-card">
            <h2>Pagos registrados</h2>
            <div class="overflow-x-auto">
                <table class="sales-table">
                    <thead><tr><th>Valor</th><th>Método</th><th>Referencia</th><th>Fecha</th><th>Recibido por</th><th>Estado</th></tr></thead>
                    <tbody>
                        @forelse ($venta->pagos as $pago)
                            <tr><td>${{ number_format((float) $pago->valor, 0, ',', '.') }}</td><td>{{ ucfirst($pago->metodo_pago) }}</td><td>{{ $pago->referencia ?? 'Sin referencia' }}</td><td>{{ $pago->pagado_at?->format('d/m/Y H:i') }}</td><td>{{ $pago->recibidoPor?->name ?? 'Sin asignar' }}</td><td>{{ ucfirst($pago->estado) }}</td></tr>
                        @empty
                            <tr><td colspan="6" style="padding:20px 16px;color:#94A3B8;">Sin pagos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @include('ventas.partials.abono-modal', ['inlineVenta' => $venta, 'inlineSaldo' => $saldo])
        @include('ventas.partials.factura-modal', ['inlineVenta' => $venta, 'inlinePagado' => $pagado, 'inlineSaldo' => $saldo])
    </section>

    @push('styles')
        <style>
            .sale-detail-page{padding:24px 34px 34px;color:#F8FAFC}.sale-detail-header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:18px}.detail-actions{display:flex;gap:10px;flex-wrap:wrap}.btn-new-sale{height:44px;padding:0 22px;border-radius:10px;background:linear-gradient(90deg,#2563EB,#7C3AED);color:#fff;font-size:14px;font-weight:700;display:inline-flex;align-items:center;gap:10px;box-shadow:0 12px 26px rgba(37,99,235,.28);text-decoration:none}.btn-secondary{background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.16);box-shadow:none}.sale-detail-grid-main{display:grid;grid-template-columns:.8fr 1.2fr;gap:16px;margin-bottom:16px}.sale-detail-card{padding:22px;border-radius:14px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}.sale-summary-card h2{font-size:36px;font-weight:900;margin:18px 0 4px}.sale-summary-card p{color:#94A3B8}.sale-money-grid,.sale-specs{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.sale-money-grid{margin-top:18px}.sale-money-grid div,.sale-specs div{padding:14px;border-radius:10px;background:rgba(15,23,42,.6);border:1px solid rgba(148,163,184,.12)}.sale-money-grid span,.sale-specs span{display:block;color:#94A3B8;font-size:12px;margin-bottom:6px}.sale-money-grid strong,.sale-specs strong{color:#F8FAFC}.sale-payments-card h2{font-size:18px;font-weight:800;margin-bottom:14px}.sales-table{width:100%;border-collapse:collapse;min-width:820px}.sales-table thead{background:rgba(15,23,42,.54)}.sales-table th,.sales-table td{padding:14px 16px;text-align:left;border-top:1px solid rgba(148,163,184,.10);font-size:13px}.sales-table th{color:#E2E8F0;font-weight:700}.sales-table td{color:#CBD5E1}.sale-form{display:grid;gap:16px}.sale-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.sale-field{display:grid;gap:8px}.sale-field--full{grid-column:1 / -1}.sale-field span{font-size:13px;font-weight:700;color:#E2E8F0}.sale-field input,.sale-field select,.sale-field textarea{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.90);border:1px solid rgba(148,163,184,.18);color:#E2E8F0;padding:0 14px;font-size:13px;box-sizing:border-box;color-scheme:dark}.sale-field textarea{height:96px;padding:12px 14px}.sale-actions{display:flex;gap:10px;flex-wrap:wrap}.sale-actions .btn-primary,.sale-actions .btn-secondary{height:42px;padding:0 18px;border-radius:10px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.sale-actions .btn-primary{background:linear-gradient(90deg,#2563EB,#7C3AED);border:0;color:#fff}.invoice-box{padding:18px;border-radius:12px;background:rgba(248,250,252,.96);color:#0F172A}.invoice-box h3{font-size:22px;font-weight:900;margin-bottom:12px}.invoice-line{display:flex;justify-content:space-between;border-bottom:1px solid rgba(15,23,42,.12);padding:8px 0}.badge-orange{color:#FDBA74;background:rgba(249,115,22,.20)}@media(max-width:1024px){.sale-detail-page{padding:20px 16px 28px}.sale-detail-grid-main,.sale-specs,.sale-grid{grid-template-columns:1fr}.sale-detail-header{flex-direction:column;align-items:flex-start}}
        </style>
    @endpush
</x-app-layout>
