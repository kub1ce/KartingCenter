@props(['active'])

@php
 $classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-b-4 border-red-500 text-start text-sm font-bold uppercase tracking-wider neon-text-active transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-b-4 border-transparent text-start text-sm font-bold uppercase tracking-wider text-gray-500 hover:text-lime-400 hover:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>