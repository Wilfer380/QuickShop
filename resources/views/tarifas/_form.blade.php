@csrf
<div class="crud-grid">
    <label><span>Nombre</span><input type="text" name="nombre" value="{{ old('nombre', $tarifa->nombre) }}" required>@error('nombre') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Valor</span><input type="number" name="valor" min="0.01" step="0.01" value="{{ old('valor', $tarifa->valor) }}" required>@error('valor') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>
<div class="crud-grid">
    <label><span>Tipo vehiculo</span><select name="tipo_vehiculo" required>@foreach ($tiposVehiculo as $tipoVehiculo)<option value="{{ $tipoVehiculo }}" @selected(old('tipo_vehiculo', $tarifa->tipo_vehiculo) === $tipoVehiculo)>{{ ucfirst($tipoVehiculo) }}</option>@endforeach</select>@error('tipo_vehiculo') <div class="crud-error">{{ $message }}</div> @enderror</label>
    <label><span>Tipo cobro</span><select name="tipo_cobro" required>@foreach ($tiposCobro as $tipoCobro)<option value="{{ $tipoCobro }}" @selected(old('tipo_cobro', $tarifa->tipo_cobro ?: 'hora') === $tipoCobro)>{{ ucfirst($tipoCobro) }}</option>@endforeach</select>@error('tipo_cobro') <div class="crud-error">{{ $message }}</div> @enderror</label>
</div>
<label><span>Estado</span><select name="activa" required><option value="1" @selected((string) old('activa', $tarifa->activa ? '1' : '0') === '1')>Activa</option><option value="0" @selected((string) old('activa', $tarifa->activa ? '1' : '0') === '0')>Inactiva</option></select>@error('activa') <div class="crud-error">{{ $message }}</div> @enderror</label>
<label><span>Descripcion</span><textarea name="descripcion" rows="4">{{ old('descripcion', $tarifa->descripcion) }}</textarea>@error('descripcion') <div class="crud-error">{{ $message }}</div> @enderror</label>
<div class="crud-actions"><button class="crud-button" type="submit">Guardar tarifa</button><a class="crud-link" href="{{ route('tarifas.index') }}">Cancelar</a></div>
