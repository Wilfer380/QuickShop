<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Nuevo registro</span>
                <h1>Crear cupo</h1>
                <p>Define un espacio operativo para el parqueadero.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('cupos.store') }}" method="POST">
                @include('cupos._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
