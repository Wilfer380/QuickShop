<section class="profile-section">
    <header class="profile-section__head">
        <div>
            <h2>Actualizar contraseña</h2>
            <p>Usa una contraseña larga y aleatoria para mantener tu cuenta segura.</p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="profile-form mt-6">
        @csrf
        @method('put')

        <div class="profile-grid">
            <div class="profile-field">
                <label for="update_password_current_password">Contraseña actual</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="profile-field">
                <label for="update_password_password">Nueva contraseña</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="profile-field">
                <label for="update_password_password_confirmation">Confirmar contraseña</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="profile-actions">
            <button type="submit" class="profile-primary">Guardar</button>

            @if (session('status') === 'password-updated')
                <span class="profile-success">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
