<div x-cloak x-show="createOpen" class="client-modal-backdrop" @click.self="createOpen = false" @keydown.escape.window="createOpen = false">
    <div class="client-modal" @click.stop>
        <div class="client-modal__head">
            <div>
                <h2>Nueva venta</h2>
                <p>Registra el cierre comercial sin salir del tablero.</p>
            </div>
            <button class="client-modal__close" type="button" @click="createOpen = false">×</button>
        </div>
        <div class="client-modal__body">
            @php
                $venta = new \App\Models\Venta(['fecha_venta' => now()->toDateString(), 'descuento' => 0, 'impuestos' => 0]);
                $action = route('ventas.store');
                $method = 'POST';
            @endphp
            <form class="sale-form" method="POST" action="{{ $action }}">
                @include('ventas.partials.fields')
            </form>
        </div>
    </div>
</div>
