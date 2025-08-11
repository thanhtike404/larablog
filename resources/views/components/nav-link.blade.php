@props(['href' => 'home'])

@php
    $isActive = request()->routeIs($href);
@endphp

<li>
    <a href="{{ route($href) }}" class="font-medium {{ $isActive ? 'text-blue-600 font-bold' : 'text-gray-700 hover:text-blue-600' }}" {{ $attributes }}>
    {{ $slot }}
</a>

</li>