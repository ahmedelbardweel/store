<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Digital Download Store')</title>
    
    <!-- Instrument Sans Font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 Build or CDN fallback -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        <style>
            html {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    @endif
    
    <style>
        .custom-shadow {
            box-shadow: inset 0px 0px 0px 1px rgba(26, 26, 0, 0.16);
        }
        .dark .custom-shadow {
            box-shadow: inset 0px 0px 0px 1px rgba(255, 250, 237, 0.18);
        }
        .laravel-border {
            border-color: rgba(25, 20, 0, 0.15);
        }
        .dark .laravel-border {
            border-color: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] h-full flex flex-col antialiased">

    <!-- Header / Navigation -->
    <header class="w-full border-b border-gray-200 dark:border-zinc-800 bg-white dark:bg-[#161615]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Logo (Laravel 13 Aesthetic) -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 rounded-lg bg-[#f53003] flex items-center justify-center text-white font-black text-lg transition-transform group-hover:scale-105">
                    L
                </div>
                <span class="font-bold text-lg tracking-tight group-hover:text-[#f53003] transition-colors">
                    Store<span class="text-[#f53003]">13</span>
                </span>
            </a>

            <!-- Search Bar (Aesthetic Live Search) -->
            <div class="flex-1 max-w-md relative hidden md:block">
                <div class="relative">
                    <input 
                        type="text" 
                        id="global-search" 
                        placeholder="Search games, music, videos, apps..." 
                        class="w-full px-4 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:ring-1 focus:ring-[#f53003] transition-all"
                        autocomplete="off"
                    >
                    <div id="search-spinner" class="absolute right-3 top-2.5 hidden">
                        <svg class="animate-spin h-4 w-4 text-[#f53003]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <!-- Live Search Dropdown -->
                <div id="search-results" class="absolute left-0 right-0 mt-2 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto"></div>
            </div>

            <!-- Right Actions (Cart, Auth, Admin) -->
            <div class="flex items-center gap-3">
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 dark:text-zinc-400 hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span id="cart-badge" class="absolute -top-1 -right-1 bg-[#f53003] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center hidden">0</span>
                </a>

                <!-- User Actions / Navigation -->
                @auth
                    @if (Auth::user()->email === 'admin@store.com')
                        <a href="{{ route('admin.dashboard') }}" class="text-xs px-3 py-1.5 border border-amber-500 text-amber-500 hover:bg-amber-500 hover:text-white rounded-md transition-all">
                            Admin Portal
                        </a>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="text-xs px-3 py-1.5 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:border-[#f53003] hover:text-[#f53003] rounded-md transition-all">
                        My Library
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 text-gray-500 hover:text-red-500 transition-colors cursor-pointer">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs px-3 py-1.5 text-gray-700 dark:text-zinc-300 hover:text-[#f53003] transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="text-xs px-3.5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#f53003] dark:hover:border-[#FF4433] rounded-md transition-all">
                        Register
                    </a>
                @endauth
            </div>

        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Toast / Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-lg text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 rounded-lg text-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-gray-200 dark:border-zinc-800 bg-white dark:bg-[#161615] py-8 text-center text-xs text-gray-400 dark:text-zinc-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span>© {{ date('Y') }} Store13. All rights reserved. Built with Laravel 13.</span>
            <div class="flex gap-4">
                <a href="#" class="hover:text-[#f53003] transition-colors">Terms</a>
                <a href="#" class="hover:text-[#f53003] transition-colors">Privacy</a>
                <a href="#" class="hover:text-[#f53003] transition-colors">Contact</a>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Update Cart Badge count
        function updateCartBadge() {
            fetch("{{ route('cart.count') }}")
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('cart-badge');
                    if (data.count > 0) {
                        badge.innerText = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });
        }
        updateCartBadge();

        // Live Search Logic
        const searchInput = document.getElementById('global-search');
        const searchResults = document.getElementById('search-results');
        const spinner = document.getElementById('search-spinner');
        let searchTimeout = null;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('hidden');
                    return;
                }

                spinner.classList.remove('hidden');

                searchTimeout = setTimeout(() => {
                    fetch(`/search?q=${encodeURIComponent(query)}`)
                        .then(r => r.json())
                        .then(data => {
                            spinner.classList.add('hidden');
                            if (data.length === 0) {
                                searchResults.innerHTML = '<div class="p-4 text-xs text-gray-500 text-center">No products found.</div>';
                            } else {
                                searchResults.innerHTML = data.map(item => `
                                    <a href="${item.url}" class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-zinc-900 border-b border-gray-100 dark:border-zinc-800/50 last:border-none transition-colors">
                                        <img src="${item.thumbnail}" class="w-10 h-10 object-cover rounded-md border border-gray-100 dark:border-zinc-800" />
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-semibold truncate text-gray-800 dark:text-zinc-200">${item.name}</div>
                                            <div class="text-[10px] text-gray-400">${item.category}</div>
                                        </div>
                                        <div class="text-xs font-bold text-[#f53003]">${item.price}</div>
                                    </a>
                                `).join('');
                            }
                            searchResults.classList.remove('hidden');
                        })
                        .catch(() => spinner.classList.add('hidden'));
                }, 300);
            });

            // Close search result on clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
