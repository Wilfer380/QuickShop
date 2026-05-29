@extends('App.layouts.app')

@section('title')
    QuickShop
@endsection

@section('links_css_js')
    <link rel="stylesheet" href="{{ asset('resources/App/modules/buyers/buyers.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/App/components/products/products.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/App/components/categories/categories.css') }}">
@endsection

@section('content')
    <div class="catalog-shell">
        <section class="catalog-hero">
            <div class="catalog-hero__copy">
                <span class="catalog-hero__eyebrow">QuickShop marketplace</span>
                <h2>Comprá con una experiencia más clara, visual y profesional.</h2>
                <p>
                    El catálogo ahora prioriza búsqueda, referencias visibles, stock real, precios claros e imágenes
                    relacionadas para que la tienda se sienta como una plataforma de venta de verdad.
                </p>

                <div class="catalog-hero__stats">
                    <article>
                        <strong>{{ $products->count() }}</strong>
                        <span>Productos visibles</span>
                    </article>
                    <article>
                        <strong>{{ $categories->count() }}</strong>
                        <span>Categorías</span>
                    </article>
                    <article>
                        <strong>{{ $cart_count }}</strong>
                        <span>En tu carrito</span>
                    </article>
                </div>
            </div>

            <div class="catalog-hero__panel">
                <span class="badge">QuickShop Premium UI</span>
                <h3>{{ $selectedCategoryId ? 'Colección filtrada' : 'Catálogo destacado' }}</h3>
                <p>
                    {{ $selectedCategoryId ? 'Estás viendo una selección enfocada para comparar mejor cada referencia.' : 'Mostramos una portada más sólida, lista para seguir creciendo como ecommerce real.' }}
                </p>

                <ul class="catalog-hero__highlights">
                    <li>Referencias visibles por producto</li>
                    <li>Precios y unidades disponibles</li>
                    <li>Filtros rápidos y búsqueda directa</li>
                </ul>
            </div>
        </section>

        <section class="catalog-tools">
            <form action="{{ route('buyers.index') }}" method="GET" class="catalog-filters">
                @if ($selectedCategoryId)
                    <input type="hidden" name="c" value="{{ $selectedCategoryId }}">
                @endif

                <div class="catalog-field">
                    <label for="q">Buscar producto</label>
                    <input
                        id="q"
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Auriculares, mochila, lámpara..."
                    >
                </div>

                <div class="catalog-field">
                    <label for="sort">Ordenar por</label>
                    <select id="sort" name="sort">
                        <option value="newest" @selected($sort === 'newest')>Más recientes</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Precio: menor a mayor</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Precio: mayor a menor</option>
                        <option value="stock_desc" @selected($sort === 'stock_desc')>Mayor disponibilidad</option>
                    </select>
                </div>

                <div class="catalog-actions">
                    <button type="submit">Aplicar</button>
                    @if ($selectedCategoryId || $search !== '' || $sort !== 'newest')
                        <a href="{{ route('buyers.index') }}">Limpiar</a>
                    @endif
                </div>
            </form>

            <div class="catalog-summary">
                <span>Vista activa</span>
                <strong>{{ $products->count() }} productos encontrados</strong>
                <p>{{ $selectedCategoryId ? 'Con categoría filtrada' : 'En todo el catálogo' }}</p>
            </div>
        </section>

        <div class="content">
            @include('App.components.categories.categories')
            @include('App.components.products.products')
        </div>
    </div>

    <script src="{{ asset('resources/App/modules/buyers/buyers.js') }}"></script>
@endsection
