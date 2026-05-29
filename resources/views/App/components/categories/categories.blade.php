@php
    $catalogBaseParams = array_filter([
        'q' => $search,
        'sort' => $sort !== 'newest' ? $sort : null,
    ]);
@endphp

<aside class="categories-panel">
    <div class="categories-panel__header">
        <span class="label">Colecciones</span>
        <h3>Categorías</h3>
        <p>Cada tarjeta ahora muestra una representación visual de su categoría y podés hacer scroll para verlas todas.</p>
    </div>

    <div class="categories categories--cards">
        <a
            href="{{ route('buyers.index', $catalogBaseParams) }}#products-section"
            class="category-card {{ empty($selectedCategoryId) ? 'active' : '' }}"
        >
            <div class="category-card__media">
                <img src="{{ asset('resources/img_empresa/logo_quickShop.png') }}" alt="Todo el catálogo">
            </div>
            <div class="category-card__content">
                <span>Todo el catálogo</span>
                <small>Ver todos los productos</small>
            </div>
            <strong>{{ $categories->sum('products_count') }}</strong>
        </a>

        @forelse ($categories as $category)
            @php
                $sampleProduct = $category->products->first();
                $sampleImage = optional(optional($sampleProduct)->productImages->first())->image_path;
            @endphp

            <a
                href="{{ route('buyers.index', array_merge($catalogBaseParams, ['c' => $category->id])) }}#products-section"
                class="category-card {{ (string) $selectedCategoryId === (string) $category->id ? 'active' : '' }}"
            >
                <div class="category-card__media">
                    <img
                        src="{{ $sampleImage ? asset('storage/' . $sampleImage) : asset('resources/img_empresa/logo_quickShop.png') }}"
                        alt="{{ $category->name }}"
                    >
                </div>

                <div class="category-card__content">
                    <span>{{ $category->name }}</span>
                    <small>{{ \Illuminate\Support\Str::limit($category->description, 46) }}</small>
                </div>

                <strong>{{ $category->products_count }}</strong>
            </a>
        @empty
            <a href="#" class="category-card">
                <div class="category-card__content">
                    <span>Sin categorías</span>
                    <small>No hay información disponible.</small>
                </div>
                <strong>0</strong>
            </a>
        @endforelse
    </div>
</aside>
