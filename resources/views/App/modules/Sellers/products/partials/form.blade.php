@php
    $selectedCategory = old('category_id', $product->category_id ?? $categories->first()?->id);
    $currentImage = isset($image) && $image->isNotEmpty()
        ? asset('storage/' . $image->first()->image_path)
        : null;
@endphp

@if ($errors->any())
    <div class="form-alert">
        <strong>Revisá los datos del producto.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="seller-form">
    @csrf
    @method($method)

    <div class="seller-form__content">
        <section class="seller-form__section">
            <div class="seller-form__heading">
                <span>Información principal</span>
                <h3>Definí la referencia dentro de su categoría</h3>
                <p>Completá nombre, descripción, precio y unidades disponibles para que el catálogo se vea consistente.</p>
            </div>

            <div class="seller-field">
                <label for="category_id">Categoría</label>
                <select name="category_id" id="category_id" required data-category-select>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            data-description="{{ $category->description }}"
                            {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <small id="category-description" class="seller-help">
                    {{ optional($categories->firstWhere('id', $selectedCategory))->description }}
                </small>
            </div>

            <div class="seller-grid">
                <div class="seller-field">
                    <label for="name">Nombre del producto</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <div class="seller-field">
                    <label for="stock">Unidades disponibles</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock ?? '') }}" required>
                </div>
            </div>

            <div class="seller-field">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" rows="6" required>{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="seller-grid">
                <div class="seller-field">
                    <label for="price">Precio</label>
                    <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" required>
                </div>

                <div class="seller-field seller-field--hint">
                    <span>Tip de catálogo</span>
                    <p>Usá un nombre corto, una descripción clara y una imagen alineada con la categoría para que la card se vea profesional.</p>
                </div>
            </div>
        </section>

        <aside class="seller-form__section seller-form__section--media">
            <div class="seller-form__heading">
                <span>Imagen del producto</span>
                <h3>Subí una vista principal</h3>
                <p>Esta imagen será la portada de la referencia en el catálogo.</p>
            </div>

            <label for="image" class="seller-upload">
                <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp">
                <span>Seleccionar imagen</span>
                <small>{{ $method === 'POST' ? 'Campo obligatorio para productos nuevos.' : 'Opcional: solo si querés reemplazar la imagen actual.' }}</small>
            </label>

            <div class="seller-preview">
                <img
                    id="preview-image"
                    src="{{ old('image') ? '#' : ($currentImage ?? asset('resources/img_empresa/logo_quickShop.png')) }}"
                    alt="Vista previa del producto"
                >
            </div>
        </aside>
    </div>

    <div class="seller-form__actions">
        <a href="{{ route('seller.products.index') }}">Cancelar</a>
        <button type="submit">{{ $submitLabel }}</button>
    </div>
</form>

<script>
    const categorySelect = document.querySelector('[data-category-select]');
    const categoryDescription = document.getElementById('category-description');
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('preview-image');

    categorySelect?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        categoryDescription.textContent = selectedOption.dataset.description || '';
    });

    imageInput?.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (!file || !previewImage) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            previewImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
</script>
