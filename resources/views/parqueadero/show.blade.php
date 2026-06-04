<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero"><div><span class="crud-eyebrow">Ticket parqueadero</span><h1>Movimiento #{{ $movimiento->id }}</h1><p>{{ $movimiento->vehiculo->placa ?? 'Sin placa' }} - {{ $movimiento->vehiculo->marca }} {{ $movimiento->vehiculo->modelo }}</p></div><a class="crud-link" href="{{ route('parqueadero.index') }}">Volver</a></section>
        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-grid">
                <p><strong>Entrada:</strong> {{ $movimiento->entrada_at->format('d/m/Y H:i') }}</p>
                <p><strong>Salida:</strong> {{ $movimiento->salida_at?->format('d/m/Y H:i') ?? 'Activa' }}</p>
                <p><strong>Cupo:</strong> {{ $movimiento->cupo?->codigo ?? 'Sin cupo' }}</p>
                <p><strong>Total:</strong> {{ $movimiento->total ? '$'.number_format((float) $movimiento->total, 2) : 'Pendiente' }}</p>
            </div>
            @if ($movimiento->estado === 'abierto')
                <form class="crud-form" method="POST" action="{{ route('parqueadero.salida', $movimiento) }}">
                    @csrf
                    <div class="crud-grid">
                        <label><span>Salida</span><input type="datetime-local" name="salida_at"></label>
                        <label><span>Pago salida</span><input type="number" name="pago_salida" min="0" step="0.01" value="0"></label>
                        <label><span>Metodo</span><select name="metodo_pago"><option value="efectivo">Efectivo</option><option value="tarjeta">Tarjeta</option><option value="transferencia">Transferencia</option></select></label>
                        <label><span>Referencia</span><input type="text" name="referencia"></label>
                    </div>
                    <button class="crud-button" type="submit">Registrar salida</button>
                </form>
            @endif
            <h2>Pagos</h2>
            <div class="crud-table">@forelse ($movimiento->pagos as $pago)<div class="crud-row"><span><strong>${{ number_format((float) $pago->valor, 2) }}</strong><small>{{ $pago->metodo_pago }}</small></span><span>{{ $pago->referencia ?? 'Sin referencia' }}</span><span>{{ $pago->pagado_at?->format('d/m/Y H:i') }}</span><span>{{ $pago->estado }}</span><span></span></div>@empty<div class="crud-row crud-row--empty">Sin pagos registrados.</div>@endforelse</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
