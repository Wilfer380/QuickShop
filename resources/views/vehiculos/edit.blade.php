<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Edicion</span>
                <h1>Editar vehiculo</h1>
                <p>Actualiza la informacion del inventario.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('vehiculos.update', $vehiculo) }}" method="POST">
                @method('put')
                @include('vehiculos._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
