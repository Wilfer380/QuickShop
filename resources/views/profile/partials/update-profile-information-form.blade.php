<section class="profile-section">
    <header class="profile-section__head">
        <div>
            <h2>Información del perfil</h2>
            <p>Actualiza tu nombre, correo y foto de perfil.</p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form mt-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="profile-avatar-upload">
            <div class="profile-avatar profile-avatar--xl">
                @if ($user?->avatar)
                    <img src="{{ route('profile.avatar', $user) }}" alt="Foto de perfil">
                @else
                    {{ collect(explode(' ', trim((string) $user?->name)))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') ?: 'VP' }}
                @endif
            </div>

            <div class="profile-avatar-copy">
                <label for="avatar">Foto de perfil</label>
                <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp">
                <small>JPG, PNG o WEBP. Máx. 2 MB.</small>
                @error('avatar') <div class="profile-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="profile-fields-grid">
            <div class="profile-field">
                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name') <div class="profile-error">{{ $message }}</div> @enderror
            </div>

            <div class="profile-field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email') <div class="profile-error">{{ $message }}</div> @enderror
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="profile-notice">
                <p>{{ __('Your email address is unverified.') }}</p>
                <button form="send-verification" type="submit" class="profile-link">Reenviar verificación</button>
            </div>

            @if (session('status') === 'verification-link-sent')
                <p class="profile-success">{{ __('A new verification link has been sent to your email address.') }}</p>
            @endif
        @endif

        <div class="profile-actions">
            <button type="submit" class="profile-primary">Guardar</button>

            @if (session('status') === 'profile-updated')
                <span class="profile-success">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
