<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'QuickShop') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="auth-shell">
            <div class="auth-shell__backdrop"></div>

            <main class="auth-shell__content">
                <section class="auth-showcase">
                    <a href="/" class="auth-showcase__brand">
                        <img src="{{ asset('resources/img_empresa/logo_quickShop.png') }}" alt="QuickShop logo">
                        <div>
                            <strong>QuickShop</strong>
                            <span>Marketplace experience</span>
                        </div>
                    </a>

                    <div class="auth-showcase__copy">
                        <span class="auth-pill">Compra y vendé mejor</span>
                        <h1>Una entrada mucho más seria para una plataforma de ventas.</h1>
                        <p>
                            Mejoramos la experiencia de acceso para que registro e inicio de sesión acompañen el
                            nivel visual del catálogo.
                        </p>
                    </div>

                    <div class="auth-showcase__stats">
                        <article>
                            <strong>Catálogo</strong>
                            <span>Productos con imagen, referencia, stock y precio claros.</span>
                        </article>
                        <article>
                            <strong>Compra</strong>
                            <span>Flujos más consistentes para carrito, saldo y checkout.</span>
                        </article>
                    </div>
                </section>

                <section class="auth-card">
                    {{ $slot }}
                </section>
            </main>
        </div>
    </body>
</html>
