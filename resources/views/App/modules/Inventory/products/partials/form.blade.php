@php
    $selectedCategory = old('category_id', $product->category_id ?? $categories->first()?->id);
    $currentImage = isset($image) && $image->isNotEmpty()
        ? asset('storage/' . $image->first()->image_path)
        : null;
@endphp

@if ($errors->any())
    <div class="form-alert">
        <strong>Revisa los datos del vehiculo.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="inventory-form">
    @csrf
    @method($method)

    <div class="inventory-form__content">
        <section class="inventory-form__section">
            <div class="inventory-form__heading">
                <span>Informacion principal</span>
                <h3>Define la referencia dentro de su segmento</h3>
                <p>Completa nombre, descripcion, valor operativo y disponibilidad para mantener consistente el inventario.</p>
            </div>

            <div class="inventory-field">
                <label for="category_id">Segmento / tipo</label>
                <select name="category_id" id="category_id" required data-category-select>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            data-description="{{ $category->description }}"
                            {{ (string) $selectedCategory === (string) $category->id ? 'selected' : '' }}
                        >
                            {{ $category->parent ? $category->parent->name . ' / ' : '' }}{{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <small id="category-description" class="inventory-help">
                    {{ optional($categories->firstWhere('id', $selectedCategory))->description }}
                </small>
            </div>

            <div class="inventory-grid">
                <div class="inventory-field">
                    <label for="name">Nombre del vehiculo</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <div class="inventory-field">
                    <label for="stock">Disponibilidad</label>
                    <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock ?? '') }}" required>
                </div>
            </div>

            <div class="inventory-field">
                <label for="description">Descripcion operativa</label>
                <textarea name="description" id="description" rows="6">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="inventory-grid">
                <div class="inventory-field">
                    <label for="price">Valor operativo</label>
                    <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price', $product->price ?? '') }}" required>
                </div>

                <div class="inventory-field inventory-field--hint">
                    <span>Tip operativo</span>
                    <p>Usa una referencia corta, una descripcion clara y una imagen alineada con la unidad para que el tablero sea facil de leer.</p>
                </div>
            </div>
        </section>

        <aside class="inventory-form__section inventory-form__section--media">
            <div class="inventory-form__heading">
                <span>Imagen del vehiculo</span>
                <h3>Sube una vista principal</h3>
                <p>Esta imagen sera la portada de la unidad en VehiPark.</p>
            </div>

            <label for="image" class="inventory-upload">
                <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml" @if ($method === 'POST') required @endif>
                <span>Seleccionar imagen</span>
                <small>{{ $method === 'POST' ? 'Campo obligatorio para vehiculos nuevos.' : 'Opcional: solo si quieres reemplazar la imagen actual.' }}</small>
            </label>

            <div class="inventory-preview">
                <img
                    id="preview-image"
                    src="{{ old('image') ? '#' : ($currentImage ?? asset('resources/img_empresa/logo_vehipark.svg')) }}"
                    alt="Vista previa del vehiculo"
                >
            </div>
        </aside>
    </div>

    <div class="inventory-form__actions">
        <a href="{{ route('dashboard') }}">Cancelar</a>
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
