<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero">
            <div>
                <span class="crud-eyebrow">Cierre comercial</span>
                <h1>Nueva venta</h1>
                <p>La venta se registra en transaccion, marca el vehiculo como vendido y guarda el abono inicial si existe.</p>
            </div>
            <a class="crud-link" href="{{ route('ventas.index') }}">Volver</a>
        </section>

        <section class="crud-panel">
            @if ($errors->any())<div class="crud-alert">Revisa los datos del formulario.</div>@endif
            <form class="crud-form" method="POST" action="{{ route('ventas.store') }}">
                @csrf
                <div class="crud-grid">
                    <label><span>Cliente</span><select name="cliente_id" required>@foreach ($clientes as $cliente)<option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>{{ $cliente->nombres }} {{ $cliente->apellidos }} - {{ $cliente->documento }}</option>@endforeach</select></label>
                    <label><span>Vehiculo disponible</span><select name="vehiculo_id" required>@foreach ($vehiculos as $vehiculo)<option value="{{ $vehiculo->id }}" @selected(old('vehiculo_id') == $vehiculo->id)>{{ $vehiculo->marca }} {{ $vehiculo->modelo }} {{ $vehiculo->placa }} - ${{ number_format((float) $vehiculo->precio_venta, 2) }}</option>@endforeach</select></label>
                    <label><span>Fecha venta</span><input type="date" name="fecha_venta" value="{{ old('fecha_venta', now()->toDateString()) }}" required></label>
                    <label><span>Precio base</span><input type="number" name="precio_base" min="0" step="0.01" value="{{ old('precio_base') }}" required></label>
                    <label><span>Descuento</span><input type="number" name="descuento" min="0" step="0.01" value="{{ old('descuento', 0) }}"></label>
                    <label><span>Impuestos</span><input type="number" name="impuestos" min="0" step="0.01" value="{{ old('impuestos', 0) }}"></label>
                    <label><span>Pago inicial</span><input type="number" name="pago_inicial" min="0" step="0.01" value="{{ old('pago_inicial', 0) }}"></label>
                    <label><span>Metodo de pago</span><select name="metodo_pago"><option value="efectivo">Efectivo</option><option value="tarjeta">Tarjeta</option><option value="transferencia">Transferencia</option></select></label>
                </div>
                <label><span>Referencia de pago</span><input type="text" name="referencia" value="{{ old('referencia') }}"></label>
                <label><span>Notas</span><textarea name="notas" rows="4">{{ old('notas') }}</textarea></label>
                <button class="crud-button" type="submit">Registrar venta</button>
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
