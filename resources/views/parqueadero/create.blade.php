<x-app-layout>
    <div class="crud-page">
        <section class="crud-hero"><div><span class="crud-eyebrow">Ingreso</span><h1>Registrar entrada</h1><p>Reserva el cupo seleccionado y abre un movimiento activo para el vehiculo.</p></div><a class="crud-link" href="{{ route('parqueadero.index') }}">Volver</a></section>
        <section class="crud-panel">
            @if ($errors->any())<div class="crud-alert">Revisa los datos del formulario.</div>@endif
            <form class="crud-form" method="POST" action="{{ route('parqueadero.store') }}">
                @csrf
                <div class="crud-grid">
                    <label><span>Vehiculo</span><select name="vehiculo_id" required>@foreach ($vehiculos as $vehiculo)<option value="{{ $vehiculo->id }}">{{ $vehiculo->placa ?? 'Sin placa' }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</option>@endforeach</select></label>
                    <label><span>Cupo</span><select name="cupo_id"><option value="">Sin cupo asignado</option>@foreach ($cupos as $cupo)<option value="{{ $cupo->id }}">{{ $cupo->codigo }} - {{ $cupo->zona }}</option>@endforeach</select></label>
                    <label><span>Tarifa</span><select name="tarifa_id" required>@foreach ($tarifas as $tarifa)<option value="{{ $tarifa->id }}">{{ $tarifa->nombre }} / {{ $tarifa->tipo_cobro }} - ${{ number_format((float) $tarifa->valor, 2) }}</option>@endforeach</select></label>
                    <label><span>Entrada</span><input type="datetime-local" name="entrada_at" value="{{ old('entrada_at') }}"></label>
                </div>
                <label><span>Observaciones</span><textarea name="observaciones" rows="4">{{ old('observaciones') }}</textarea></label>
                <button class="crud-button" type="submit">Abrir movimiento</button>
            </form>
        </section>
    </div>
    <x-admin-crud-styles />
</x-app-layout>
