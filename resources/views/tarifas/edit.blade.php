<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Edicion</span>
                <h1>Editar tarifa</h1>
                <p>Actualiza valores y vigencia sin generar cobros.</p>
            </div>
        </section>

        <section class="crud-panel">
            <form class="crud-form" action="{{ route('tarifas.update', $tarifa) }}" method="POST">
                @method('put')
                @include('tarifas._form')
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
