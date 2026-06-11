<x-app-layout>
    <x-clientes-styles />

    @php($method = 'PUT')
    <form class="tarifa-form" action="{{ route('tarifas.update', $tarifa) }}" method="POST">
        @method('PUT')
        @include('tarifas.partials.form')
    </form>
</x-app-layout>
