@extends('App.layouts.app')

@section('title')
    Edit product
@endsection

@section('links_css_js')
    <link rel="stylesheet" href="{{ asset('resources/App/modules/sellers/products/formulario_create.css') }}">
@endsection

@section('content')
    <div class="contenido">
        <div class="seller-page__header">
            <div>
                <span>QuickShop seller</span>
                <h2>Editar producto</h2>
                <p>Actualizá categoría, descripción, precio, stock e imagen para mantener consistente el catálogo.</p>
            </div>
        </div>

        @include('App.modules.Sellers.products.partials.form', [
            'action' => route('seller.products.update', $product->id),
            'method' => 'PUT',
            'submitLabel' => 'Guardar cambios',
            'product' => $product,
            'image' => $image,
        ])
    </div>
@endsection
