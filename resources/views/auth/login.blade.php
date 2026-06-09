<x-guest-layout>
    <div class="auth-card__title">
        <div class="auth-card__icon">
            <img src="{{ asset('resources/img_empresa/logo_vehipark.svg') }}" alt="VehiPark" class="logo-auth-icon">
        </div>
        <h2>Iniciar sesión</h2>
        <p>Bienvenido, inicia sesión para continuar</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form" x-data="{ showPassword: false }">
        @csrf

        <div class="auth-field">
            <label for="email">Correo electrónico</label>
            <div class="auth-input-wrap">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.8"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <input id="email" class="input-auth" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="ejemplo@vehipark.com">
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Contraseña</label>
            <div class="auth-input-wrap">
                <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="16" r="1.4" fill="currentColor"/></svg>
                <input id="password" class="input-auth input-auth--password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••">
                <button type="button" class="auth-input-toggle" @click="showPassword = !showPassword" aria-label="Mostrar u ocultar contraseña">
                    <svg x-show="!showPassword" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="2.8" stroke="currentColor" stroke-width="1.8"/></svg>
                    <svg x-show="showPassword" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true"><path d="M3 3l18 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M10.6 10.6A2.8 2.8 0 0 0 12 15c1.5 0 2.8-1.3 2.8-2.8 0-.5-.1-1-.4-1.4" stroke="currentColor" stroke-width="1.8"/><path d="M6.8 6.8C4.7 8.2 3.3 10 2 12c1.9 3.1 5.4 7 10 7 1.8 0 3.4-.4 4.8-1.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-login-meta">
            <label for="remember_me" class="auth-check">
                <input id="remember_me" type="checkbox" name="remember" @checked(old('remember'))>
                <span>Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </div>

        <button type="submit" class="btn-auth-primary">
            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true"><path d="M12 2a5 5 0 0 1 5 5v2h1.5A2.5 2.5 0 0 1 21 11.5v7A2.5 2.5 0 0 1 18.5 21h-13A2.5 2.5 0 0 1 3 18.5v-7A2.5 2.5 0 0 1 5.5 9H7V7a5 5 0 0 1 5-5Z" stroke="currentColor" stroke-width="1.8"/><path d="M9.5 9V7a2.5 2.5 0 1 1 5 0v2" stroke="currentColor" stroke-width="1.8"/></svg>
            <span>Iniciar sesión</span>
        </button>
    </form>

    <p class="auth-card__footnote">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" class="auth-link">Regístrate aquí</a>
    </p>
</x-guest-layout>
