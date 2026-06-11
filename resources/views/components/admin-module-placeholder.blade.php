@props([
    'title',
    'eyebrow' => 'Modulo VehiPark',
    'description' => 'Base preparada para implementar este modulo en la siguiente fase.',
])

<x-app-layout>
    <section class="min-h-[calc(100vh-5rem)] bg-slate-950 px-6 py-10 text-slate-100">
        <div class="mx-auto max-w-6xl rounded-[2rem] border border-white/10 bg-slate-900/80 p-8 shadow-2xl shadow-black/40">
            <span class="text-xs font-black uppercase tracking-[0.24em] text-sky-300">{{ $eyebrow }}</span>
            <h1 class="mt-4 text-4xl font-black text-white">{{ $title }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-300">{{ $description }}</p>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <article class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                    <strong class="block text-lg text-white">Controlador</strong>
                    <span class="mt-2 block text-sm text-slate-400">Stub listo para casos de uso del modulo.</span>
                </article>
                <article class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                    <strong class="block text-lg text-white">Request</strong>
                    <span class="mt-2 block text-sm text-slate-400">Validacion aislada por modulo.</span>
                </article>
                <article class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                    <strong class="block text-lg text-white">Service</strong>
                    <span class="mt-2 block text-sm text-slate-400">Lugar para reglas de negocio sin engordar controladores.</span>
                </article>
            </div>
        </div>
    </section>
</x-app-layout>
