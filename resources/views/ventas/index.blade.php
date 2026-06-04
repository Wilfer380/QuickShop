<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Comercial</span>
                <h1>Ventas</h1>
                <p>Registra cierres con actualizacion automatica del vehiculo y estado de pago.</p>
            </div>
            <a class="crud-button" href="{{ route('ventas.create') }}">Nueva venta</a>
        </section>

        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-table">
                <div class="crud-row crud-row--head"><span>Venta</span><span>Cliente</span><span>Total</span><span>Estado</span><span>Acciones</span></div>
                @forelse ($ventas as $venta)
                    <div class="crud-row">
                        <span><strong>{{ $venta->vehiculo->marca }} {{ $venta->vehiculo->modelo }}</strong><small>{{ $venta->fecha_venta->format('d/m/Y') }}</small></span>
                        <span>{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</span>
                        <span>${{ number_format((float) $venta->total, 2) }}</span>
                        <span>{{ ucfirst($venta->estado) }}</span>
                        <span><a class="crud-link" href="{{ route('ventas.show', $venta) }}">Ver</a></span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay ventas registradas.</div>
                @endforelse
            </div>
            <div class="crud-pagination">{{ $ventas->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
