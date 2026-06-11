@if (isset($inlineVenta))
    <div x-cloak x-show="facturaOpen" class="client-modal-backdrop" @keydown.escape.window="facturaOpen = false">
        <div class="client-modal" @click.outside="facturaOpen = false">
            <div class="client-modal__head"><div><h2>Factura de venta</h2><p>Vista imprimible de la venta #{{ $inlineVenta->id }}.</p></div><button class="client-modal__close" type="button" @click="facturaOpen = false">×</button></div>
            <div class="client-modal__body invoice-print-area"><div class="invoice-box"><h3>VehiPark · Factura #{{ $inlineVenta->id }}</h3><div class="invoice-line"><span>Cliente</span><strong>{{ $inlineVenta->cliente->nombres }} {{ $inlineVenta->cliente->apellidos }}</strong></div><div class="invoice-line"><span>Vehículo</span><strong>{{ $inlineVenta->vehiculo->marca }} {{ $inlineVenta->vehiculo->modelo }}</strong></div><div class="invoice-line"><span>Precio base</span><strong>${{ number_format((float) $inlineVenta->precio_base, 0, ',', '.') }}</strong></div><div class="invoice-line"><span>Descuento</span><strong>${{ number_format((float) $inlineVenta->descuento, 0, ',', '.') }}</strong></div><div class="invoice-line"><span>Impuestos</span><strong>${{ number_format((float) $inlineVenta->impuestos, 0, ',', '.') }}</strong></div><div class="invoice-line"><span>Total</span><strong>${{ number_format((float) $inlineVenta->total, 0, ',', '.') }}</strong></div><div class="invoice-line"><span>Pagado</span><strong>${{ number_format($inlinePagado, 0, ',', '.') }}</strong></div><div class="invoice-line"><span>Saldo</span><strong>${{ number_format($inlineSaldo, 0, ',', '.') }}</strong></div></div><div class="sale-actions invoice-actions" style="margin-top:16px"><button class="btn-primary" type="button" onclick="window.print()">Imprimir factura</button><button class="btn-secondary" type="button" @click="facturaOpen = false">Cerrar</button></div></div>
        </div>
    </div>
@else
    <div x-cloak x-show="facturaOpen" class="client-modal-backdrop" @keydown.escape.window="facturaOpen = false">
        <div class="client-modal" @click.outside="facturaOpen = false">
            <div class="client-modal__head"><div><h2>Factura de venta</h2><p>Vista imprimible de la venta #<span x-text="selected.id"></span>.</p></div><button class="client-modal__close" type="button" @click="facturaOpen = false">×</button></div>
            <div class="client-modal__body invoice-print-area"><div class="invoice-box"><h3>VehiPark · Factura #<span x-text="selected.id"></span></h3><div class="invoice-line"><span>Cliente</span><strong x-text="selected.cliente"></strong></div><div class="invoice-line"><span>Vehículo</span><strong x-text="selected.vehiculo"></strong></div><div class="invoice-line"><span>Precio base</span><strong x-text="selected.precio_base"></strong></div><div class="invoice-line"><span>Descuento</span><strong x-text="selected.descuento"></strong></div><div class="invoice-line"><span>Impuestos</span><strong x-text="selected.impuestos"></strong></div><div class="invoice-line"><span>Total</span><strong x-text="selected.total"></strong></div><div class="invoice-line"><span>Pagado</span><strong x-text="selected.pagado"></strong></div><div class="invoice-line"><span>Saldo</span><strong x-text="selected.saldo"></strong></div></div><div class="sale-actions invoice-actions" style="margin-top:16px"><button class="btn-primary" type="button" onclick="window.print()">Imprimir factura</button><button class="btn-secondary" type="button" @click="facturaOpen = false">Cerrar</button></div></div>
        </div>
    </div>
@endif

<style>
    @media print {
        body * { visibility: hidden !important; }
        .invoice-print-area, .invoice-print-area * { visibility: visible !important; }
        .invoice-print-area {
            position: fixed !important;
            inset: 0 !important;
            background: #fff !important;
            color: #111827 !important;
            padding: 24px !important;
        }
        .invoice-print-area .invoice-box {
            border: 1px solid #d1d5db !important;
            background: #fff !important;
            color: #111827 !important;
        }
        .invoice-print-area .sale-actions,
        .invoice-print-area .invoice-actions,
        .client-modal__close,
        .client-modal__head {
            display: none !important;
        }
    }
</style>
