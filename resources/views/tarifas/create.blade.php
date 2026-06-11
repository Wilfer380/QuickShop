<x-app-layout>
    <x-clientes-styles />

    @php($method = 'POST')
    <form class="tarifa-form" action="{{ route('tarifas.store') }}" method="POST">
        @include('tarifas.partials.form')
    </form>
</x-app-layout>
