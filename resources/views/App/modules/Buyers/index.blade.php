@extends("App.layouts.app")

@section("title")
    QuickShop
@endsection

@section("links_css_js")
    <link rel="stylesheet" href="{{ asset('resources/App/modules/buyers/buyers.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/App/components/products/products.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/App/components/categories/categories.css') }}">
@endsection

@section("content")
    <div class="catalog-shell">
        <section class="catalog-hero">
            <div class="catalog-hero__copy">
                <span class="catalog-hero__eyebrow">Shopping experience renovada</span>
                <h2>Descubrí productos con mejor imagen, contexto y una presentación mucho más profesional.</h2>
                <p>
                    Ahora cada card muestra una imagen relacionada con su producto, una descripción clara y una interfaz más cercana
                    a una app real de ecommerce.
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
                <h3>{{ $selectedCategoryId ? 'Vista filtrada por categoría' : 'Catálogo destacado' }}</h3>
                <p>
                    {{ $selectedCategoryId ? 'Estás viendo un conjunto filtrado para comparar productos con más foco visual.' : 'Mostramos una grilla más cuidada, lista para seguir creciendo como tienda real.' }}
                </p>
            </div>
        </section>

        <div class="content">
            @include("App.components.categories.categories")
            @include("App.components.products.products")
        </div>
    </div>
@endsection
