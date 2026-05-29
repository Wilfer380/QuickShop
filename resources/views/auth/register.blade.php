<x-guest-layout>
    <div class="auth-card__header">
        <span class="auth-card__eyebrow">Nueva cuenta</span>
        <h2>Creá tu acceso y empezá a usar QuickShop con una experiencia más profesional.</h2>
        <p>Registrate para comprar, vender y seguir construyendo una tienda más sólida.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="auth-input mt-2 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="auth-input mt-2 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="auth-form__split">
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="auth-input mt-2 block w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="auth-input mt-2 block w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="auth-form__actions">
            <a class="auth-secondary-button" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="auth-primary-button">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
