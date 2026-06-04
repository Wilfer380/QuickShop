<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Edicion</span>
                <h1>Editar cupo</h1>
                <p>Actualiza zona, tipo o estado del cupo.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('cupos.update', $cupo) }}" method="POST">
                @method('put')
                @include('cupos._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
