@csrf

<label>
    <span>Cliente propietario</span>
    <select name="cliente_id">
        <option value="">Sin cliente asignado</option>
        @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}" @selected((string) old('cliente_id', $vehiculo->cliente_id) === (string) $cliente->id)>{{ $cliente->nombres }} {{ $cliente->apellidos }} - {{ $cliente->documento }}</option>
        @endforeach
    </select>
    @error('cliente_id') <div class="crud-error">{{ $message }}</div> @enderror
</label>

<div class="crud-grid">
    <label><span>Placa</span><input type="text" name="placa" value="{{ old('placa', $vehiculo->placa) }}" required>@error('placa') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Tipo</span><select name="tipo" required>@foreach ($tipos as $tipo)<option value="{{ $tipo }}" @selected(old('tipo', $vehiculo->tipo) === $tipo)>{{ ucfirst($tipo) }}</option>@endforeach</select>@error('tipo') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>

<div class="crud-grid">
    <label><span>Marca</span><input type="text" name="marca" value="{{ old('marca', $vehiculo->marca) }}" required>@error('marca') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Modelo</span><input type="text" name="modelo" value="{{ old('modelo', $vehiculo->modelo) }}" required>@error('modelo') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>

<div class="crud-grid">
    <label><span>Anio</span><input type="number" name="anio" min="1900" max="{{ now()->addYear()->year }}" value="{{ old('anio', $vehiculo->anio) }}">@error('anio') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Color</span><input type="text" name="color" value="{{ old('color', $vehiculo->color) }}">@error('color') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>

<div class="crud-grid">
    <label><span>VIN</span><input type="text" name="vin" value="{{ old('vin', $vehiculo->vin) }}">@error('vin') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Estado</span><select name="estado" required>@foreach ($estados as $estado)<option value="{{ $estado }}" @selected(old('estado', $vehiculo->estado ?: 'disponible') === $estado)>{{ ucfirst($estado) }}</option>@endforeach</select>@error('estado') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>

<div class="crud-grid">
    <label><span>Kilometraje</span><input type="number" name="kilometraje" min="0" value="{{ old('kilometraje', $vehiculo->kilometraje) }}">@error('kilometraje') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Precio venta</span><input type="number" name="precio_venta" min="0" step="0.01" value="{{ old('precio_venta', $vehiculo->precio_venta) }}">@error('precio_venta') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>

<label><span>Observaciones</span><textarea name="observaciones" rows="4">{{ old('observaciones', $vehiculo->observaciones) }}</textarea>@error('observaciones') <div class="crud-error">{{ $message }}</div> @enderror</label>

<div class="crud-actions">
    <button class="crud-button" type="submit">Guardar vehiculo</button>
    <a class="crud-link" href="{{ route('vehiculos.index') }}">Cancelar</a>
</div>
