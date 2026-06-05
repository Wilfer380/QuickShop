@php
    $items = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'dashboard'],
        ['label' => 'Clientes', 'route' => 'clientes.index', 'active' => 'clientes.*', 'icon' => 'users'],
        ['label' => 'Vehículos', 'route' => 'vehiculos.index', 'active' => 'vehiculos.*', 'icon' => 'car'],
        ['label' => 'Ventas', 'route' => 'ventas.index', 'active' => 'ventas.*', 'icon' => 'tag'],
        ['label' => 'Parqueadero', 'route' => 'parqueadero.index', 'active' => 'parqueadero.*', 'icon' => 'parking'],
        ['label' => 'Tarifas', 'route' => 'tarifas.index', 'active' => 'tarifas.*', 'icon' => 'badge'],
        ['label' => 'Pagos', 'route' => 'pagos.index', 'active' => 'pagos.*', 'icon' => 'credit'],
        ['label' => 'Reportes', 'route' => 'reportes.index', 'active' => 'reportes.*', 'icon' => 'chart'],
        ['label' => 'Configuración', 'route' => 'configuracion.index', 'active' => 'configuracion.*', 'icon' => 'gear'],
    ];
@endphp

<aside
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="sidebar"
    x-cloak
>
    <div class="sidebar-inner">
        <div class="sidebar-brand">
            <div class="sidebar-brand__mark">
                <span class="sidebar-brand__vp">VP</span>
            </div>
            <div class="sidebar-brand__text">
                <span class="logo-text">Vehi<span>Park</span></span>
            </div>
            <button type="button" class="sidebar-collapse" @click="sidebarOpen = false" aria-label="Contraer menú">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M15 6 9 12l6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        <nav class="sidebar-nav" aria-label="Navegación principal">
            @foreach ($items as $item)
                <a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['active']) ? 'active' : '' }}">
                    <span class="sidebar-link__icon">
                        @switch($item['icon'])
                            @case('dashboard')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h7v7H4zM13 4h7v4h-7zM13 10h7v10h-7zM4 13h7v7H4z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                                @break
                            @case('users')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="9.5" cy="7.5" r="3.5" stroke="currentColor" stroke-width="1.7"/><path d="M17 11c1.8 0 3.2 1.4 3.2 3.2V18" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                                @break
                            @case('car')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 14h14l-1.2-4.2A2 2 0 0 0 16.9 8H7.1a2 2 0 0 0-1.9 1.8L5 14Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M6 14v2m12-2v2M7 16h10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="8" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/><circle cx="16" cy="17" r="1.4" stroke="currentColor" stroke-width="1.7"/></svg>
                                @break
                            @case('tag')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 13.5 12.5 21 4 12.5V4h8.5L20 11.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="9" cy="9" r="1.2" fill="currentColor"/></svg>
                                @break
                            @case('parking')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 20A8 8 0 1 0 4 12a8 8 0 0 0 8 8Z" stroke="currentColor" stroke-width="1.7"/><path d="M9.5 15V9h3.3a2.2 2.2 0 0 1 0 4.4H9.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @break
                            @case('badge')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l7 4v5c0 5-3.5 8.5-7 9.8C8.5 20.5 5 17 5 12V7l7-4Z" stroke="currentColor" stroke-width="1.7"/><path d="M9 12h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                                @break
                            @case('credit')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.5" y="5" width="17" height="14" rx="2.5" stroke="currentColor" stroke-width="1.7"/><path d="M3.5 9h17" stroke="currentColor" stroke-width="1.7"/><path d="M7 15h4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                                @break
                            @case('chart')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 19V5M4 19h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M8 15v-4M12 15V8M16 15v-7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                                @break
                            @case('gear')
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" stroke="currentColor" stroke-width="1.7"/><path d="M4.5 12a7.5 7.5 0 0 1 .1-1l1.8-.5.8-1.7-1-1.6a7.7 7.7 0 0 1 1.4-1.4l1.6 1 1.7-.8.5-1.8a7.5 7.5 0 0 1 2 0l.5 1.8 1.7.8 1.6-1a7.7 7.7 0 0 1 1.4 1.4l-1 1.6.8 1.7 1.8.5a7.5 7.5 0 0 1 0 2l-1.8.5-.8 1.7 1 1.6a7.7 7.7 0 0 1-1.4 1.4l-1.6-1-1.7.8-.5 1.8a7.5 7.5 0 0 1-2 0l-.5-1.8-1.7-.8-1.6 1a7.7 7.7 0 0 1-1.4-1.4l1-1.6-.8-1.7-1.8-.5a7.5 7.5 0 0 1-.1-1Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>
                                @break
                        @endswitch
                    </span>
                    <span class="sidebar-link__label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="sidebar-footer">
            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80" alt="Carro decorativo" class="sidebar-footer__car">
            <div class="sidebar-footer__meta">
                <strong>{{ auth()->user()->name ?? 'VehiPark' }}</strong>
                <span>{{ auth()->user()->email ?? '© 2024 VehiPark S.A.S.' }}</span>
                <p><i class="sidebar-status-dot"></i> {{ auth()->user()?->role ? ucfirst(auth()->user()->role) : 'Sistema en línea' }}</p>
            </div>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen && window.innerWidth < 1024" x-cloak class="sidebar-overlay lg:hidden" @click="sidebarOpen = false"></div>
