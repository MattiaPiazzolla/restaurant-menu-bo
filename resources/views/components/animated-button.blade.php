@props([
    'href' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'variant' => 'primary',
    'size' => 'md'
])

@php
    $baseClasses = 'inline-flex items-center gap-2 font-medium rounded-md transition-all duration-200 ease-out hover:scale-[1.02]';
    
    $variants = [
        'primary' => 'bg-black text-white hover:bg-gray-800',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700'
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
        @if($icon && $iconPosition === 'left')
            {!! $icon !!}
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            {!! $icon !!}
        @endif
    </button>
@endif
