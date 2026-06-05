@csrf

@php
    $ubicaciones = $ubicaciones ?? ['inventario venta' => 'Inventario venta', 'parqueadero' => 'Parqueadero', 'taller' => 'Taller', 'vendido' => 'Vendido', 'reservado' => 'Reservado'];
@endphp

<div class="vehicle-field vehicle-field--full vehicle-upload">
    <label for="imagen"><span>Foto del vehículo</span></label>
    <input id="imagen" type="file" name="imagen" accept="image/jpeg,image/png,image/webp">
    @error('imagen') <div class="crud-error">{{ $message }}</div> @enderror
</div>

<div class="vehicle-field vehicle-field--full">
    <label for="cliente_id"><span>Cliente propietario</span></label>
    <select id="cliente_id" name="cliente_id">
        <option value="">Sin cliente asignado</option>
        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}" @selected((string) old('cliente_id', $vehiculo->cliente_id) === (string) $cliente->id)>{{ $cliente->nombres }} {{ $cliente->apellidos }} - {{ $cliente->documento }}</option>
        @endforeach
    </select>
    @error('cliente_id') <div class="crud-error">{{ $message }}</div> @enderror
</div>

<div class="vehicle-grid">
    <div class="vehicle-field">
        <label for="placa"><span>Placa</span></label>
        <input id="placa" type="text" name="placa" value="{{ old('placa', $vehiculo->placa) }}" required>
        @error('placa') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="tipo"><span>Tipo</span></label>
        <select id="tipo" name="tipo" required>
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo }}" @selected(old('tipo', $vehiculo->tipo) === $tipo)>{{ ucfirst($tipo) }}</option>
            @endforeach
        </select>
        @error('tipo') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="vehicle-grid">
    <div class="vehicle-field">
        <label for="marca"><span>Marca</span></label>
        <input id="marca" type="text" name="marca" value="{{ old('marca', $vehiculo->marca) }}" required>
        @error('marca') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="modelo"><span>Modelo</span></label>
        <input id="modelo" type="text" name="modelo" value="{{ old('modelo', $vehiculo->modelo) }}" required>
        @error('modelo') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="vehicle-grid vehicle-grid--3">
    <div class="vehicle-field">
        <label for="anio"><span>Año</span></label>
        <input id="anio" type="number" name="anio" min="1900" max="{{ now()->addYear()->year }}" value="{{ old('anio', $vehiculo->anio) }}">
        @error('anio') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="color"><span>Color</span></label>
        <input id="color" type="text" name="color" value="{{ old('color', $vehiculo->color) }}">
        @error('color') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="ubicacion"><span>Ubicación</span></label>
        <select id="ubicacion" name="ubicacion">
            @foreach ($ubicaciones as $value => $label)
                <option value="{{ $value }}" @selected(old('ubicacion', $vehiculo->ubicacion) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('ubicacion') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="vehicle-grid vehicle-grid--3">
    <div class="vehicle-field">
        <label for="vin"><span>VIN</span></label>
        <input id="vin" type="text" name="vin" value="{{ old('vin', $vehiculo->vin) }}">
        @error('vin') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="kilometraje"><span>Kilometraje</span></label>
        <input id="kilometraje" type="number" name="kilometraje" min="0" value="{{ old('kilometraje', $vehiculo->kilometraje) }}">
        @error('kilometraje') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="precio_compra"><span>Precio compra</span></label>
        <input id="precio_compra" type="number" name="precio_compra" min="0" step="0.01" value="{{ old('precio_compra', $vehiculo->precio_compra) }}">
        @error('precio_compra') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="vehicle-grid">
    <div class="vehicle-field">
        <label for="precio_venta"><span>Precio venta</span></label>
        <input id="precio_venta" type="number" name="precio_venta" min="0" step="0.01" value="{{ old('precio_venta', $vehiculo->precio_venta) }}">
        @error('precio_venta') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
    <div class="vehicle-field">
        <label for="estado"><span>Estado</span></label>
        <select id="estado" name="estado" required>
            @foreach ($estados as $estado)
                <option value="{{ $estado }}" @selected(old('estado', $vehiculo->estado ?: 'disponible') === $estado)>{{ ucfirst($estado) }}</option>
            @endforeach
        </select>
        @error('estado') <div class="crud-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="vehicle-field vehicle-field--full">
    <label for="observaciones"><span>Observaciones</span></label>
    <textarea id="observaciones" name="observaciones" rows="4">{{ old('observaciones', $vehiculo->observaciones) }}</textarea>
    @error('observaciones') <div class="crud-error">{{ $message }}</div> @enderror
</div>

<div class="crud-actions vehicle-actions">
    <button class="crud-button" type="submit">Guardar vehículo</button>
    <a class="crud-link" href="{{ route('vehiculos.index') }}">Cancelar</a>
</div>
