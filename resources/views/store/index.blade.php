@extends('layouts.store')

@section('title', 'Welcome to Store13 - Games, Music, Videos & Apps')
@section('description', 'Browse and download games, royalty-free music, HD videos, and open-source apps. Instant digital downloads at Store13.')

@section('content')
    <!-- Hero Slider (Aesthetic Highlight section) -->
    <div
        class="mb-10 relative overflow-hidden rounded-xl bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 p-6 sm:p-10 custom-shadow flex flex-col md:flex-row items-center gap-8 min-h-[360px]">
        <div class="flex-1 space-y-4">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 dark:bg-red-950/20 text-[#f53003] dark:text-[#FF4433] rounded-full text-xs font-semibold uppercase tracking-wider">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-2.133-1A3.75 3.75 0 0 0 12 18Z" />
                </svg>
                {{ __('Feature Highlight') }}
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ __('Discover Next-Gen Games, Music & Video Packs') }}
            </h1>
            <p class="text-gray-500 dark:text-zinc-400 max-w-lg text-sm sm:text-base">
                {{ __('Download verified high-performance software, royalty-free audio tracks, high-definition stock videos, and open-source applications built on the web.') }}
            </p>
            <div class="pt-2 flex flex-wrap gap-3">
                <a href="#store-explore"
                    class="bg-[#111111] dark:bg-white dark:text-black text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-black dark:hover:bg-zinc-150 transition-colors">
                    {{ __('Browse All Products') }}
                </a>
                <a href="?tab=free"
                    class="px-5 py-2.5 rounded-lg border border-gray-200 dark:border-zinc-800 text-sm font-medium hover:border-[#f53003] hover:text-[#f53003] transition-all">
                    {{ __('Explore Free Downloads') }}
                </a>
            </div>
        </div>
        <div class="flex-1 w-full max-w-sm md:max-w-none relative aspect-video rounded-xl overflow-hidden">
            <!-- Dynamic Hero Graphic -->
            <div class="w-full h-full bg-white dark:bg-[#161615] flex items-center justify-center relative">
                <span class="text-6xl md:text-8xl font-black text-gray-200/60 dark:text-zinc-800/40 select-none">13</span>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex gap-5 w-5/6 max-w-[360px]">
                    <div
                        class="flex-1 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-lg transform -rotate-6 flex flex-col items-center">
                        @include('components.category-icon', ['slug' => 'games', 'class' => 'w-8 h-8 text-gray-500'])
                        <div class="h-2.5 w-16 bg-gray-200 dark:bg-zinc-800/80 rounded-md mt-3"></div>
                        <div class="h-2 w-10 bg-gray-100 dark:bg-zinc-800/50 rounded-md mt-1.5"></div>
                    </div>
                    <div
                        class="flex-1 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-xl transform translate-y-2 scale-105 z-20 flex flex-col items-center">
                        @include('components.category-icon', ['slug' => 'music', 'class' => 'w-8 h-8 text-gray-750 dark:text-zinc-300'])
                        <div class="h-2.5 w-20 bg-gray-200 dark:bg-zinc-800/80 rounded-md mt-3"></div>
                        <div class="h-2 w-12 bg-gray-100 dark:bg-zinc-800/50 rounded-md mt-1.5"></div>
                    </div>
                    <div
                        class="flex-1 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl p-4 sm:p-5 shadow-lg transform rotate-6 flex flex-col items-center">
                        @include('components.category-icon', ['slug' => 'applications', 'class' => 'w-8 h-8 text-gray-500'])
                        <div class="h-2.5 w-14 bg-gray-200 dark:bg-zinc-800/80 rounded-md mt-3"></div>
                        <div class="h-2 w-8 bg-gray-100 dark:bg-zinc-800/50 rounded-md mt-1.5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filters -->
    <div id="store-explore" class="mb-8">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-400 dark:text-zinc-500 mb-4">
            {{ __('Categories') }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach ($categories as $cat)
                <a href="?category={{ $cat->slug }}"
                    class="p-4 rounded-xl border {{ $slug === $cat->slug ? 'border-[#f53003] bg-red-50/10' : 'border-gray-200 dark:border-zinc-800 bg-white dark:bg-[#161615]' }} hover:border-[#f53003] hover:scale-[1.01] transition-all flex items-center gap-3 group">
                    <div
                        class="p-2 bg-gray-50 dark:bg-zinc-900 rounded-lg group-hover:scale-105 transition-transform text-[#f53003] dark:text-[#FF4433]">
                        @include('components.category-icon', ['slug' => $cat->slug, 'class' => 'w-6 h-6'])
                    </div>
                    <div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white">{{ __($cat->name) }}</div>
                        <div class="text-[11px] text-gray-400">{{ $cat->products_count }} {{ __('items') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Main Store Section: Filter Tabs & Product Grid -->
    <div class="border-t border-gray-200 dark:border-zinc-800 pt-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <!-- Tabs -->
            <div class="flex gap-2 border-b border-gray-100 dark:border-zinc-800 pb-px">
                <a href="?tab=top{{ $slug ? '&category=' . $slug : '' }}"
                    class="px-4 py-2 text-xs font-semibold transition-all border-b-2 {{ $tab === 'top' ? 'border-[#f53003] text-[#f53003] dark:text-[#FF4433]' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                    {{ __('Top Downloaded') }}
                </a>
                <a href="?tab=newest{{ $slug ? '&category=' . $slug : '' }}"
                    class="px-4 py-2 text-xs font-semibold transition-all border-b-2 {{ $tab === 'newest' ? 'border-[#f53003] text-[#f53003] dark:text-[#FF4433]' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                    {{ __('Newest Mapped') }}
                </a>
                <a href="?tab=free{{ $slug ? '&category=' . $slug : '' }}"
                    class="px-4 py-2 text-xs font-semibold transition-all border-b-2 {{ $tab === 'free' ? 'border-[#f53003] text-[#f53003] dark:text-[#FF4433]' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                    {{ __('Free Downloads') }}
                </a>
                <a href="?tab=paid{{ $slug ? '&category=' . $slug : '' }}"
                    class="px-4 py-2 text-xs font-semibold transition-all border-b-2 {{ $tab === 'paid' ? 'border-[#f53003] text-[#f53003] dark:text-[#FF4433]' : 'border-transparent text-gray-500 hover:text-gray-900' }}">
                    {{ __('Premium / Paid') }}
                </a>
            </div>

            @if ($slug || $tab !== 'top')
                <a href="{{ route('home') }}"
                    class="text-xs text-gray-400 hover:text-[#f53003] transition-colors self-start sm:self-auto">
                    ✕ {{ __('Reset filters') }}
                </a>
            @endif
        </div>

        <!-- Product Grid -->
        @if ($products->isEmpty())
            <div
                class="text-center py-16 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl custom-shadow">
                <svg class="w-12 h-12 text-gray-300 dark:text-zinc-700 mx-auto mb-3" fill="none" stroke="currentColor"
                    stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m16.5 0a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25m16.5 0V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v1.5M13.5 10.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">{{ __('No products found') }}</h3>
                <p class="mt-1 text-xs text-gray-400">{{ __('Try changing your filters or checking back later.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <!-- Product Card -->
                    <div
                        class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl overflow-hidden custom-shadow group hover:border-[#f53003]/50 transition-all flex flex-col h-full">

                        <!-- Thumbnail/Cover -->
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="relative block aspect-[4/3] bg-gray-50 dark:bg-zinc-900 overflow-hidden border-b border-gray-100 dark:border-zinc-800">
                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-300"
                                loading="lazy">
                            <!-- Category Badge -->
                            <span
                                class="absolute top-3 left-3 px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider bg-white/95 dark:bg-zinc-900/95 shadow-sm text-gray-800 dark:text-zinc-200">
                                {{ __($product->category->name) }}
                            </span>
                        </a>

                        <!-- Details -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] text-gray-400">{{ $product->file_size ?? 'N/A' }} •
                                        v{{ $product->version ?? '1.0' }}</span>
                                    <span class="text-xs text-[#f8b803] font-bold">{{ $product->star_rating }}</span>
                                </div>
                                <h3
                                    class="font-bold text-sm truncate text-gray-800 dark:text-zinc-200 group-hover:text-[#f53003] transition-colors">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 line-clamp-2 h-8 leading-snug">
                                    {{ $product->short_description }}
                                </p>
                            </div>

                            <!-- Footer Price/Action -->
                            <div
                                class="mt-4 pt-3 border-t border-gray-100 dark:border-zinc-800/50 flex items-center justify-between">
                                <span class="font-black text-sm text-gray-900 dark:text-white">
                                    {{ $product->is_free ? __('Free') : $product->formatted_price }}
                                </span>

                                @if ($product->is_free)
                                    <!-- Direct Download / Detail link -->
                                    <a href="{{ route('products.show', $product->slug) }}"
                                        class="text-xs px-3 py-1.5 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:border-[#f53003] hover:text-[#f53003] rounded-lg font-medium transition-all">
                                        {{ __('Get Free') }}
                                    </a>
                                @else
                                    <!-- Add to Cart via post request -->
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs px-3 py-1.5 bg-[#111] dark:bg-white text-white dark:text-black hover:bg-[#f53003] dark:hover:bg-[#FF4433] dark:hover:text-white rounded-lg font-medium transition-all cursor-pointer">
                                            {{ __('Buy Now') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection





