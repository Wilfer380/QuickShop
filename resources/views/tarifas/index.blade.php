<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div><span class="crud-eyebrow">Reglas de cobro</span><h1>Tarifas</h1><p>Administra valores base por tipo de vehiculo y periodo de cobro.</p></div>
            <a class="crud-button" href="{{ route('tarifas.create') }}">Nueva tarifa</a>
        </section>
        <section class="crud-panel">
            @if (session('status'))<div class="crud-alert">{{ session('status') }}</div>@endif
            <div class="crud-table">
                <div class="crud-row crud-row--head"><span>Tarifa</span><span>Tipo vehiculo</span><span>Cobro</span><span>Valor</span><span>Acciones</span></div>
                @forelse ($tarifas as $tarifa)
                    <div class="crud-row">
                        <span><strong>{{ $tarifa->nombre }}</strong><small>{{ $tarifa->activa ? 'Activa' : 'Inactiva' }}</small></span>
                        <span>{{ $tarifa->tipo_vehiculo }}</span>
                        <span>{{ $tarifa->tipo_cobro }}</span>
                        <span>${{ number_format((float) $tarifa->valor, 2) }}</span>
                        <span class="crud-stack"><a class="crud-link" href="{{ route('tarifas.show', $tarifa) }}">Ver</a><a class="crud-link" href="{{ route('tarifas.edit', $tarifa) }}">Editar</a><form class="crud-inline-form" action="{{ route('tarifas.destroy', $tarifa) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar esta tarifa?')">@csrf @method('delete')<button class="crud-danger" type="submit">Eliminar</button></form></span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay tarifas registradas.</div>
                @endforelse
            </div>
            <div class="crud-pagination">{{ $tarifas->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
