<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Edicion</span>
                <h1>Editar cliente</h1>
                <p>Actualiza los datos principales sin tocar transacciones futuras.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('clientes.update', $cliente) }}" method="POST">
                @method('put')
                @include('clientes._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
