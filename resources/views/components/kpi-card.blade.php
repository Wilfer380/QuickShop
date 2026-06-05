@props([
    'title',
    'value',
    'note' => '',
    'iconClass' => '',
    'iconBg' => 'bg-blue-500',
    'iconText' => 'text-white',
])

<article class="kpi-card">
    <div class="kpi-card__top">
        <div class="kpi-icon {{ $iconBg }} {{ $iconText }} {{ $iconClass }}">
            {{ $slot }}
        </div>
        <div class="kpi-card__copy">
            <h3>{{ $title }}</h3>
            <div class="kpi-value">{{ $value }}</div>
        </div>
    </div>
    <div class="kpi-note">{{ $note }}</div>
</article>
