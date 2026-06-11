<x-app-layout>
    <x-clientes-styles />

    <section class="profile-page">
        <div class="profile-header">
            <div>
                <h1 class="page-title">Perfil</h1>
                <p class="page-subtitle">Actualiza tus datos, contraseña y foto de perfil.</p>
            </div>
        </div>

        <div class="profile-cards-grid">
            <article class="profile-card">
                @include('profile.partials.update-profile-information-form')
            </article>

            <article class="profile-card">
                @include('profile.partials.update-password-form')
            </article>

            <article class="profile-card profile-card--danger">
                @include('profile.partials.delete-user-form')
            </article>
        </div>
    </section>

    @push('styles')
        <style>
            .profile-page{padding:24px 34px 34px;color:#F8FAFC}
            .profile-header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:18px;flex-wrap:wrap}
            .profile-cards-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;max-width:1180px;align-items:start}
            .profile-card{padding:22px;border-radius:14px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18)}
            .profile-card--danger{border-color:rgba(239,68,68,.22)}
            .profile-avatar{width:46px;height:46px;border-radius:9999px;background:linear-gradient(135deg,#7c3aed,#3b82f6);display:grid;place-items:center;overflow:hidden;color:#fff;font-weight:800;flex:none}
            .profile-avatar--lg{width:56px;height:56px;font-size:18px}
            .profile-avatar img{width:100%;height:100%;object-fit:cover;display:block;border-radius:inherit}
            .profile-section{display:grid;gap:18px}
            .profile-section__head h2{margin:0;color:#F8FAFC;font-size:20px;font-weight:800}
            .profile-section__head p{margin:6px 0 0;color:#94A3B8;line-height:1.7}
            .profile-form{display:grid;gap:18px}
            .profile-fields-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
            .profile-field{display:grid;gap:8px}
            .profile-field label{font-size:13px;font-weight:700;color:#E2E8F0}
            .profile-field input{width:100%;height:44px;border-radius:10px;background:rgba(15,23,42,.90);border:1px solid rgba(148,163,184,.18);color:#E2E8F0;padding:0 14px;font-size:13px;outline:none;box-sizing:border-box;min-width:0;color-scheme:dark}
            .profile-field input:focus{border-color:#3B82F6;box-shadow:0 0 0 3px rgba(59,130,246,.14)}
            .profile-avatar-upload{display:grid;grid-template-columns:auto minmax(0,1fr);align-items:center;gap:14px;padding:16px;border-radius:12px;background:rgba(15,23,42,.64);border:1px solid rgba(148,163,184,.14)}
            .profile-avatar--xl{width:72px;height:72px;font-size:22px}
            .profile-avatar-copy{display:grid;gap:6px;min-width:0}
            .profile-avatar-copy label{font-size:13px;font-weight:700;color:#E2E8F0}
            .profile-avatar-copy input[type="file"]{width:100%;max-width:100%;min-width:0;display:block;color:#CBD5E1;overflow:hidden}
            .profile-avatar-copy input[type="file"]::file-selector-button{margin-right:12px;height:32px;padding:0 12px;border:0;border-radius:8px;background:rgba(37,99,235,.18);color:#93C5FD;font-weight:700;cursor:pointer}
            .profile-avatar-copy small{color:#94A3B8}
            .profile-actions{display:flex;align-items:center;justify-content:flex-end;gap:12px;flex-wrap:wrap}
            .profile-primary{height:42px;padding:0 18px;border-radius:10px;background:linear-gradient(90deg,#2563EB,#7C3AED);border:0;color:#fff;font-weight:700}
            .profile-link{height:40px;padding:0 14px;border-radius:10px;background:rgba(15,23,42,.76);border:1px solid rgba(148,163,184,.16);color:#CBD5E1;font-weight:700}
            .profile-notice{padding:14px 16px;border-radius:10px;background:rgba(59,130,246,.10);border:1px solid rgba(59,130,246,.18);color:#BFDBFE;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
            .profile-success{color:#4ADE80;font-size:13px;font-weight:700}
            .profile-error{color:#F87171;font-size:12px}
            .profile-section--danger{border-color:rgba(239,68,68,.22)}
            @media (max-width:1024px){.profile-page{padding:20px 16px 28px}.profile-cards-grid{grid-template-columns:1fr}}
            @media (max-width:768px){.profile-fields-grid{grid-template-columns:1fr}.profile-avatar-upload{grid-template-columns:1fr}.profile-notice{align-items:flex-start}.profile-actions{justify-content:flex-start}}
        </style>
    @endpush
</x-app-layout>
