@php
    $moduleLinks = [
        ['route' => 'clientes.index', 'label' => 'Clientes', 'active' => 'clientes.*'],
        ['route' => 'vehiculos.index', 'label' => 'Vehiculos', 'active' => 'vehiculos.*'],
        ['route' => 'ventas.index', 'label' => 'Ventas', 'active' => 'ventas.*'],
        ['route' => 'parqueadero.index', 'label' => 'Parqueadero', 'active' => 'parqueadero.*'],
        ['route' => 'cupos.index', 'label' => 'Cupos', 'active' => 'cupos.*'],
        ['route' => 'tarifas.index', 'label' => 'Tarifas', 'active' => 'tarifas.*'],
        ['route' => 'pagos.index', 'label' => 'Pagos', 'active' => 'pagos.*'],
        ['route' => 'reportes.index', 'label' => 'Reportes', 'active' => 'reportes.*'],
    ];
@endphp

<nav x-data="{ open: false }" class="text-slate-100">
    <div class="fixed inset-x-0 top-0 z-40 border-b border-white/10 bg-slate-950/95 px-4 py-3 backdrop-blur lg:hidden">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline">
                <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="VehiPark" class="h-11 w-11 rounded-2xl bg-white p-1.5">
                <div class="leading-tight">
                    <strong class="block text-base font-black text-white">VehiPark</strong>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-sky-300">Admin OS</span>
                </div>
            </a>
            <button @click="open = ! open" class="rounded-xl border border-white/10 bg-slate-900 p-2 text-slate-100">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <aside :class="{'translate-x-0': open, '-translate-x-full': ! open}" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-white/10 bg-slate-950/95 shadow-2xl shadow-black/50 backdrop-blur-xl transition-transform duration-200 lg:translate-x-0">
        <div class="flex items-center gap-3 border-b border-white/10 px-6 py-6">
            <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="VehiPark" class="h-14 w-14 rounded-2xl bg-white p-2">
            <div class="leading-tight">
                <strong class="block text-xl font-black text-white">VehiPark</strong>
                <span class="text-xs font-bold uppercase tracking-[0.22em] text-sky-300">Admin OS</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-4 py-5">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link--active' : '' }}">Dashboard</a>
            @foreach ($moduleLinks as $link)
                <a href="{{ route($link['route']) }}" class="sidebar-link {{ request()->routeIs($link['active']) ? 'sidebar-link--active' : '' }}">{{ $link['label'] }}</a>
            @endforeach
        </div>

        <div class="border-t border-white/10 p-4">
            <div class="rounded-2xl border border-white/10 bg-slate-900/80 p-4">
                <strong class="block text-sm font-black text-white">{{ Auth::user()->name }}</strong>
                <span class="mt-1 block break-all text-xs font-semibold text-slate-300">{{ Auth::user()->email }}</span>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('profile.edit') }}" class="sidebar-action">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-action">Salir</button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div x-show="open" @click="open = false" class="fixed inset-0 z-40 bg-black/60 lg:hidden" x-cloak></div>
</nav>

<style>
    .sidebar-link{display:flex;align-items:center;margin-bottom:8px;border:1px solid transparent;border-radius:18px;padding:12px 14px;color:#f8fbff;font-weight:850;text-decoration:none;transition:.18s ease}.sidebar-link:hover{border-color:rgba(56,189,248,.35);background:rgba(30,41,59,.75);color:#fff}.sidebar-link--active{border-color:rgba(56,189,248,.5);background:linear-gradient(135deg,rgba(56,189,248,.22),rgba(124,58,237,.18));color:#fff;box-shadow:0 8px 24px rgba(56,189,248,.14)}.sidebar-action{display:inline-flex;align-items:center;border:1px solid rgba(255,255,255,.12);border-radius:999px;background:#020617;padding:8px 12px;color:#f8fbff;font-size:12px;font-weight:900;text-decoration:none}.sidebar-action:hover{border-color:rgba(56,189,248,.45);color:#fff}@media (max-width:1023px){main{padding-top:72px}}
</style>
