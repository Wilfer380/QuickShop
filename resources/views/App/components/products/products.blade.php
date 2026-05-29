<section class="products-wrapper" id="products-section">
    <div class="products-toolbar">
        <div>
            <span class="products-toolbar__eyebrow">Selección disponible</span>
            <h3>Productos listos para una tienda más profesional</h3>
        </div>
        <p>{{ $products->count() }} resultados listos para explorar.</p>
    </div>

    <div class="products">
        @forelse ($products as $product)
            @if ($product->stock > 0)
                @php
                    $productImages = $product->productImages->values();
                    $primaryImage = optional($productImages->first())->image_path;
                    $productReference = 'QKS-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);
                    $stockStatus = $product->stock > 10 ? 'Disponible' : 'Últimas unidades';
                @endphp

                <article class="product">
                    <div class="img_start">
                        <span class="product-badge">{{ optional($product->category)->name ?? 'QuickShop' }}</span>
                        <span class="product-reference">{{ $productReference }}</span>

                        <img
                            class="product-preview"
                            src="{{ $primaryImage ? asset('storage/' . $primaryImage) : asset('resources/img_empresa/logo_quickShop.png') }}"
                            alt="{{ $product->name }}"
                        >

                        @if ($productImages->isNotEmpty())
                            <div class="product-thumbnails">
                                @foreach ($productImages as $image)
                                    <button
                                        type="button"
                                        class="product-thumbnail {{ $loop->first ? 'active' : '' }}"
                                        data-preview="{{ asset('storage/' . $image->image_path) }}"
                                        data-product-gallery
                                        aria-label="Ver imagen de {{ $product->name }}"
                                    >
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="informacion">
                        <div class="product-copy">
                            <p class="seller">Vendido por {{ optional($product->user)->name ?? 'QuickShop Store' }}</p>
                            <h3 class="nombre_producto">{{ $product->name }}</h3>
                            <p class="descripcion">{{ \Illuminate\Support\Str::limit($product->description, 130) }}</p>
                        </div>

                        <div class="product-meta-grid">
                            <div class="product-meta-card">
                                <span>Precio</span>
                                <strong>$ {{ number_format($product->price, 2) }}</strong>
                            </div>
                            <div class="product-meta-card">
                                <span>Disponibilidad</span>
                                <strong>{{ $product->stock }} unidades</strong>
                            </div>
                        </div>

                        <div class="product-footer">
                            <div class="product-status">
                                <span class="stock">{{ $stockStatus }}</span>
                                <small>Referencia {{ $productReference }}</small>
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
                                    <small>Precio, imagen y stock alineados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @endif
        @empty
            <div class="empty-state">
                <h4>No encontramos productos para esta vista.</h4>
                <p>Probá otra categoría, ajustá la búsqueda o sembrá más referencias para enriquecer el catálogo.</p>
            </div>
        @endforelse
    </div>
</section>
