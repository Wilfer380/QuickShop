@csrf

@php
    $isUpdate = strtoupper($method ?? 'POST') !== 'POST';
    $fechaVenta = $venta->fecha_venta ? \Illuminate\Support\Carbon::parse($venta->fecha_venta)->format('Y-m-d') : now()->toDateString();
    $precioBaseValue = old('precio_base');
    $descuentoValue = old('descuento');
    $impuestosValue = old('impuestos');
    $pagoInicialValue = old('pago_inicial');
    $vehiculosOptions = collect($vehiculos ?? []);
    $vehiculosVendibles = $vehiculosOptions->reject(fn ($vehiculo) => in_array($vehiculo->estado, ['vendido', 'inactivo'], true));
    $vehiculosAgotados = $vehiculosVendibles->isEmpty();
    $sinVehiculosRegistrados = $vehiculosOptions->isEmpty();

    if ($precioBaseValue === null) {
        $precioBaseValue = number_format((float) ($venta->precio_base ?? 0), 0, ',', '.');
    }

    if ($descuentoValue === null) {
        $descuentoValue = number_format((float) ($venta->descuento ?? 0), 0, ',', '.');
    }

    if ($impuestosValue === null) {
        $impuestosValue = number_format((float) ($venta->impuestos ?? 0), 0, ',', '.');
    }

    if ($pagoInicialValue === null) {
        $pagoInicialValue = number_format((float) old('pago_inicial', 0), 0, ',', '.');
    }
@endphp

<div class="sale-grid">
    <div class="sale-field">
        <label for="cliente_id"><span>Cliente</span></label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Seleccionar cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id }}" @selected((string) old('cliente_id', $venta->cliente_id) === (string) $cliente->id)>{{ $cliente->nombres }} {{ $cliente->apellidos }} - {{ $cliente->documento }}</option>
            @endforeach
        </select>
        @error('cliente_id') <div class="crud-error">{{ $message }}</div> @enderror
    </div>

    <div class="sale-field">
        <label for="vehiculo_id"><span>Vehículo</span></label>
        <input id="vehiculo_search" type="search" placeholder="Buscar por placa, marca o modelo" autocomplete="off">
        <select id="vehiculo_id" name="vehiculo_id" required>
            @if ($sinVehiculosRegistrados)
                <option value="">No hay vehículos registrados</option>
            @else
                <option value="">Seleccionar vehículo</option>
                @foreach ($vehiculosOptions as $vehiculo)
                    @php
                        $agotado = in_array($vehiculo->estado, ['vendido', 'inactivo'], true);
                        $seleccionado = (string) old('vehiculo_id', $venta->vehiculo_id) === (string) $vehiculo->id;
                        $label = $vehiculo->marca . ' ' . $vehiculo->modelo . ' ' . $vehiculo->placa . ' - $' . number_format((float) $vehiculo->precio_venta, 0, ',', '.');
                        $estadoLabel = match ($vehiculo->estado) {
                            'vendido' => 'Este vehículo ya está vendido',
                            'inactivo' => 'Este vehículo está inactivo',
                            default => ucfirst((string) $vehiculo->estado),
                        };
                    @endphp
                    <option value="{{ $vehiculo->id }}" @selected($seleccionado) @disabled($agotado && ! $seleccionado)>{{ $label }}{{ $agotado && $seleccionado ? ' · Agotado' : '' }}{{ $agotado && ! $seleccionado ? ' · ' . $estadoLabel : '' }}</option>
                @endforeach
            @endif
        </select>
        @error('vehiculo_id') <div class="crud-error">{{ $message }}</div> @enderror
        @if ($vehiculosAgotados && ! $sinVehiculosRegistrados)
            <div class="crud-error">No hay vehículos habilitados para venta en este momento.</div>
        @endif
    </div>
</div>

