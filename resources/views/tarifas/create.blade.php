<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Nuevo registro</span>
                <h1>Crear tarifa</h1>
                <p>Define una regla base de cobro para parqueadero.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('tarifas.store') }}" method="POST">
                @include('tarifas._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
