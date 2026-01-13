@props([
    'href' => '#',
    'color' => 'indigo', // indigo, red, blue
])

@php
    $colorClasses = match($color) {
        'red' => 'text-red-600 hover:text-red-900',
        'blue' => 'text-blue-600 hover:text-blue-900',
        'indigo' => 'text-indigo-600 hover:text-indigo-900',
        default => 'text-indigo-600 hover:text-indigo-900',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $colorClasses . ' font-medium']) }}>
    {{ $slot }}
</a>
