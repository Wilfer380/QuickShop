<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div><span class="crud-eyebrow">Parqueadero</span><h1>Cupos</h1><p>Administra codigos, zonas y disponibilidad base del parqueadero.</p></div>
            <a class="crud-button" href="{{ route('cupos.create') }}">Nuevo cupo</a>
        </section>
        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-table">
                <div class="crud-row crud-row--head"><span>Cupo</span><span>Zona</span><span>Tipo</span><span>Estado</span><span>Acciones</span></div>
                @forelse ($cupos as $cupo)
                    <div class="crud-row">
                        <span><strong>{{ $cupo->codigo }}</strong><small>{{ $cupo->observaciones ?? 'Sin observaciones' }}</small></span>
                        <span>{{ $cupo->zona ?? 'Sin zona' }}</span>
                        <span>{{ $cupo->tipo_vehiculo }}</span>
                        <span>{{ $cupo->estado }}</span>
                        <span class="crud-stack"><a class="crud-link" href="{{ route('cupos.show', $cupo) }}">Ver</a><a class="crud-link" href="{{ route('cupos.edit', $cupo) }}">Editar</a><form class="crud-inline-form" action="{{ route('cupos.destroy', $cupo) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar este cupo?')">@csrf @method('delete')<button class="crud-danger" type="submit">Eliminar</button></form></span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay cupos registrados.</div>
                @endforelse
            </div>
            <div class="crud-pagination">{{ $cupos->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
