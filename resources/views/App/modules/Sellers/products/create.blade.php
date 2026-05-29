@extends('App.layouts.app')

@section('title')
    Create product
@endsection

@section('links_css_js')
    <link rel="stylesheet" href="{{ asset('resources/App/modules/sellers/products/formulario_create.css') }}">
@endsection

@section('content')
    <div class="contenido">
        <div class="seller-page__header">
            <div>
                <span>QuickShop seller</span>
                <h2>Crear producto</h2>
                <p>Cargá una referencia con su categoría, descripción, precio, stock e imagen principal.</p>
            </div>
        </div>

        @include('App.modules.Sellers.products.partials.form', [
            'action' => route('seller.products.store'),
            'method' => 'POST',
            'submitLabel' => 'Crear producto',
            'product' => null,
        ])
    </div>
@endsection
