<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">CRM interno</span>
                <h1>Clientes</h1>
                <p>Administra datos de contacto y documentos antes de conectar ventas o parqueadero.</p>
            </div>
            <a class="crud-button" href="{{ route('clientes.create') }}">Nuevo cliente</a>
        </section>

        <section class="crud-panel">
            @if (session('status'))
                <div class="crud-alert">{{ session('status') }}</div>
            @endif

            <div class="crud-table">
                <div class="crud-row crud-row--head">
                    <span>Cliente</span><span>Documento</span><span>Contacto</span><span>Vehiculos</span><span>Acciones</span>
                </div>
                @forelse ($clientes as $cliente)
                    <div class="crud-row">
                        <span><strong>{{ $cliente->nombres }} {{ $cliente->apellidos }}</strong><small>{{ $cliente->email ?? 'Sin email' }}</small></span>
                        <span>{{ $cliente->tipo_documento }} {{ $cliente->documento }}</span>
                        <span>{{ $cliente->telefono ?? 'Sin telefono' }}</span>
                        <span>{{ $cliente->vehiculos_count }}</span>
                        <span class="crud-stack">
                            <a class="crud-link" href="{{ route('clientes.show', $cliente) }}">Ver</a>
                            <a class="crud-link" href="{{ route('clientes.edit', $cliente) }}">Editar</a>
                            <form class="crud-inline-form" action="{{ route('clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar este cliente?')">
                                @csrf
                                @method('delete')
                                <button class="crud-danger" type="submit">Eliminar</button>
                            </form>
                        </span>
                    </div>
                @empty
                    <div class="crud-row crud-row--empty">No hay clientes registrados.</div>
                @endforelse
            </div>

            <div class="crud-pagination">{{ $clientes->links() }}</div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
