<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - Store13</title>
    
    <!-- Instrument Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS v4 CDN fallback -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        html {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col antialiased">
    
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
        <nav class="flex items-center justify-between">
            <!-- Back to Store Link -->
            <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-[#f53003] transition-colors">
                ← Back to Store
            </a>
            <!-- Register link -->
            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-xs leading-normal">
                Register Account
            </a>
        </nav>
    </header>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
        <!-- Main Card container matching welcome.blade.php layout -->
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row border border-[#19140015] dark:border-[#3E3E3A]/40 rounded-[20px] overflow-hidden shadow-lg">
            
            <!-- Left Panel (Login Form) -->
            <div class="text-[13px] leading-[20px] flex-1 p-6 pb-6 lg:p-20 lg:pb-16 bg-white dark:bg-[#161615] dark:text-[#EDEDEC]">
                <h1 class="mb-1 font-bold text-lg text-gray-900 dark:text-white">Log in to Store13</h1>
                <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">Welcome back! Enter your credentials to access your library.</p>
                
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 rounded-[12px] text-xs">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-[11px] text-gray-400 mb-1">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            placeholder="admin@store.com"
                            class="w-full px-3 py-1.5 rounded-[12px] border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003] transition-colors"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-[11px] text-gray-400">Password</label>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="password"
                            class="w-full px-3 py-1.5 rounded-[12px] border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003] transition-colors"
                        >
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="remember" id="remember" class="rounded-[12px] border-gray-300 text-[#f53003] focus:ring-[#f53003] w-3.5 h-3.5">
                        <label for="remember" class="text-[11px] text-gray-500">Remember session</label>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#111111] dark:bg-white text-white dark:text-black py-2 rounded-[18px] text-xs font-semibold hover:bg-black dark:hover:bg-zinc-150 transition-colors cursor-pointer">
                            Log In
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Panel (Laravel 13 Graphic Panel) -->
            <div class="flex-1 bg-[#FFF5F3] dark:bg-[#1D0002] relative min-h-[200px] lg:min-h-full flex items-center justify-center p-6 border-b lg:border-b-0 lg:border-l border-[#19140015] dark:border-[#3E3E3A]/40">
                <div class="text-center space-y-4">
                    <span class="text-8xl font-black text-[#f53003] tracking-tighter select-none">13</span>
                    <p class="text-[11px] font-bold text-[#f53003]/80 uppercase tracking-widest">Store13 Engine</p>
                </div>
            </div>

        </main>
    </div>

    <!-- Footer -->
    <footer class="w-full lg:max-w-4xl max-w-[335px] text-center text-[11px] text-gray-400 dark:text-zinc-500 mt-6">
        Store13 built with Laravel 13.
    </footer>

</body>
</html>



