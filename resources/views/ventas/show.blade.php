<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Recibo de venta</span>
                <h1>Venta #{{ $venta->id }}</h1>
                <p>{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }} compro {{ $venta->vehiculo->marca }} {{ $venta->vehiculo->modelo }}.</p>
            </div>
            <a class="crud-link" href="{{ route('ventas.index') }}">Volver</a>
        </section>

        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-grid">
                <p><strong>Total:</strong> ${{ number_format((float) $venta->total, 2) }}</p>
                <p><strong>Estado:</strong> {{ ucfirst($venta->estado) }}</p>
                <p><strong>Vendedor:</strong> {{ $venta->vendedor?->name ?? 'Sin asignar' }}</p>
                <p><strong>Fecha:</strong> {{ $venta->fecha_venta->format('d/m/Y') }}</p>
            </div>
            <h2>Pagos registrados</h2>
            <div class="crud-table">
                @forelse ($venta->pagos as $pago)
                    <div class="crud-row"><span><strong>${{ number_format((float) $pago->valor, 2) }}</strong><small>{{ $pago->metodo_pago }}</small></span><span>{{ $pago->referencia ?? 'Sin referencia' }}</span><span>{{ $pago->pagado_at?->format('d/m/Y H:i') }}</span><span>{{ $pago->estado }}</span><span></span></div>
                @empty
                    <div class="crud-row crud-row--empty">Sin pagos registrados.</div>
                @endforelse
            </div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
