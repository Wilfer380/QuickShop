@php
    $cliente = $cliente ?? new \App\Models\Cliente();
    $tipoDocumentos = ['CC', 'CE', 'NIT', 'Pasaporte'];
    $segmentos = ['frecuente' => 'Frecuente', 'activo' => 'Activo', 'nuevo' => 'Nuevo', 'inactivo' => 'Inactivo'];
    $estados = ['activo' => 'Activo', 'inactivo' => 'Inactivo'];
@endphp

<div class="client-form-grid">
    <label class="client-form-field" style="grid-column:1 / -1;">
        <span>Foto del cliente</span>
        <input type="file" name="foto" accept="image/jpeg,image/png,image/webp">
        @error('foto') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Nombre completo</span>
        <input type="text" name="nombres" value="{{ old('nombres', $cliente->nombres) }}" required placeholder="María Gómez">
        @error('nombres') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Tipo de documento</span>
        <select name="tipo_documento" required>
            @foreach ($tipoDocumentos as $tipoDocumento)
                <option value="{{ $tipoDocumento }}" @selected(old('tipo_documento', $cliente->tipo_documento ?: 'CC') === $tipoDocumento)>{{ $tipoDocumento }}</option>
            @endforeach
        </select>
        @error('tipo_documento') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Número de documento</span>
        <input type="text" name="documento" value="{{ old('documento', $cliente->documento) }}" required placeholder="1.234.567.890">
        @error('documento') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Teléfono</span>
        <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" placeholder="300 123 4567">
        @error('telefono') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Correo electrónico</span>
        <input type="email" name="email" value="{{ old('email', $cliente->email) }}" placeholder="maria.gomez@email.com">
        @error('email') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Ciudad</span>
        <input type="text" name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}" placeholder="Medellín">
        @error('ciudad') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Segmento</span>
        <select name="segmento" required>
            <option value="">Selecciona un segmento</option>
            @foreach ($segmentos as $key => $label)
                <option value="{{ $key }}" @selected(old('segmento', $cliente->segmento ?: 'activo') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('segmento') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field">
        <span>Estado</span>
        <select name="estado" required>
            @foreach ($estados as $key => $label)
                <option value="{{ $key }}" @selected(old('estado', $cliente->estado ?: 'activo') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('estado') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>

    <label class="client-form-field" style="grid-column:1 / -1;">
        <span>Dirección</span>
        <textarea name="direccion" placeholder="Calle 45 #12-34">{{ old('direccion', $cliente->direccion) }}</textarea>
        @error('direccion') <div class="client-form-errors">{{ $message }}</div> @enderror
    </label>
</div>
