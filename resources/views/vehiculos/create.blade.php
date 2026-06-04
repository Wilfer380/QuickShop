<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Nuevo registro</span>
                <h1>Crear vehiculo</h1>
                <p>Registra una unidad base del inventario.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('vehiculos.store') }}" method="POST">
                @include('vehiculos._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
