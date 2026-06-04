<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero"><div><span class="crud-eyebrow">Comprobante</span><h1>Pago #{{ $pago->id }}</h1><p>Contenido listo para recibo interno y conciliacion.</p></div><a class="crud-link" href="{{ route('pagos.index') }}">Volver</a></section>
        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-grid">
                <p><strong>Valor:</strong> ${{ number_format((float) $pago->valor, 2) }}</p>
                <p><strong>Concepto:</strong> {{ ucfirst($pago->concepto) }}</p>
                <p><strong>Metodo:</strong> {{ ucfirst($pago->metodo_pago) }}</p>
                <p><strong>Referencia:</strong> {{ $pago->referencia ?? 'Sin referencia' }}</p>
                <p><strong>Cliente:</strong> {{ $pago->cliente?->nombres ?? 'Sin cliente' }}</p>
                <p><strong>Recibido por:</strong> {{ $pago->recibidoPor?->name ?? 'Sistema' }}</p>
            </div>
            <p class="crud-muted">Fecha: {{ $pago->pagado_at?->format('d/m/Y H:i') ?? 'Sin fecha' }}. Estado: {{ ucfirst($pago->estado) }}.</p>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
