<x-guest-layout>
    <div class="auth-card__title">
        <h2>Iniciar sesión</h2>
        <p>Bienvenido, inicia sesión para continuar</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="auth-login-layout" x-data="{ showPassword: false }">
        <form method="POST" action="{{ route('login') }}" class="grid gap-5">
            @csrf

            <div class="auth-field">
                <label for="email">Correo electrónico</label>
                <div class="auth-input-wrap">
                    <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.8"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="ejemplo@vehipark.com">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="auth-field">
                <label for="password">Contraseña</label>
                <div class="auth-input-wrap">
                    <svg class="auth-input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="16" r="1.4" fill="currentColor"/></svg>
                    <input id="password" class="auth-input auth-input--password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="••••••••">
                    <button type="button" class="auth-input-toggle" @click="showPassword = !showPassword" aria-label="Mostrar u ocultar contraseña">
                        <svg x-show="!showPassword" viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="2.8" stroke="currentColor" stroke-width="1.8"/></svg>
                        <svg x-show="showPassword" viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M3 3l18 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M10.6 10.6A2.8 2.8 0 0 0 12 15c1.5 0 2.8-1.3 2.8-2.8 0-.5-.1-1-.4-1.4" stroke="currentColor" stroke-width="1.8"/><path d="M6.8 6.8C4.7 8.2 3.3 10 2 12c1.9 3.1 5.4 7 10 7 1.8 0 3.4-.4 4.8-1.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="auth-login-meta">
                <label for="remember_me" class="auth-check">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
            </div>

            <button type="submit" class="auth-button auth-button--primary w-full">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true"><path d="M12 2a5 5 0 0 1 5 5v2h1.5A2.5 2.5 0 0 1 21 11.5v7A2.5 2.5 0 0 1 18.5 21h-13A2.5 2.5 0 0 1 3 18.5v-7A2.5 2.5 0 0 1 5.5 9H7V7a5 5 0 0 1 5-5Z" stroke="currentColor" stroke-width="1.8"/><path d="M9.5 9V7a2.5 2.5 0 1 1 5 0v2" stroke="currentColor" stroke-width="1.8"/></svg>
                <span>{{ __('Iniciar sesión') }}</span>
            </button>
        </form>

        <div class="auth-divider">o continúa con</div>

        <div class="auth-social-grid">
            <a href="#" class="auth-social-button">
                <svg viewBox="0 0 48 48" class="h-5 w-5" aria-hidden="true"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.659 29.351 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.957 3.042l5.657-5.657C34.049 6.053 29.271 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/><path fill="#FF3D00" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.957 3.042l5.657-5.657C34.049 6.053 29.271 4 24 4c-7.675 0-14.354 4.328-17.694 10.691z"/><path fill="#4CAF50" d="M24 44c5.205 0 9.897-1.994 13.488-5.238l-6.225-5.268C29.198 35.091 26.769 36 24 36c-5.328 0-9.616-3.317-11.288-7.946l-6.522 5.025C9.48 39.556 16.227 44 24 44z"/><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.004 12.004 0 0 1-4.04 5.494l.003-.002 6.225 5.268C36.99 39.001 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/></svg>
                <span>Google</span>
            </a>
            <a href="#" class="auth-social-button">
                <svg viewBox="0 0 24 24" class="h-5 w-5" aria-hidden="true"><path fill="#F25022" d="M2 2h9v9H2z"/><path fill="#7FBA00" d="M13 2h9v9h-9z"/><path fill="#00A4EF" d="M2 13h9v9H2z"/><path fill="#FFB900" d="M13 13h9v9h-9z"/></svg>
                <span>Microsoft</span>
            </a>
        </div>

        <p class="auth-card__footnote">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="auth-link">Regístrate aquí</a>
        </p>
    </div>
</x-guest-layout>
