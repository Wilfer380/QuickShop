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
                <div class="auth-wrapper auth-wrapper--{{ $authMode }}">
                    <aside class="auth-brand auth-brand-block">
                        <div class="auth-brand__head auth-brand-block__head">
                            <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="VehiPark" class="logo-auth-icon auth-brand-block__logo">
                            <h1 class="brand-title"><span>Vehi</span><span>Park</span></h1>
                        </div>

                        <div class="auth-brand__copy auth-brand-block__copy">
                            <p>{{ $authPanel['tagline'] }}</p>
                        </div>

                        <ul class="auth-benefits">
                            <li>
                                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z" stroke="currentColor" stroke-width="1.6"/><path d="m7 10 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>Control de ventas.</span>
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z" stroke="currentColor" stroke-width="1.6"/><path d="m7 10 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>Gestión de parqueadero.</span>
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z" stroke="currentColor" stroke-width="1.6"/><path d="m7 10 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>Reportes en tiempo real.</span>
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M10 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z" stroke="currentColor" stroke-width="1.6"/><path d="m7 10 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>Seguridad y confianza.</span>
                            </li>
                        </ul>
                    </aside>

                    <section class="auth-card {{ $authMode === 'register' ? 'register-card auth-register-card' : 'login-card auth-login-card' }}">
                        {{ $slot }}
                    </section>
                </div>

                <footer class="auth-footer">{{ $authPanel['copyright'] }}</footer>
            </main>
        </div>
    </body>
</html>
