@extends('App.layouts.app')

@section('title')
    Editar vehiculo VehiPark
@endsection

@section('content')
    <div class="contenido">
        <div class="inventory-page__header">
            <div>
                <h2>Editar vehiculo</h2>
                <p>Actualiza segmento, descripcion, valor, disponibilidad e imagen para mantener consistente la flota.</p>
            </div>
        </div>

        @include('App.modules.Inventory.products.partials.form', [
            'action' => route('vehicle-publications.update', $product->id),
            'method' => 'PUT',
            'submitLabel' => 'Guardar cambios',
            'product' => $product,
            'image' => $image,
        ])
    </div>
@endsection
