<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>VehiPark</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @php
            $authMode = request()->routeIs('register') ? 'register' : (request()->routeIs('login') ? 'login' : 'guest');

            $authPanel = [
                'login' => [
                    'tagline' => 'Sistema de Venta de Vehículos y Gestión de Parqueadero',
                    'copyright' => '© 2024 VehiPark. Todos los derechos reservados.',
                ],
                'register' => [
                    'tagline' => 'Crea tu cuenta para empezar',
                    'copyright' => '© 2024 VehiPark. Todos los derechos reservados.',
                ],
                'guest' => [
                    'tagline' => 'Acceso seguro a VehiPark',
                    'copyright' => '© 2024 VehiPark. Todos los derechos reservados.',
                ],
            ][$authMode];
        @endphp

        <div class="auth-shell auth-shell--{{ $authMode }}">
            <div class="auth-shell__backdrop"></div>
            <div class="auth-shell__decor auth-shell__decor--dots"></div>
            <div class="auth-shell__decor auth-shell__decor--orb"></div>

            <main class="auth-shell__content">
                <header class="auth-brand-block">
                    <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="VehiPark logo" class="auth-brand-block__logo">
                    <div class="auth-brand-block__copy">
                        <h1>VehiPark</h1>
                        <p>{{ $authPanel['tagline'] }}</p>
                    </div>
                </header>

                <section class="auth-card auth-{{ $authMode }}-card">
                    {{ $slot }}
                </section>

                <footer class="auth-footer">{{ $authPanel['copyright'] }}</footer>
            </main>
        </div>
    </body>
</html>
