<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Detalle vehiculo</span>
                <h1>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</h1>
                <p>{{ $vehiculo->placa }} - {{ $vehiculo->estado }}</p>
            </div>
            <div class="crud-actions">
                <a class="crud-link" href="{{ route('vehiculos.edit', $vehiculo) }}">Editar</a>
                <a class="crud-link" href="{{ route('vehiculos.index') }}">Volver</a>
            </div>
        </section>

        <section class="crud-panel">
            @if (session('status'))
                <div class="crud-alert">{{ session('status') }}</div>
            @endif
            <div class="crud-detail">
                <div><span>Cliente</span><strong>{{ $vehiculo->cliente?->nombres ?? 'Sin cliente' }}</strong></div>
                <div><span>Tipo</span><strong>{{ $vehiculo->tipo }}</strong></div>
                <div><span>Anio</span><strong>{{ $vehiculo->anio ?? 'No registrado' }}</strong></div>
                <div><span>Precio</span><strong>{{ $vehiculo->precio_venta ? '$'.number_format((float) $vehiculo->precio_venta, 2) : 'Sin precio' }}</strong></div>
                <div><span>VIN</span><strong>{{ $vehiculo->vin ?? 'Sin VIN' }}</strong></div>
                <div><span>Kilometraje</span><strong>{{ $vehiculo->kilometraje ?? 'Sin dato' }}</strong></div>
            </div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
