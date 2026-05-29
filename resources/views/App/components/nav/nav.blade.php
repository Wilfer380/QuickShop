<nav class="navegation">
    <div class="icon_title">
        <a href="{{ route('buyers.index') }}">
            <img src="{{ asset('resources/img_empresa/logo_quickShop.png') }}" alt="QuickShop logo">
            <div class="brand-copy">
                <h1>QuickShop</h1>
                <p>Marketplace experience</p>
            </div>
        </a>
    </div>

    <div class="links">
        @auth
            <div class="nav-chip shop" id="btn_option_shop">
                <span class="nav-chip__count">{{ $cart_count }}</span>
                <span>Carrito</span>
            </div>

            <div class="select_option_shop hidden" id="select_option_shop">
                <div class="menu-heading">
                    <strong>Tu carrito</strong>
                    <small>{{ $cart_count }} productos</small>
                </div>

                @forelse ($cartProducts as $cartProduct)
                    <div class="option">
                        <div class="img">
                            <img src="{{ asset('storage/' . $cartProduct->productImages[0]->image_path) }}" alt="{{ $cartProduct->name }}">
                        </div>
                        <div class="details">
                            <div class="name">{{ $cartProduct->name }}</div>
                            <div class="price">${{ number_format($cartProduct->price, 2) }}</div>
                        </div>
                        <div class="eliminar">
                            <a href="{{ route('eliminar_cart_shop', ['id' => $cartProduct->id]) }}">Eliminar</a>
                        </div>
                    </div>
                @empty
                    <div class="option option--empty">
                        <div class="name">No hay productos agregados todavía.</div>
                    </div>
                @endforelse

                @if ($cart_count > 0)
                    <div class="shop-footer">
                        <div class="total">Total ${{ number_format($precioTotal, 2) }}</div>
                        <a href="{{ route('cart.shop') }}" class="primary-link">Ir a comprar</a>
                    </div>
                @endif
            </div>

            <div class="nav-chip money">
                <a href="{{ route('money.index') }}">
                    <span>Saldo ${{ number_format(Auth::user()->money, 2) }}</span>
                </a>
            </div>

            <div class="nav-chip btn_option_users" id="btn_option_users">
                <span>{{ Auth::user()->name }}</span>
            </div>

            <div class="select_option_users hidden" id="select_option_users">
                <div class="menu-heading">
                    <strong>Tu cuenta</strong>
                    <small>Gestioná tu actividad</small>
                </div>

                <div class="option">
                    <a href="{{ route('profile.edit') }}">Perfil</a>
                </div>

                @if (Auth::user()->role == 'admin')
                    <div class="option">
                        <a href="#">Panel de administrador</a>
                    </div>
                @endif

                @if (Auth::user()->role == 'seller' || Auth::user()->role == 'admin')
                    <div class="option">
                        <a href="{{ route('seller.products.index') }}">Tus productos</a>
                    </div>
                @endif

                <div class="option">
                    <a href="{{ route('getPurchaseHistory') }}">Compras</a>
                </div>

                <div class="option">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <input type="submit" value="Cerrar sesión">
                    </form>
                </div>
            </div>
        @else
            <div class="guest-actions">
                <a href="{{ route('login') }}" class="secondary-link">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="primary-link">Crear cuenta</a>
            </div>
        @endauth
    </div>
</nav>
