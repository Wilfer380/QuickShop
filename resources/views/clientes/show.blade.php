<x-app-layout>
    <x-clientes-styles />

    @php
        $segmento = $cliente->segmento ?? 'activo';
        $estado = $cliente->estado ?? 'activo';
        $segmentLabel = ['frecuente' => 'Frecuente', 'activo' => 'Activo', 'nuevo' => 'Nuevo', 'inactivo' => 'Inactivo'][$segmento] ?? ucfirst($segmento);
        $stateLabel = ['activo' => 'Activo', 'inactivo' => 'Inactivo'][$estado] ?? ucfirst($estado);
        $segmentClass = ['frecuente' => 'badge-purple', 'activo' => 'badge-blue', 'nuevo' => 'badge-green', 'inactivo' => 'badge-red'][$segmento] ?? 'badge-blue';
        $stateClass = $estado === 'inactivo' ? 'status-inactive' : 'status-active';
    @endphp

    <section class="clients-page">
        <div class="clients-header">
            <div>
                <h1 class="page-title">Detalle del cliente</h1>
                <p class="page-subtitle">Consulta la información completa sin editar</p>
            </div>
            <div class="clients-header" style="margin:0;gap:10px;">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn-secondary">Editar cliente</a>
                <a href="{{ route('clientes.index') }}" class="btn-secondary">Volver</a>
            </div>
        </div>

        <section class="client-modal" style="max-height:none;">
            <div class="client-modal__head">
                <div>
                    <h2>{{ $cliente->nombres }} {{ $cliente->apellidos }}</h2>
                    <p>{{ $cliente->tipo_documento }} {{ $cliente->documento }}</p>
                </div>
            </div>

            <div class="client-modal__body">
                <div class="client-stats-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-bottom:18px;">
                    <article class="client-stat-card"><div class="stat-label">Total compras</div><div class="stat-value">${{ number_format((float) ($cliente->compras_total ?? 0), 0, ',', '.') }}</div></article>
                    <article class="client-stat-card"><div class="stat-label">Última compra</div><div class="stat-value">{{ $cliente->ultima_compra ? \Illuminate\Support\Carbon::parse($cliente->ultima_compra)->format('d/m/Y') : '—' }}</div></article>
                    <article class="client-stat-card"><div class="stat-label">Vehículos</div><div class="stat-value">{{ $cliente->vehiculos_count ?? $cliente->vehiculos->count() }}</div></article>
                    <article class="client-stat-card"><div class="stat-label">Fecha registro</div><div class="stat-value">{{ $cliente->created_at?->format('d/m/Y') ?? '—' }}</div></article>
                </div>

                <div class="client-form-grid" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:16px;">
                    <div class="client-form-field"><span>Teléfono</span><input value="{{ $cliente->telefono ?? 'Sin teléfono' }}" readonly></div>
                    <div class="client-form-field"><span>Correo electrónico</span><input value="{{ $cliente->email ?? 'Sin email' }}" readonly></div>
                    <div class="client-form-field"><span>Ciudad</span><input value="{{ $cliente->ciudad ?? 'Sin ciudad' }}" readonly></div>
                    <div class="client-form-field"><span>Dirección</span><input value="{{ $cliente->direccion ?? 'Sin dirección' }}" readonly style="grid-column:1/-1;"></div>
                    <div class="client-form-field"><span>Segmento</span><input value="{{ $segmentLabel }}" readonly></div>
                    <div class="client-form-field"><span>Estado</span><input value="{{ $stateLabel }}" readonly></div>
                    <div class="client-form-field"><span>Observaciones</span><input value="Sin observaciones" readonly></div>
                </div>

                <div class="client-form-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:16px;">
                    <div class="client-modal" style="padding:16px;max-height:none;">
                        <div class="client-modal__head" style="padding:0 0 12px;border:0;">
                            <div>
                                <h2 style="font-size:18px;">Historial de compras</h2>
                                <p style="margin:0;">Últimos registros asociados.</p>
                            </div>
                        </div>
                        <div style="display:grid;gap:10px;">
                            @forelse ($cliente->ventas as $venta)
                                <div class="summary-item">
                                    <div class="summary-item__left">
                                        <div class="summary-item__icon">#</div>
                                        <div>
                                            <div class="summary-item__label">Venta #{{ $venta->id }}</div>
                                            <div class="summary-item__label">{{ $venta->fecha_venta?->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="summary-item__value">${{ number_format((float) $venta->total, 0, ',', '.') }}</div>
                                </div>
                            @empty
                                <div class="summary-item"><div class="summary-item__label">Sin compras registradas.</div></div>
                            @endforelse
                        </div>
                    </div>

                    <div class="client-modal" style="padding:16px;max-height:none;">
                        <div class="client-modal__head" style="padding:0 0 12px;border:0;">
                            <div>
                                <h2 style="font-size:18px;">Historial relacionado</h2>
                                <p style="margin:0;">Vehículos, pagos y parqueadero.</p>
                            </div>
                        </div>
                        <div style="display:grid;gap:10px;">
                            <div class="summary-item"><div class="summary-item__label">Vehículos comprados</div><div class="summary-item__value">{{ $cliente->vehiculos->count() }}</div></div>
                            <div class="summary-item"><div class="summary-item__label">Pagos asociados</div><div class="summary-item__value">{{ $cliente->pagos->count() }}</div></div>
                            <div class="summary-item"><div class="summary-item__label">Movimientos parqueadero</div><div class="summary-item__value">{{ $cliente->movimientosParqueadero->count() }}</div></div>
                        </div>
                    </div>
                </div>

                <div class="client-form-actions">
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn-primary">Editar cliente</a>
                    <a href="{{ route('clientes.index') }}" class="btn-secondary">Cerrar</a>
                </div>
            </div>
        </section>
    </section>
</x-app-layout>
