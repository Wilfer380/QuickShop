<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Recaudo</span>
                <h1>Pagos</h1>
                <p>Registra abonos de ventas y parqueadero con validacion de valores positivos.</p>
            </div>
            <a class="crud-button" href="{{ route('pagos.create') }}">Nuevo pago</a>
        </section>

        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-table">
                <div class="crud-row crud-row--head"><span>Pago</span><span>Concepto</span><span>Cliente</span><span>Estado</span><span>Acciones</span></div>
                @forelse ($pagos as $pago)
                    <div class="crud-row">
                        <span><strong>${{ number_format((float) $pago->valor, 0, ',', '.') }}</strong><small>{{ $pago->pagado_at?->format('d/m/Y H:i') }}</small></span>
                        <span>{{ ucfirst($pago->concepto) }} / {{ $pago->metodo_pago }}</span>
                        <span>{{ $pago->cliente?->nombres ?? 'Sin cliente' }}</span>
                        <span>{{ ucfirst($pago->estado) }}</span>
                        <span><a class="crud-link" href="{{ route('pagos.show', $pago) }}">Ver</a></span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay pagos registrados.</div>
                @endforelse
            </div>
            <div class="crud-pagination">{{ $pagos->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
