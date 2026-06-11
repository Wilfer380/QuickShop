@if (isset($inlineVenta))
    <div x-cloak x-show="abonoOpen" class="client-modal-backdrop" @keydown.escape.window="abonoOpen = false">
        <div class="client-modal" @click.outside="abonoOpen = false">
            <div class="client-modal__head"><div><h2>Registrar abono</h2><p>Venta #{{ $inlineVenta->id }} · saldo ${{ number_format($inlineSaldo, 0, ',', '.') }}</p></div><button class="client-modal__close" type="button" @click="abonoOpen = false">×</button></div>
            <div class="client-modal__body">
                <form class="sale-form" method="POST" action="{{ route('pagos.store') }}">
                    @csrf
                    <input type="hidden" name="concepto" value="venta"><input type="hidden" name="venta_id" value="{{ $inlineVenta->id }}"><input type="hidden" name="cliente_id" value="{{ $inlineVenta->cliente_id }}">
                    @include('ventas.partials.abono-modal-fields', ['maxSaldo' => $inlineSaldo])
                </form>
            </div>
        </div>
    </div>
@else
    <div x-cloak x-show="abonoOpen" class="client-modal-backdrop" @keydown.escape.window="abonoOpen = false">
        <div class="client-modal" @click.outside="abonoOpen = false">
            <div class="client-modal__head"><div><h2>Registrar abono</h2><p>Venta #<span x-text="selected.id"></span> · saldo <span x-text="selected.saldo"></span></p></div><button class="client-modal__close" type="button" @click="abonoOpen = false">×</button></div>
            <div class="client-modal__body">
                <form class="sale-form" method="POST" action="{{ route('pagos.store') }}">
                    @csrf
                    <input type="hidden" name="concepto" value="venta"><input type="hidden" name="venta_id" :value="selected.id">
                    @include('ventas.partials.abono-modal-fields')
                </form>
            </div>
        </div>
    </div>
@endif
