@csrf

@php
    $isEdit = ($method ?? 'POST') !== 'POST';
    $formatMoney = static function ($value) {
        if ($value === null || $value === '') {
            return '';
        }

        $raw = trim((string) $value);
        if (!preg_match('/^(?:0|[1-9]\d*|[1-9]\d{0,2}(?:\.\d{3})*)$/', $raw)) {
            return $raw;
        }

        return number_format((int) str_replace('.', '', $raw), 0, ',', '.');
    };

    $moneyBase = old('tarifa_hora', $tarifa->tarifa_hora ?? $tarifa->valor ?? 0);
    $stateValue = old('estado', $tarifa->estado ?? ((bool) ($tarifa->activa ?? true) ? 'activa' : 'inactiva'));
    $observacionesValue = old('observaciones', $tarifa->observaciones ?? $tarifa->descripcion ?? '');
@endphp

@push('styles')
    <style>
        .tarifa-form-page{padding:24px 34px 34px;color:#f8fafc}.tarifa-form-shell{border-radius:14px;background:linear-gradient(180deg,rgba(17,24,39,.96),rgba(15,23,42,.98));border:1px solid rgba(148,163,184,.16);box-shadow:0 18px 38px rgba(0,0,0,.22);padding:22px}.tarifa-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.tarifa-form-field{display:flex;flex-direction:column;gap:8px}.tarifa-form-field label,.tarifa-form-field>span{font-size:13px;font-weight:700;color:#e2e8f0}.tarifa-form-field input,.tarifa-form-field select,.tarifa-form-field textarea{background:rgba(15,23,42,.9);border:1px solid rgba(148,163,184,.16);border-radius:10px;color:#f8fafc;padding:12px 14px;font-size:14px;outline:none}.tarifa-form-field textarea{min-height:110px;resize:vertical}.tarifa-form-field input:focus,.tarifa-form-field select:focus,.tarifa-form-field textarea:focus{border-color:rgba(59,130,246,.55);box-shadow:0 0 0 3px rgba(37,99,235,.15)}.tarifa-form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:18px}.tarifa-form-actions .btn-primary-tarifa,.tarifa-form-actions .btn-secondary-tarifa{justify-content:center}.tarifa-form-full{grid-column:1/-1}.tarifa-form-note{font-size:12px;color:#94a3b8}.tarifa-form-mini{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.tarifa-form-mini .tarifa-form-field{min-width:0}.tarifa-form-header{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:18px}.tarifa-form-title{font-size:28px;font-weight:800}.tarifa-form-subtitle{font-size:14px;color:#94a3b8}.tarifa-form-badges{display:flex;gap:10px;flex-wrap:wrap}.tarifa-chip{display:inline-flex;align-items:center;gap:8px;height:32px;padding:0 12px;border-radius:999px;background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.18);color:#dbeafe;font-size:12px;font-weight:700}.tarifa-inline-legend{display:flex;flex-wrap:wrap;gap:8px}.tarifa-inline-legend span{font-size:12px;color:#94a3b8}
        @media (max-width: 960px){.tarifa-form-grid,.tarifa-form-mini{grid-template-columns:1fr}.tarifa-form-header{flex-direction:column}.tarifa-form-actions{flex-direction:column}.tarifa-form-actions .btn-primary-tarifa,.tarifa-form-actions .btn-secondary-tarifa{width:100%}}
    </style>
@endpush

<div class="tarifa-form-page">
    <section class="tarifa-form-shell">
        <div class="tarifa-form-header">
            <div>
                <div class="tarifa-form-title">{{ $isEdit ? 'Editar tarifa' : 'Nueva tarifa' }}</div>
                <div class="tarifa-form-subtitle">{{ $isEdit ? 'Actualiza la configuración sin perder el rastro comercial.' : 'Crea una tarifa lista para operar en parqueadero.' }}</div>
            </div>
            <div class="tarifa-form-badges">
                <span class="tarifa-chip">{{ $tarifa->tipo_vehiculo ? ucfirst($tarifa->tipo_vehiculo) : 'Tipo de vehículo' }}</span>
                <span class="tarifa-chip">{{ $stateValue === 'activa' ? 'Activa' : 'Inactiva' }}</span>
            </div>
        </div>

        <input type="hidden" name="valor" id="tarifa_valor" value="{{ $formatMoney($moneyBase) }}">
        <input type="hidden" name="tipo_cobro" value="hora">
        <input type="hidden" name="activa" id="tarifa_activa" value="{{ $stateValue === 'activa' ? 1 : 0 }}">
        <input type="hidden" name="descripcion" id="tarifa_descripcion" value="{{ $observacionesValue }}">

        <div class="tarifa-form-grid">
            <div class="tarifa-form-field">
                <label for="tipo_vehiculo">Tipo de vehículo</label>
                <select id="tipo_vehiculo" name="tipo_vehiculo" required>
                    @foreach ($tiposVehiculo as $tipoVehiculo)
                        <option value="{{ $tipoVehiculo }}" @selected(old('tipo_vehiculo', $tarifa->tipo_vehiculo) === $tipoVehiculo)>{{ ucfirst($tipoVehiculo) }}</option>
                    @endforeach
                </select>
                @error('tipo_vehiculo') <div class="crud-error">{{ $message }}</div> @enderror
            </div>

            <div class="tarifa-form-field">
                <label for="icono">Ícono</label>
                <select id="icono" name="icono">
                    @foreach (['carro' => 'Carro', 'moto' => 'Moto', 'camioneta' => 'Camioneta', 'camion' => 'Camión', 'bicicleta' => 'Bicicleta', 'otro' => 'Otro'] as $icon => $label)
                        <option value="{{ $icon }}" @selected(old('icono', $tarifa->icono) === $icon)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tarifa-form-field">
                <label for="zona">Zona</label>
                <select id="zona" name="zona">
                    <option value="">Sin zona</option>
                    @foreach ($zonas as $zona)
                        <option value="{{ $zona }}" @selected(old('zona', $tarifa->zona) === $zona)>Zona {{ $zona }}</option>
                    @endforeach
                </select>
            </div>

            <div class="tarifa-form-field">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" required>
                    @foreach ($estados as $value => $label)
                        <option value="{{ $value }}" @selected($stateValue === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('estado') <div class="crud-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="tarifa-form-mini" style="margin-top:16px">
            <div class="tarifa-form-field">
                <label for="tarifa_minuto">Tarifa por minuto</label>
                <input type="text" id="tarifa_minuto" name="tarifa_minuto" inputmode="numeric" autocomplete="off" placeholder="80" data-money-input="true" value="{{ $formatMoney(old('tarifa_minuto', $tarifa->tarifa_minuto ?? round((float) ($tarifa->tarifa_hora ?? $tarifa->valor ?? 0) / 60))) }}">
            </div>

            <div class="tarifa-form-field">
                <label for="tarifa_hora">Tarifa por hora</label>
                <input type="text" id="tarifa_hora" name="tarifa_hora" inputmode="numeric" autocomplete="off" placeholder="4.000" data-money-input="true" value="{{ $formatMoney($moneyBase) }}" required>
                @error('valor') <div class="crud-error">{{ $message }}</div> @enderror
            </div>

            <div class="tarifa-form-field">
                <label for="tarifa_dia">Tarifa día completo</label>
                <input type="text" id="tarifa_dia" name="tarifa_dia" inputmode="numeric" autocomplete="off" placeholder="25.000" data-money-input="true" value="{{ $formatMoney(old('tarifa_dia', $tarifa->tarifa_dia ?? ((float) ($tarifa->tarifa_hora ?? $tarifa->valor ?? 0) * 6))) }}">
            </div>

            <div class="tarifa-form-field">
                <label for="tarifa_noche">Tarifa noche (12AM - 6AM)</label>
                <input type="text" id="tarifa_noche" name="tarifa_noche" inputmode="numeric" autocomplete="off" placeholder="12.000" data-money-input="true" value="{{ $formatMoney(old('tarifa_noche', $tarifa->tarifa_noche ?? ((float) ($tarifa->tarifa_hora ?? $tarifa->valor ?? 0) * 3))) }}">
            </div>
        </div>

        <div class="tarifa-form-grid" style="margin-top:16px">
            <div class="tarifa-form-field tarifa-form-full">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones">{{ $observacionesValue }}</textarea>
                @error('observaciones') <div class="crud-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="tarifa-form-actions">
            <a class="btn-secondary-tarifa" href="{{ route('tarifas.index') }}">Cancelar</a>
            <button class="btn-primary-tarifa" type="submit">{{ $isEdit ? 'Actualizar tarifa' : 'Guardar tarifa' }}</button>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const moneyInput = document.getElementById('tarifa_hora');
            const hiddenValor = document.getElementById('tarifa_valor');
            const descripcion = document.getElementById('tarifa_descripcion');
            const observaciones = document.getElementById('observaciones');
            const estado = document.getElementById('estado');
            const activa = document.getElementById('tarifa_activa');

            const syncBase = () => {
                if (!moneyInput || !hiddenValor) return;
                hiddenValor.value = (moneyInput.value || '').replace(/[^\d]/g, '');
            };

            moneyInput?.addEventListener('input', syncBase);
            moneyInput?.addEventListener('blur', syncBase);
            syncBase();

            observaciones?.addEventListener('input', () => {
                if (descripcion) descripcion.value = observaciones.value;
            });

            estado?.addEventListener('change', () => {
                if (activa) activa.value = estado.value === 'activa' ? '1' : '0';
            });
            estado?.dispatchEvent(new Event('change'));
        });
    </script>
@endpush
