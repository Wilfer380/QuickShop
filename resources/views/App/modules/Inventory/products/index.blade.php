@extends('App.layouts.app')

@section('title')
    Inventario VehiPark
@endsection

@section('content')
    <div class="contenido">
        <h2>Inventario de flota</h2>
        <a href="{{ route('vehicle-publications.create') }}">Agregar vehiculo</a>

        @if (session('success'))
            <div class="inventory-success">{{ session('success') }}</div>
        @endif

        <div class="table">
            <div class="row primal">
                <div class="col">ID</div>
                <div class="col">Segmento</div>
                <div class="col">Nombre</div>
                <div class="col">Descripcion</div>
                <div class="col">Valor</div>
                <div class="col">Disponibilidad</div>
                <div class="col">Img</div>
                <div class="col">Acciones</div>
            </div>

            <div class="product">
                @forelse ($inventoryItems as $product)
                    <div class="row">
                        <div class="col"><p>{{ $product->id }}</p></div>
                        <div class="col">{{ optional($product->category)->name ?? 'Sin segmento' }}</div>
                        <div class="col"><p>{{ $product->name }}</p></div>
                        <div class="col"><p>{{ $product->description }}</p></div>
                        <div class="col"><p>$ {{ number_format((float) $product->price, 0, ',', '.') }}</p></div>
                        <div class="col"><p>{{ $product->stock }}</p></div>
                        <div class="col">
                            @if ($product->productImages->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->productImages[0]->image_path) }}" alt="Imagen del vehiculo">
                            @else
                                <p>Este vehiculo no tiene imagenes</p>
                            @endif
                        </div>
                        <div class="col action">
                            <div class="delete">
                                <form action="{{ route('vehicle-publications.delete', $product->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" value="Eliminar">
                                </form>
                            </div>

                            <div class="edit">
                                <a href="{{ route('vehicle-publications.edit', $product->id) }}">Editar</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No se encontraron vehiculos</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
