<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero"><div><span class="crud-eyebrow">Recaudo manual</span><h1>Nuevo pago</h1><p>Selecciona una venta pendiente o un movimiento cerrado de parqueadero.</p></div><a class="crud-link" href="{{ route('pagos.index') }}">Volver</a></section>
        <section class="crud-panel">
            @if ($errors->any())<div class="crud-alert">Revisa los datos del formulario.</div>@endif
            <form class="crud-form" method="POST" action="{{ route('pagos.store') }}">
                @csrf
                <div class="crud-grid">
                    <label><span>Concepto</span><select name="concepto" required><option value="venta">Venta</option><option value="parqueadero">Parqueadero</option></select></label>
                    <label><span>Venta pendiente</span><select name="venta_id"><option value="">No aplica</option>@foreach ($ventas as $venta)<option value="{{ $venta->id }}">#{{ $venta->id }} - {{ $venta->cliente->nombres }} - ${{ number_format((float) $venta->total, 0, ',', '.') }}</option>@endforeach</select></label>
                    <label><span>Movimiento parqueadero</span><select name="movimiento_parqueadero_id"><option value="">No aplica</option>@foreach ($movimientos as $movimiento)<option value="{{ $movimiento->id }}">#{{ $movimiento->id }} - {{ $movimiento->vehiculo->placa ?? 'Sin placa' }} - ${{ number_format((float) $movimiento->total, 0, ',', '.') }}</option>@endforeach</select></label>
                    <label><span>Valor</span><input type="text" name="valor" inputmode="numeric" autocomplete="off" placeholder="5.000" data-money-input="true" value="{{ old('valor') }}" required></label>
                    <label><span>Metodo</span><select name="metodo_pago" required><option value="efectivo">Efectivo</option><option value="tarjeta">Tarjeta</option><option value="transferencia">Transferencia</option></select></label>
                    <label><span>Fecha pago</span><input type="datetime-local" name="pagado_at" value="{{ old('pagado_at') }}"></label>
                </div>
                <label><span>Referencia</span><input type="text" name="referencia" value="{{ old('referencia') }}"></label>
                <label><span>Notas</span><textarea name="notas" rows="4">{{ old('notas') }}</textarea></label>
                <button class="crud-button" type="submit">Registrar pago</button>
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
