<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Nuevo registro</span>
                <h1>Crear cliente</h1>
                <p>Registra el cliente base para ventas de vehiculos y parqueadero.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('clientes.store') }}" method="POST">
                @include('clientes._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
