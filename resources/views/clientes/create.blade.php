<x-app-layout>
    <x-clientes-styles />

    <section class="clients-page">
        <div class="clients-header">
            <div>
                <h1 class="page-title">Nuevo cliente</h1>
                <p class="page-subtitle">Registra un cliente con la misma experiencia visual del panel</p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn-secondary">Volver a clientes</a>
        </div>

        <section class="client-modal" style="max-height:none;">
            <div class="client-modal__head">
                <div>
                    <h2>Crear cliente</h2>
                    <p>Completa los datos del cliente y guarda el registro.</p>
                </div>
            </div>

            <div class="client-modal__body">
                <form method="POST" action="{{ route('clientes.store') }}" class="grid gap-4" enctype="multipart/form-data">
                    @csrf
                    @include('clientes.partials.fields', ['cliente' => $cliente])

                    <div class="client-form-actions">
                        <a href="{{ route('clientes.index') }}" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Guardar cliente</button>
                    </div>
                </form>
            </div>
        </section>
    </section>
</x-app-layout>
