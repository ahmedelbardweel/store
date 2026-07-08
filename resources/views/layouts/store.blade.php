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
    <header class="w-full border-b border-gray-200 dark:border-zinc-800 bg-white dark:bg-[#161615] sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 sm:h-16 flex items-center justify-between gap-3">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#f53003] flex items-center justify-center text-white font-black text-base sm:text-lg transition-transform group-hover:scale-105">
                    L
                </div>
                <span class="font-bold text-base sm:text-lg tracking-tight group-hover:text-[#f53003] transition-colors">
                    Store<span class="text-[#f53003]">13</span>
                </span>
            </a>

            <!-- Search Bar (Desktop only) -->
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

            <!-- Right Actions -->
            <div class="flex items-center gap-1 sm:gap-3">
                <!-- Mobile Search Toggle -->
                <button id="mobile-search-toggle" class="md:hidden p-2 text-gray-600 dark:text-zinc-400 hover:text-[#f53003] transition-colors rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 dark:text-zinc-400 hover:text-[#f53003] dark:hover:text-[#FF4433] transition-colors rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span id="cart-badge" class="absolute -top-0.5 -right-0.5 bg-[#f53003] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center hidden">0</span>
                </a>

                <!-- Desktop Auth Actions -->
                <div class="hidden sm:flex items-center gap-2">
                    @auth
                        @if (Auth::user()->email === 'admin@store.com')
                            <a href="{{ route('admin.dashboard') }}" class="text-xs px-2.5 py-1.5 border border-amber-500 text-amber-500 hover:bg-amber-500 hover:text-white rounded-md transition-all">
                                Admin
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="text-xs px-2.5 py-1.5 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:border-[#f53003] hover:text-[#f53003] rounded-md transition-all">
                            My Library
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs px-2.5 py-1.5 text-gray-500 hover:text-red-500 transition-colors cursor-pointer">
                                Log out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-xs px-2.5 py-1.5 text-gray-700 dark:text-zinc-300 hover:text-[#f53003] transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="text-xs px-2.5 py-1.5 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#f53003] dark:hover:border-[#FF4433] rounded-md transition-all">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger -->
                <button id="mobile-menu-toggle" class="sm:hidden p-2 text-gray-600 dark:text-zinc-400 hover:text-[#f53003] transition-colors rounded-lg">
                    <svg id="hamburger-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Search Bar (Expandable) -->
        <div id="mobile-search-bar" class="hidden md:hidden border-t border-gray-100 dark:border-zinc-800 px-4 py-2.5 bg-white dark:bg-[#161615]">
            <div class="relative">
                <input 
                    type="text" 
                    id="mobile-search-input" 
                    placeholder="Search products..." 
                    class="w-full px-4 py-2 rounded-lg text-sm bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 focus:outline-none focus:border-[#f53003] focus:ring-1 focus:ring-[#f53003] transition-all"
                    autocomplete="off"
                >
                <div id="mobile-search-spinner" class="absolute right-3 top-2.5 hidden">
                    <svg class="animate-spin h-4 w-4 text-[#f53003]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            <div id="mobile-search-results" class="mt-2 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-lg shadow-lg z-50 hidden max-h-72 overflow-y-auto"></div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden sm:hidden border-t border-gray-100 dark:border-zinc-800 bg-white dark:bg-[#161615]">
            <div class="px-4 py-3 space-y-1">
                @auth
                    @if (Auth::user()->email === 'admin@store.com')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/20 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            Admin Portal
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        My Library
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-[#f53003] hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Create Account
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
        // ── Cart Badge ──
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

        // ── Mobile Menu Toggle ──
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
                // Close search if open
                document.getElementById('mobile-search-bar').classList.add('hidden');
            });
        }

        // ── Mobile Search Toggle ──
        const mobileSearchToggle = document.getElementById('mobile-search-toggle');
        const mobileSearchBar = document.getElementById('mobile-search-bar');
        if (mobileSearchToggle) {
            mobileSearchToggle.addEventListener('click', function() {
                mobileSearchBar.classList.toggle('hidden');
                // Close menu if open
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                if (!mobileSearchBar.classList.contains('hidden')) {
                    document.getElementById('mobile-search-input').focus();
                }
            });
        }

        // ── Live Search Helper ──
        function setupSearch(inputId, resultsId, spinnerId) {
            const input = document.getElementById(inputId);
            const results = document.getElementById(resultsId);
            const spinner = spinnerId ? document.getElementById(spinnerId) : null;
            if (!input || !results) return;

            let timeout = null;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value.trim();
                if (query.length < 2) {
                    results.innerHTML = '';
                    results.classList.add('hidden');
                    return;
                }
                if (spinner) spinner.classList.remove('hidden');
                timeout = setTimeout(() => {
                    fetch(`/search?q=${encodeURIComponent(query)}`)
                        .then(r => r.json())
                        .then(data => {
                            if (spinner) spinner.classList.add('hidden');
                            if (data.length === 0) {
                                results.innerHTML = '<div class="p-4 text-xs text-gray-500 text-center">No products found.</div>';
                            } else {
                                results.innerHTML = data.map(item => `
                                    <a href="${item.url}" class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-zinc-900 border-b border-gray-100 dark:border-zinc-800/50 last:border-none transition-colors">
                                        <img src="${item.thumbnail}" class="w-9 h-9 object-cover rounded-md border border-gray-100 dark:border-zinc-800 shrink-0" />
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-semibold truncate text-gray-800 dark:text-zinc-200">${item.name}</div>
                                            <div class="text-[10px] text-gray-400">${item.category}</div>
                                        </div>
                                        <div class="text-xs font-bold text-[#f53003] shrink-0">${item.price}</div>
                                    </a>
                                `).join('');
                            }
                            results.classList.remove('hidden');
                        })
                        .catch(() => { if (spinner) spinner.classList.add('hidden'); });
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !results.contains(e.target)) {
                    results.classList.add('hidden');
                }
            });
        }

        setupSearch('global-search', 'search-results', 'search-spinner');
        setupSearch('mobile-search-input', 'mobile-search-results', 'mobile-search-spinner');
    </script>
    @yield('scripts')
</body>
</html>
