<div x-cloak x-show="newOpen" x-transition.opacity class="modal-backdrop" @keydown.escape.window="newOpen = false">
    <div class="modal-card" @click.outside="newOpen = false">
        <div class="modal-head">
            <div>
                <h3>Nuevo ingreso</h3>
                <p>Registra la entrada de un vehículo al parqueadero.</p>
            </div>
            <button type="button" class="modal-close" @click="newOpen = false">×</button>
        </div>

        <form method="POST" action="{{ route('parqueadero.store') }}">
            @csrf
            <div class="modal-body">
                <div class="modal-grid">
                    <label class="field">
                        <span>Placa / vehículo</span>
                        <select name="vehiculo_id" required>
                            <option value="">Selecciona un vehículo</option>
                            @foreach ($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}">{{ $vehiculo->placa ?? 'Sin placa' }} — {{ $vehiculo->tipo }} — {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Cliente</span>
                        <select name="cliente_id">
                            <option value="">Sin cliente específico</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ trim($cliente->nombres . ' ' . $cliente->apellidos) ?: $cliente->documento }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Zona / espacio</span>
                        <select name="cupo_id" required>
                            <option value="">Selecciona un espacio</option>
                            @foreach ($cupos->groupBy('zona') as $zona => $items)
                                <optgroup label="Zona {{ $zona }}">
                                    @foreach ($items as $cupo)
                                        <option value="{{ $cupo->id }}">{{ $cupo->codigo }} — {{ ucfirst($cupo->estado) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Tipo de vehículo / tarifa</span>
                        <select name="tarifa_id" required>
                            <option value="">Selecciona una tarifa</option>
                            @foreach ($tarifas as $tarifa)
                                <option value="{{ $tarifa->id }}">{{ $tarifa->nombre }} — {{ ucfirst($tarifa->tipo_vehiculo) }} — ${{ number_format((float) $tarifa->valor, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="field">
                        <span>Fecha y hora de ingreso</span>
                        <input type="datetime-local" name="entrada_at" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </label>

                    <label class="field" style="grid-column:1/-1">
                        <span>Observaciones</span>
                        <textarea name="observaciones" rows="4" placeholder="Notas del ingreso, estado del vehículo, etc."></textarea>
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="modal-secondary" @click="newOpen = false">Cancelar</button>
                <button type="submit" class="modal-primary">Guardar ingreso</button>
            </div>
        </form>
    </div>
</div>
