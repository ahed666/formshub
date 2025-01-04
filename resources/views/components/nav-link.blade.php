@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center p-2  hover:text-secondary_blue rounded-lg dark:text-white bg-gray-100 text-secondary_blue hover:bg-gray-100 dark:hover:bg-gray-700 group'
            : 'flex items-center p-2 text-white hover:text-secondary_blue rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>

    {{ $slot }}
</a>
