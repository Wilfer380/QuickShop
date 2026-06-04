<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Inventario interno</span>
                <h1>Vehiculos</h1>
                <p>Gestiona unidades disponibles para venta y parqueadero sin crear transacciones todavia.</p>
            </div>
            <a class="crud-button" href="{{ route('vehiculos.create') }}">Nuevo vehiculo</a>
        </section>

        <section class="crud-panel">
            @if (session('status'))
                <div class="crud-alert">{{ session('status') }}</div>
            @endif
            <div class="crud-table">
                <div class="crud-row crud-row--head"><span>Vehiculo</span><span>Placa</span><span>Cliente</span><span>Estado</span><span>Acciones</span></div>
                @forelse ($vehiculos as $vehiculo)
                    <div class="crud-row">
                        <span><strong>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong><small>{{ $vehiculo->tipo }} {{ $vehiculo->anio }}</small></span>
                        <span>{{ $vehiculo->placa }}</span>
                        <span>{{ $vehiculo->cliente?->nombres ?? 'Sin cliente' }}</span>
                        <span>{{ $vehiculo->estado }}</span>
                        <span class="crud-stack">
                            <a class="crud-link" href="{{ route('vehiculos.show', $vehiculo) }}">Ver</a>
                            <a class="crud-link" href="{{ route('vehiculos.edit', $vehiculo) }}">Editar</a>
                            <form class="crud-inline-form" action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar este vehiculo?')">
                                @csrf
                                @method('delete')
                                <button class="crud-danger" type="submit">Eliminar</button>
                            </form>
                        </span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay vehiculos registrados.</div>
                @endforelse
            </div>
            <div class="crud-pagination">{{ $vehiculos->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
