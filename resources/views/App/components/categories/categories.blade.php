<aside class="categories-panel">
    <div class="categories-panel__header">
        <span class="label">Colecciones</span>
        <h3>Categorías</h3>
        <p>Filtrá rápido y compará productos con mejor contexto visual.</p>
    </div>

    <div class="categories">
        <a href="{{ route('buyers.index') }}" class="{{ empty($selectedCategoryId) ? 'active' : '' }}">
            Todo el catálogo
        </a>

        @forelse ($categories as $category)
            <a href="/?c={{ $category->id }}" class="{{ (string) $selectedCategoryId === (string) $category->id ? 'active' : '' }}">
                {{ $category->name }}
            </a>
        @empty
            <a href="">
                Sin categorías
            </a>
        @endforelse
    </div>
</aside>
