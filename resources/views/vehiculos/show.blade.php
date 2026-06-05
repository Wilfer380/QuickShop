<x-app-layout>
    <x-clientes-styles />

    @php
        $tipoLabels = [
            'carro' => 'Carro',
            'moto' => 'Moto',
            'camioneta' => 'Camioneta',
            'camion' => 'Camión',
            'otro' => 'Otro',
            'automovil' => 'Carro',
            'motocicleta' => 'Moto',
        ];
        $ubicacionLabels = [
            'inventario venta' => 'Inventario venta',
            'parqueadero' => 'Parqueadero',
            'taller' => 'Taller',
            'vendido' => 'Vendido',
            'reservado' => 'Reservado',
            '0' => 'Inventario venta',
            '1' => 'Parqueadero',
            '2' => 'Taller',
            '3' => 'Vendido',
            '4' => 'Reservado',
        ];
        $thumb = $vehiculo->imagen ? route('vehiculos.imagen', $vehiculo) : null;
    @endphp

    <section class="vehicles-page">
        <div class="vehicles-header">
            <div>
                <h1 class="page-title">Detalle vehículo</h1>
                <p class="page-subtitle">{{ $vehiculo->placa }} · {{ ucfirst($vehiculo->estado) }}</p>
            </div>
            <div class="detail-actions">
                <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn-new-vehicle btn-secondary">Editar</a>
                <a href="{{ route('vehiculos.index') }}" class="btn-new-vehicle">Volver</a>
            </div>
        </div>

        @if (session('status'))
            <div class="crud-alert">{{ session('status') }}</div>
        @endif

        <div class="vehicle-detail-grid">
            <article class="vehicle-detail-card">
                @if ($thumb)
                        <img src="{{ $thumb }}" alt="Foto vehículo" class="vehicle-hero-image">
                @else
                    <div class="vehicle-hero-placeholder">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 14h14l-1.2-4.2A2 2 0 0 0 16.9 8H7.1a2 2 0 0 0-1.9 1.8L5 14Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="8" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/><circle cx="16" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/></svg>
                    </div>
                @endif
                <div class="vehicle-detail-main">
                    <h2>{{ $vehiculo->marca }} {{ $vehiculo->modelo }} {{ $vehiculo->anio }}</h2>
                    <p>{{ $tipoLabels[$vehiculo->tipo] ?? ucfirst((string) $vehiculo->tipo) }} · {{ $vehiculo->color ?? 'Sin color' }}</p>
                </div>
            </article>

            <article class="vehicle-detail-card vehicle-specs">
                <div><span>Cliente</span><strong>{{ $vehiculo->cliente?->nombres ?? 'Sin cliente' }}</strong></div>
                <div><span>Tipo</span><strong>{{ $tipoLabels[$vehiculo->tipo] ?? ucfirst((string) $vehiculo->tipo) }}</strong></div>
                <div><span>Placa</span><strong>{{ $vehiculo->placa }}</strong></div>
                <div><span>Ubicación</span><strong>{{ $ubicacionLabels[(string) $vehiculo->ubicacion] ?? $vehiculo->ubicacion ?? 'Sin ubicación' }}</strong></div>
                <div><span>Kilometraje</span><strong>{{ $vehiculo->kilometraje ? number_format((float) $vehiculo->kilometraje, 0, ',', '.') . ' km' : 'Sin dato' }}</strong></div>
                <div><span>Precio compra</span><strong>{{ $vehiculo->precio_compra ? '$' . number_format((float) $vehiculo->precio_compra, 0, ',', '.') : 'Sin dato' }}</strong></div>
                <div><span>Precio venta</span><strong>{{ $vehiculo->precio_venta ? '$' . number_format((float) $vehiculo->precio_venta, 0, ',', '.') : 'Sin precio' }}</strong></div>
                <div><span>VIN</span><strong>{{ $vehiculo->vin ?? 'Sin VIN' }}</strong></div>
            </article>
        </div>
    </section>

    @push('styles')
        <style>
            .vehicles-page{padding:24px 34px 34px;color:#F8FAFC}
            .vehicles-header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:18px}
            .detail-actions{display:flex;gap:10px;flex-wrap:wrap}
            .btn-secondary{background:rgba(15,23,42,.78);border:1px solid rgba(148,163,184,.16);box-shadow:none}
            .vehicle-detail-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:16px}
            .vehicle-detail-card{padding:22px;border-radius:14px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}
            .vehicle-hero-image,.vehicle-hero-placeholder{width:100%;height:280px;border-radius:12px;object-fit:cover;background:rgba(15,23,42,.8);border:1px solid rgba(148,163,184,.16)}
            .vehicle-hero-placeholder{display:flex;align-items:center;justify-content:center;color:#3B82F6}
            .vehicle-hero-placeholder svg{width:72px;height:72px}
            .vehicle-detail-main{margin-top:18px}
            .vehicle-detail-main h2{margin:0;color:#F8FAFC;font-size:26px;font-weight:800}
            .vehicle-detail-main p{margin:8px 0 0;color:#94A3B8}
            .vehicle-specs{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
            .vehicle-specs div{padding:14px;border-radius:10px;background:rgba(15,23,42,.6);border:1px solid rgba(148,163,184,.12)}
            .vehicle-specs span{display:block;color:#94A3B8;font-size:12px;margin-bottom:6px}
            .vehicle-specs strong{color:#F8FAFC;font-size:14px}
            @media (max-width:1024px){.vehicle-detail-grid{grid-template-columns:1fr}.vehicles-page{padding:20px 16px 28px}}
        </style>
    @endpush
</x-app-layout>
