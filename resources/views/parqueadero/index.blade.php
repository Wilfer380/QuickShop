<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Operaciones</span>
                <h1>Parqueadero</h1>
                <p>{{ $activos }} movimientos activos con control de cupos, tarifa automatica y salida transaccional.</p>
            </div>
            <a class="crud-button" href="{{ route('parqueadero.create') }}">Registrar entrada</a>
        </section>

        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-table">
                <div class="crud-row crud-row--head"><span>Vehiculo</span><span>Cupo</span><span>Entrada</span><span>Estado</span><span>Acciones</span></div>
                @forelse ($movimientos as $movimiento)
                    <div class="crud-row">
                        <span><strong>{{ $movimiento->vehiculo->placa ?? 'Sin placa' }}</strong><small>{{ $movimiento->vehiculo->marca }} {{ $movimiento->vehiculo->modelo }}</small></span>
                        <span>{{ $movimiento->cupo?->codigo ?? 'Sin cupo' }}</span>
                        <span>{{ $movimiento->entrada_at->format('d/m/Y H:i') }}</span>
                        <span>{{ ucfirst($movimiento->estado) }}</span>
                        <span><a class="crud-link" href="{{ route('parqueadero.show', $movimiento) }}">Ver</a></span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay movimientos registrados.</div>
                @endforelse
            </div>
            <div class="crud-pagination">{{ $movimientos->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
