<nav class="navegation">
    <div class="icon_title">
        <a href="{{ Auth::check() ? route('dashboard') : route('login') }}">
            <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="VehiPark logo">
            <div class="brand-copy">
                <h1>VehiPark</h1>
                <p>Fleet operations</p>
            </div>
        </a>
    </div>

    @php
        $navFamilies = \App\Models\Category::query()
            ->whereNull('parent_id')
            ->withCount('products')
            ->with(['children' => function ($query) {
                $query->withCount('products')->ordered();
            }])
            ->ordered()
            ->get();
        $selectedCategoryId = request()->query('c');
        $navQueryParams = array_filter(['q' => request()->query('q')]);
    @endphp

    <button type="button" class="nav-chip nav-chip--categories" id="btn_categories_menu" aria-expanded="false" aria-controls="select_option_categories">
        <span>Segmentos</span>
    </button>

    <div class="select_option_categories hidden" id="select_option_categories">
        <div class="menu-heading">
            <strong>Segmentos de flota</strong>
            <small>Tipos de vehiculo, servicios y zonas internas</small>
        </div>

        <a class="option option--all {{ empty($selectedCategoryId) ? 'active' : '' }}" href="{{ route('dashboard', $navQueryParams) }}">
            <div class="option__thumb option__thumb--all">
                <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="Todo VehiPark">
            </div>
            <div class="details">
                <div class="name">Toda la flota</div>
                <div class="price">Ver inventario completo</div>
            </div>
        </a>

        @foreach ($navFamilies as $family)
            <a class="option option--family {{ (string) $selectedCategoryId === (string) $family->id ? 'active' : '' }}" href="{{ route('dashboard', array_merge($navQueryParams, ['c' => $family->id])) }}">
                <div class="option__thumb">
                    <img src="{{ \App\Support\VehicleVisuals::imageUrl($family) }}" alt="{{ $family->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ \App\Support\VehicleVisuals::fallbackDataUri($family) }}'">
                </div>
                <div class="details">
                    <div class="name">{{ $family->name }}</div>
                    <div class="price">{{ $family->products_count + $family->children->sum('products_count') }} unidades · {{ $family->children->count() }} filtros</div>
                </div>
            </a>

            @foreach ($family->children as $child)
                <a class="option option--child {{ (string) $selectedCategoryId === (string) $child->id ? 'active' : '' }}" href="{{ route('dashboard', array_merge($navQueryParams, ['c' => $child->id])) }}">
                    <div class="option__thumb option__thumb--child">
                        <img src="{{ \App\Support\VehicleVisuals::imageUrl($child) }}" alt="{{ $child->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ \App\Support\VehicleVisuals::fallbackDataUri($child) }}'">
                    </div>
                    <div class="details">
                        <div class="name">{{ $child->name }}</div>
                        <div class="price">{{ $family->name }} · {{ $child->products_count }} unidades</div>
                    </div>
                </a>
            @endforeach
        @endforeach
    </div>

    <div class="links">
        @auth
            <a href="{{ route('dashboard') }}" class="nav-chip nav-chip--panel">
                <span>Panel</span>
            </a>

            <a href="{{ route('vehicle-publications.index') }}" class="nav-chip">
                <span>Inventario</span>
            </a>

            <a href="{{ route('vehicle-publications.create') }}" class="nav-chip">
                <span>Agregar vehiculo</span>
            </a>

            <button type="button" class="nav-chip btn_option_users" id="btn_option_users" aria-haspopup="menu" aria-expanded="false">
                <span>{{ Auth::user()->name }}</span>
            </button>

            <div class="select_option_users hidden" id="select_option_users">
                <div class="menu-heading">
                    <strong>{{ Auth::user()->name }}</strong>
                    <small>{{ Auth::user()->email }} · {{ ucfirst(Auth::user()->role ?? 'usuario') }}</small>
                </div>

                <div class="option"><a href="{{ route('profile.edit') }}">Perfil empleado</a></div>
                <div class="option"><a href="{{ route('dashboard') }}">Actividad operativa</a></div>
                <div class="option">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <input type="submit" value="Cerrar sesion">
                    </form>
                </div>
            </div>
        @else
            <div class="guest-actions">
                <a href="{{ route('login') }}" class="secondary-link">Acceso interno</a>
                <a href="{{ route('register') }}" class="primary-link">Alta empleado</a>
            </div>
        @endauth
    </div>
</nav>
