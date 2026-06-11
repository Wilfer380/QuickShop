<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'VehiPark') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        @if (request()->routeIs('dashboard') || request()->routeIs('clientes.*') || request()->routeIs('vehiculos.*') || request()->routeIs('ventas.*') || request()->routeIs('parqueadero.*') || request()->routeIs('tarifas.*') || request()->routeIs('pagos.*') || request()->routeIs('reportes.*') || request()->routeIs('configuracion.*') || request()->routeIs('cupos.*') || request()->routeIs('profile.*'))
            <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="dashboard-shell">
                <x-sidebar />

                <div class="main-content" x-bind:class="sidebarOpen ? '' : 'is-collapsed'">
                    <x-topbar />

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <div class="min-h-screen bg-slate-950 text-slate-100 lg:flex">
                @include('layouts.navigation')

                <div class="min-w-0 flex-1 lg:pl-72">
                    @isset($header)
                        <header class="border-b border-white/10 bg-slate-900/80 shadow-2xl shadow-black/30">
                            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const moneyInputs = document.querySelectorAll('[data-money-input="true"]');
                const formatMoney = (input) => {
                    const raw = (input.value || '').trim();
                    if (raw === '') return;
                    if (!/^(?:0|[1-9]\d*|[1-9]\d{0,2}(?:\.\d{3})*)$/.test(raw)) return;

                    const digits = raw.replace(/\./g, '');
                    input.value = new Intl.NumberFormat('es-CO').format(Number(digits));
                };

                moneyInputs.forEach((input) => {
                    formatMoney(input);
                    input.addEventListener('input', () => formatMoney(input));
                    input.addEventListener('blur', () => formatMoney(input));
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>
