@csrf
<div class="crud-grid">
    <label><span>Codigo</span><input type="text" name="codigo" value="{{ old('codigo', $cupo->codigo) }}" required>@error('codigo') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Zona</span><input type="text" name="zona" value="{{ old('zona', $cupo->zona) }}">@error('zona') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>
<div class="crud-grid">
    <label><span>Tipo vehiculo</span><select name="tipo_vehiculo" required>@foreach ($tiposVehiculo as $tipoVehiculo)<option value="{{ $tipoVehiculo }}" @selected(old('tipo_vehiculo', $cupo->tipo_vehiculo) === $tipoVehiculo)>{{ ucfirst($tipoVehiculo) }}</option>@endforeach</select>@error('tipo_vehiculo') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Estado</span><select name="estado" required>@foreach ($estados as $estado)<option value="{{ $estado }}" @selected(old('estado', $cupo->estado ?: 'disponible') === $estado)>{{ ucfirst($estado) }}</option>@endforeach</select>@error('estado') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>
<label><span>Observaciones</span><textarea name="observaciones" rows="4">{{ old('observaciones', $cupo->observaciones) }}</textarea>@error('observaciones') <div class="crud-error">{{ $message }}</div> @enderror</label>
<div class="crud-actions"><button class="crud-button" type="submit">Guardar cupo</button><a class="crud-link" href="{{ route('cupos.index') }}">Cancelar</a></div>
