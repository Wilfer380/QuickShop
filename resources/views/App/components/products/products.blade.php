<section class="products-wrapper">
    <div class="products-toolbar">
        <div>
            <span class="products-toolbar__eyebrow">Selección disponible</span>
            <h3>Productos pensados para una tienda más profesional</h3>
        </div>
        <p>{{ $products->count() }} resultados listos para explorar.</p>
    </div>

    <div class="products">
        @forelse ($products as $product)
            @if ($product->stock > 0)
                @php
                    $imagePath = optional($product->productImages->first())->image_path;
                @endphp
                <article class="product">
                    <div class="img_start">
                        <span class="product-badge">{{ optional($product->category)->name ?? 'QuickShop' }}</span>
                        <img
                            id="preview-image"
                            name="image"
                            src="{{ $imagePath ? asset('storage/' . $imagePath) : asset('resources/img_empresa/logo_quickShop.png') }}"
                            alt="{{ $product->name }}"
                        >
                    </div>
                    <div class="informacion">
                        <div class="product-copy">
                            <p class="seller">Vendido por {{ optional($product->user)->name ?? 'QuickShop Store' }}</p>
                            <h3 class="nombre_producto">{{ $product->name }}</h3>
                            <p class="descripcion">{{ \Illuminate\Support\Str::limit($product->description, 120) }}</p>
                        </div>

                        <div class="product-meta">
                            <p class="precio">$ {{ number_format($product->price, 2) }}</p>
                            <span class="stock">Stock: {{ $product->stock }}</span>
                        </div>

                        <div class="opciones">
                            <a href="{{ route('car_shop', ['id' => $product->id]) }}" class="agregar_carrito">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
                                    <path d="M440-600v-120H320v-80h120v-120h80v120h120v80H520v120h-80ZM280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM40-800v-80h131l170 360h280l156-280h91L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68.5-39t-1.5-79l54-98-144-304H40Z" />
                                </svg>
                                <span>Agregar al carrito</span>
                            </a>
                            <div class="mas_informacion">
                                <span>Compra segura</span>
                                <small>Imagen y descripción relacionadas</small>
                            </div>
                        </div>
                    </div>
                </article>
            @endif
        @empty
            <div class="empty-state">
                <h4>No encontramos productos para esta vista.</h4>
                <p>Probá otra categoría o sembrá más productos para seguir enriqueciendo el catálogo.</p>
            </div>
        @endforelse
    </div>
</section>