<div class="sale-grid sale-grid--3">
    <div class="sale-field">
        <label for="fecha_venta"><span>Fecha venta</span></label>
        <input id="fecha_venta" type="date" name="fecha_venta" value="{{ old('fecha_venta', $fechaVenta) }}" required>
        @error('fecha_venta') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="sale-field">
        <label for="precio_base"><span>Precio base</span></label>
        <input id="precio_base" type="text" name="precio_base" inputmode="numeric" autocomplete="off" placeholder="3.000.000" data-money-input="true" value="{{ $precioBaseValue }}" required>
        @error('precio_base') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="sale-field">
        <label for="descuento"><span>Descuento</span></label>
        <input id="descuento" type="text" name="descuento" inputmode="numeric" autocomplete="off" placeholder="0" data-money-input="true" value="{{ $descuentoValue }}">
        @error('descuento') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="sale-grid sale-grid--3">
    <div class="sale-field">
        <label for="impuestos"><span>Impuestos</span></label>
        <input id="impuestos" type="text" name="impuestos" inputmode="numeric" autocomplete="off" placeholder="0" data-money-input="true" value="{{ $impuestosValue }}">
        @error('impuestos') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    @unless ($isUpdate)
        <div class="sale-field">
            <label for="pago_inicial"><span>Pago inicial</span></label>
            <input id="pago_inicial" type="text" name="pago_inicial" inputmode="numeric" autocomplete="off" placeholder="0" data-money-input="true" value="{{ $pagoInicialValue }}">
            @error('pago_inicial') <div class="crud-error">{{ $message }}</div> @enderror
        </div>
        <div class="sale-field">
            <label for="metodo_pago"><span>Método de pago</span></label>
            <select id="metodo_pago" name="metodo_pago">
                @foreach (['efectivo' => 'Efectivo', 'tarjeta' => 'Tarjeta', 'transferencia' => 'Transferencia'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('metodo_pago', 'efectivo') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('metodo_pago') <div class="crud-error">{{ $message }}</div> @enderror
        </div>
    @else
        <div class="sale-field">
            <label><span>Total actual</span></label>
            <input type="text" value="${{ number_format((float) $venta->total, 0, ',', '.') }}" disabled>
        </div>
        <div class="sale-field">
            <label><span>Estado actual</span></label>
            <input type="text" value="{{ ucfirst((string) $venta->estado) }}" disabled>
        </div>
    @endunless
</div>

@unless ($isUpdate)
    <div class="sale-field sale-field--full">
        <label for="referencia"><span>Referencia de pago inicial</span></label>
        <input id="referencia" type="text" name="referencia" value="{{ old('referencia') }}">
        @error('referencia') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="sale-field sale-field--full">
        <label for="notas_pago"><span>Notas del pago</span></label>
        <textarea id="notas_pago" name="notas_pago">{{ old('notas_pago') }}</textarea>
        @error('notas_pago') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
@endunless

<div class="sale-field sale-field--full">
    <label for="notas"><span>Notas de la venta</span></label>
    <textarea id="notas" name="notas">{{ old('notas', $venta->notas) }}</textarea>
    @error('notas') <div class="crud-error">{{ $message }}</div> @enderror
</div>

<div class="sale-actions">
    <button class="btn-primary" type="submit">{{ $isUpdate ? 'Actualizar venta' : 'Registrar venta' }}</button>
    <a class="btn-secondary" href="{{ route('ventas.index') }}">Cancelar</a>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const search = document.getElementById('vehiculo_search');
            const select = document.getElementById('vehiculo_id');
            if (!search || !select || select.dataset.autocompleteReady === '1') return;

            select.dataset.autocompleteReady = '1';
            const placeholder = select.querySelector('option[value=""]')?.cloneNode(true);
            const originalOptions = Array.from(select.querySelectorAll('option')).map((option) => ({
                value: option.value,
                text: option.textContent,
                disabled: option.disabled,
                selected: option.selected,
            }));

            const render = (query = '') => {
                const normalized = query.trim().toLowerCase();
                const selectedValue = select.value;
                const filtered = originalOptions.filter((option) => {
                    if (option.value === '') return true;
                    if (!normalized) return true;
                    return option.text.toLowerCase().includes(normalized);
                });

                select.innerHTML = '';
                if (placeholder) select.appendChild(placeholder.cloneNode(true));

                const items = filtered.filter((option) => option.value !== '');
                if (items.length === 0) {
                    const empty = document.createElement('option');
                    empty.value = '';
                    empty.textContent = normalized ? 'Sin coincidencias' : 'Seleccionar vehículo';
                    empty.disabled = true;
                    empty.selected = true;
                    select.appendChild(empty);
                    select.value = '';
                    return;
                }

                items.forEach((option) => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    opt.disabled = option.disabled;
                    select.appendChild(opt);
                });

                if (selectedValue) {
                    select.value = selectedValue;
                }
            };

            search.addEventListener('input', () => render(search.value));
            render(search.value);
        });
    </script>
@endpush
