@extends('App.layouts.app')

@section('title')
    Agregar vehiculo VehiPark
@endsection

@section('content')
    <div class="contenido">
        <div class="inventory-page__header">
            <div>
                <h2>Agregar vehiculo</h2>
                <p>Carga una unidad con su segmento, descripcion, valor, disponibilidad e imagen principal.</p>
            </div>
        </div>

        @include('App.modules.Inventory.products.partials.form', [
            'action' => route('vehicle-publications.store'),
            'method' => 'POST',
            'submitLabel' => 'Guardar vehiculo',
            'product' => null,
        ])
    </div>
@endsection
