<x-app-layout>
    <x-clientes-styles />

    @php
        $parametros = array_merge($defaults['parametros'], $configuracion->parametros ?? []);
    @endphp

    @push('styles')
        <style>
            .settings-page{padding:24px 34px 34px;color:#f8fafc}.settings-header{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;margin-bottom:22px}.settings-eyebrow{font-size:12px;font-weight:900;letter-spacing:.18em;text-transform:uppercase;color:#60a5fa}.settings-title{font-size:30px;font-weight:800;color:#fff;margin:4px 0}.settings-subtitle{font-size:14px;color:#94a3b8;max-width:720px}.settings-card{border-radius:14px;background:linear-gradient(180deg,rgba(30,41,59,.94),rgba(15,23,42,.96));border:1px solid rgba(148,163,184,.16);box-shadow:0 16px 36px rgba(0,0,0,.18);overflow:hidden}.settings-card-head{padding:20px 22px;border-bottom:1px solid rgba(148,163,184,.12)}.settings-card-title{font-size:18px;font-weight:800;color:#fff}.settings-card-copy{margin-top:4px;font-size:13px;color:#94a3b8}.settings-form{padding:22px}.settings-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.settings-field{display:grid;gap:8px}.settings-field label{font-size:13px;font-weight:700;color:#e2e8f0}.settings-field input,.settings-field select{height:44px;border-radius:10px;background:rgba(15,23,42,.9);border:1px solid rgba(148,163,184,.18);color:#e2e8f0;padding:0 14px;font-size:14px}.settings-field input:focus,.settings-field select:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.14)}.settings-field small{font-size:12px;color:#64748b}.settings-error{font-size:12px;color:#f87171}.settings-section{margin-top:24px;padding-top:22px;border-top:1px solid rgba(148,163,184,.12)}.settings-section-title{font-size:16px;font-weight:800;color:#fff;margin-bottom:14px}.settings-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:24px;flex-wrap:wrap}.settings-button{height:44px;padding:0 22px;border-radius:10px;background:linear-gradient(90deg,#2563eb,#7c3aed);border:0;color:#fff;font-size:14px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 12px 26px rgba(37,99,235,.28)}.settings-muted-button{height:44px;padding:0 18px;border-radius:10px;background:rgba(15,23,42,.76);border:1px solid rgba(148,163,184,.16);color:#cbd5e1;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}.settings-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:18px}.settings-summary-card{padding:18px 20px;border-radius:12px;background:linear-gradient(180deg,rgba(30,41,59,.84),rgba(15,23,42,.92));border:1px solid rgba(148,163,184,.14)}.settings-summary-label{font-size:12px;color:#94a3b8;font-weight:700}.settings-summary-value{margin-top:7px;font-size:20px;font-weight:800;color:#fff;word-break:break-word}@media (max-width:900px){.settings-page{padding:20px 16px 28px}.settings-header{flex-direction:column}.settings-grid,.settings-summary{grid-template-columns:1fr}.settings-actions{justify-content:stretch}.settings-button,.settings-muted-button{width:100%}}
        </style>
    @endpush

    <section class="settings-page">
        <div class="settings-header">
            <div>
                <div class="settings-eyebrow">Configuración general</div>
                <h1 class="settings-title">Configuración</h1>
                <p class="settings-subtitle">Administra los datos de la empresa y los parámetros operativos usados por VehiPark.</p>
            </div>
        </div>

        <section class="settings-summary">
            <article class="settings-summary-card">
                <div class="settings-summary-label">Empresa</div>
                <div class="settings-summary-value">{{ $configuracion->nombre_empresa }}</div>
            </article>
            <article class="settings-summary-card">
                <div class="settings-summary-label">Moneda</div>
                <div class="settings-summary-value">{{ $configuracion->moneda }}</div>
            </article>
            <article class="settings-summary-card">
                <div class="settings-summary-label">Zona horaria</div>
                <div class="settings-summary-value">{{ $parametros['zona_horaria'] }}</div>
            </article>
        </section>

        @if (session('status'))
            <div class="crud-alert">{{ session('status') }}</div>
        @endif

        <section class="settings-card">
            <div class="settings-card-head">
                <div class="settings-card-title">Datos de empresa</div>
                <p class="settings-card-copy">Los campos vacíos usan los valores por defecto cuando existen.</p>
            </div>

            <form class="settings-form" method="POST" action="{{ route('configuracion.update') }}">
                @csrf
                @method('PATCH')

                <div class="settings-grid">
                    <div class="settings-field">
                        <label for="nombre_empresa">Nombre de empresa</label>
                        <input id="nombre_empresa" name="nombre_empresa" value="{{ old('nombre_empresa', $configuracion->nombre_empresa) }}" autocomplete="organization">
                        @error('nombre_empresa')<div class="settings-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="settings-field">
                        <label for="nit">NIT</label>
                        <input id="nit" name="nit" value="{{ old('nit', $configuracion->nit) }}">
                        @error('nit')<div class="settings-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="settings-field">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono" name="telefono" value="{{ old('telefono', $configuracion->telefono) }}" autocomplete="tel">
                        @error('telefono')<div class="settings-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="settings-field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $configuracion->email) }}" autocomplete="email">
                        @error('email')<div class="settings-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="settings-field">
                        <label for="direccion">Dirección</label>
                        <input id="direccion" name="direccion" value="{{ old('direccion', $configuracion->direccion) }}" autocomplete="street-address">
                        @error('direccion')<div class="settings-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="settings-field">
                        <label for="moneda">Moneda</label>
                        <select id="moneda" name="moneda">
                            @foreach (['COP', 'USD', 'EUR'] as $moneda)
                                <option value="{{ $moneda }}" @selected(old('moneda', $configuracion->moneda) === $moneda)>{{ $moneda }}</option>
                            @endforeach
                        </select>
                        @error('moneda')<div class="settings-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="settings-section">
                    <div class="settings-section-title">Parámetros operativos</div>
                    <div class="settings-grid">
                        <div class="settings-field">
                            <label for="horas_promocion_parqueadero">Horas de promoción parqueadero</label>
                            <input id="horas_promocion_parqueadero" name="parametros[horas_promocion_parqueadero]" type="number" min="0" max="24" value="{{ old('parametros.horas_promocion_parqueadero', $parametros['horas_promocion_parqueadero']) }}">
                            @error('parametros.horas_promocion_parqueadero')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="settings-field">
                            <label for="zona_horaria">Zona horaria</label>
                            <input id="zona_horaria" name="parametros[zona_horaria]" value="{{ old('parametros.zona_horaria', $parametros['zona_horaria']) }}">
                            @error('parametros.zona_horaria')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="settings-field">
                            <label for="redondeo_minutos">Redondeo de tiempo</label>
                            <input id="redondeo_minutos" name="parametros[redondeo_minutos]" value="{{ old('parametros.redondeo_minutos', $parametros['redondeo_minutos']) }}">
                            @error('parametros.redondeo_minutos')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="settings-field">
                            <label for="tiempo_minimo_cobro">Tiempo mínimo de cobro</label>
                            <input id="tiempo_minimo_cobro" name="parametros[tiempo_minimo_cobro]" value="{{ old('parametros.tiempo_minimo_cobro', $parametros['tiempo_minimo_cobro']) }}">
                            @error('parametros.tiempo_minimo_cobro')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="settings-field">
                            <label for="tolerancia_salida">Tolerancia de salida</label>
                            <input id="tolerancia_salida" name="parametros[tolerancia_salida]" value="{{ old('parametros.tolerancia_salida', $parametros['tolerancia_salida']) }}">
                            @error('parametros.tolerancia_salida')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="settings-field">
                            <label for="tarifa_perdida_ticket">Tarifa por pérdida de ticket</label>
                            <input id="tarifa_perdida_ticket" name="parametros[tarifa_perdida_ticket]" type="number" min="0" step="100" value="{{ old('parametros.tarifa_perdida_ticket', $parametros['tarifa_perdida_ticket']) }}">
                            @error('parametros.tarifa_perdida_ticket')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="settings-field">
                            <label for="iva_incluido">IVA incluido (%)</label>
                            <input id="iva_incluido" name="parametros[iva_incluido]" type="number" min="0" max="100" step="0.01" value="{{ old('parametros.iva_incluido', $parametros['iva_incluido']) }}">
                            @error('parametros.iva_incluido')<div class="settings-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="settings-actions">
                    <a class="settings-muted-button" href="{{ route('dashboard') }}">Volver al dashboard</a>
                    <button class="settings-button" type="submit">Guardar configuración</button>
                </div>
            </form>
        </section>
    </section>
</x-app-layout>
