@props(['slug', 'class' => 'w-6 h-6'])

@php
    $slug = strtolower($slug);
@endphp

@if ($slug === 'games')
    <!-- Game Controller SVG -->
    <svg class="{{ $class }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3-3V7.5a3 3 0 0 1-3-3h-9a3 3 0 0 1-3 3v8.25a3 3 0 0 1 3 3m9 0H7.5M9 10.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM18 10.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM12 13.5v-3m-1.5 1.5h3" />
    </svg>
@elseif ($slug === 'music' || $slug === 'songs')
    <!-- Musical Note SVG -->
    <svg class="{{ $class }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3v12.75M9 9.75v10.5c0 .414-.336.75-.75.75s-.75-.336-.75-.75.336-.75.75-.75m.75.75v-10.5m10.5-3.75v10.5c0 .414-.336.75-.75.75s-.75-.336-.75-.75.336-.75.75-.75" />
    </svg>
@elseif ($slug === 'videos' || $slug === 'video' || $slug === 'movies')
    <!-- Video/Film Camera SVG -->
    <svg class="{{ $class }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Zm0-13.5h12M6 10.5h12M6 13.5h12M6 16.5h12" />
    </svg>
@elseif ($slug === 'applications' || $slug === 'apps' || $slug === 'software')
    <!-- Device/Mobile Phone SVG -->
    <svg class="{{ $class }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3" />
    </svg>
@else
    <!-- Default Cube / Package SVG -->
    <svg class="{{ $class }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
    </svg>
@endif



