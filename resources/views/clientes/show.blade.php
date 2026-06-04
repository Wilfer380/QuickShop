<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Detalle cliente</span>
                <h1>{{ $cliente->nombres }} {{ $cliente->apellidos }}</h1>
                <p>{{ $cliente->tipo_documento }} {{ $cliente->documento }}</p>
            </div>
            <div class="crud-actions">
                <a class="crud-link" href="{{ route('clientes.edit', $cliente) }}">Editar</a>
                <a class="crud-link" href="{{ route('clientes.index') }}">Volver</a>
            </div>
        </section>

        <section class="crud-panel">
            @if (session('status'))
                <div class="crud-alert">{{ session('status') }}</div>
            @endif
            <div class="crud-detail">
                <div><span>Email</span><strong>{{ $cliente->email ?? 'Sin email' }}</strong></div>
                <div><span>Telefono</span><strong>{{ $cliente->telefono ?? 'Sin telefono' }}</strong></div>
                <div><span>Direccion</span><strong>{{ $cliente->direccion ?? 'Sin direccion' }}</strong></div>
                <div><span>Vehiculos</span><strong>{{ $cliente->vehiculos->count() }}</strong></div>
            </div>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
