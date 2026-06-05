<div x-show="createOpen" x-cloak class="client-modal-backdrop" @keydown.escape.window="createOpen = false" @click.self="createOpen = false">
    <div class="client-modal">
        <div class="client-modal__head">
            <div>
                <h2>Nuevo cliente</h2>
                <p>Registra un nuevo cliente con su información de contacto y clasificación.</p>
            </div>
            <button type="button" class="client-modal__close" @click="createOpen = false" aria-label="Cerrar modal">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>

        <div class="client-modal__body">
            <form method="POST" action="{{ route('clientes.store') }}" class="grid gap-4" enctype="multipart/form-data">
                @csrf
                @include('clientes.partials.fields', ['cliente' => new \App\Models\Cliente()])

                <div class="client-form-actions">
                    <button type="button" class="btn-secondary" @click="createOpen = false">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
