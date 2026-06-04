@csrf

<div class="crud-grid">
    <label>
        <span>Tipo documento</span>
        <select name="tipo_documento" required>
            @foreach (['CC', 'CE', 'NIT', 'Pasaporte'] as $tipoDocumento)
                <option value="{{ $tipoDocumento }}" @selected(old('tipo_documento', $cliente->tipo_documento ?: 'CC') === $tipoDocumento)>{{ $tipoDocumento }}</option>
            @endforeach
        </select>
        @error('tipo_documento') <div class="crud-error">{{ $message }}</div> @enderror
    </label>

    <label>
        <span>Documento</span>
        <input type="text" name="documento" value="{{ old('documento', $cliente->documento) }}" required>
        @error('documento') <div class="crud-error">{{ $message }}</div> @enderror
    </label>
</div>

<div class="crud-grid">
    <label>
        <span>Nombres</span>
        <input type="text" name="nombres" value="{{ old('nombres', $cliente->nombres) }}" required>
        @error('nombres') <div class="crud-error">{{ $message }}</div> @enderror
    </label>

    <label>
        <span>Apellidos</span>
        <input type="text" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}">
        @error('apellidos') <div class="crud-error">{{ $message }}</div> @enderror
    </label>
</div>

<div class="crud-grid">
    <label>
        <span>Telefono</span>
        <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}">
        @error('telefono') <div class="crud-error">{{ $message }}</div> @enderror
    </label>

    <label>
        <span>Email</span>
        <input type="email" name="email" value="{{ old('email', $cliente->email) }}">
        @error('email') <div class="crud-error">{{ $message }}</div> @enderror
    </label>
</div>

<label>
    <span>Direccion</span>
    <input type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}">
    @error('direccion') <div class="crud-error">{{ $message }}</div> @enderror
</label>

<div class="crud-actions">
    <button class="crud-button" type="submit">Guardar cliente</button>
    <a class="crud-link" href="{{ route('clientes.index') }}">Cancelar</a>
</div>
