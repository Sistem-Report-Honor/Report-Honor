@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-2 bg-white/10 text-sm font-semibold transition duration-150 ease-in-out'
            : 'block pl-3 pr-4 py-2 hover:bg-white/20 text-sm font-medium transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
