<x-app-layout>
    <x-clientes-styles />

    <section class="clients-page">
        <div class="clients-header">
            <div>
                <h1 class="page-title">Editar cliente</h1>
                <p class="page-subtitle">Actualiza la información del cliente seleccionado</p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn-secondary">Volver a clientes</a>
        </div>

        <section class="client-modal" style="max-height:none;">
            <div class="client-modal__head">
                <div>
                    <h2>{{ $cliente->nombres }} {{ $cliente->apellidos }}</h2>
                    <p>{{ $cliente->tipo_documento }} {{ $cliente->documento }}</p>
                </div>
            </div>

            <div class="client-modal__body">
                <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="grid gap-4" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('clientes.partials.fields', ['cliente' => $cliente])

                    <div class="client-form-actions">
                        <a href="{{ route('clientes.index') }}" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Actualizar cliente</button>
                    </div>
                </form>
            </div>
        </section>
    </section>
</x-app-layout>
