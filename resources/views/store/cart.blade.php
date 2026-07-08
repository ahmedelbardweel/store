@extends('layouts.store')

@section('title', 'Shopping Cart - Store13')

@section('content')
    <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-white mb-6">Your Shopping Cart</h1>

    @if (empty($items))
        <div class="text-center py-16 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl custom-shadow">
            <svg class="w-12 h-12 text-gray-300 dark:text-zinc-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">Your cart is empty</h3>
            <p class="mt-1 text-xs text-gray-400">Add digital games, apps, or music packs to your cart to checkout.</p>
            <a href="{{ route('home') }}" class="mt-6 inline-block text-xs bg-[#f53003] text-white px-4 py-2 rounded-md font-semibold hover:bg-red-700 transition-colors">
                Return to Store
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Items Column (Left 2 columns) -->
            <div class="lg:col-span-2 space-y-4">
                @foreach ($items as $item)
                    @php $p = $item['product']; @endphp
                    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-4 custom-shadow flex items-center gap-4">
                        <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-100 dark:border-zinc-800">
                        
                        <div class="flex-1 min-w-0">
                            <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">{{ $p->category->name }}</span>
                            <h3 class="font-bold text-sm text-gray-800 dark:text-zinc-200 truncate">
                                <a href="{{ route('products.show', $p->slug) }}">{{ $p->name }}</a>
                            </h3>
                            <span class="text-[10px] text-gray-400 block">v{{ $p->version ?? '1.0' }} • {{ $p->file_size ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center gap-6">
                            <span class="font-black text-sm text-gray-900 dark:text-white">
                                {{ $p->formatted_price }}
                            </span>

                            <form action="{{ route('cart.remove', $p) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-gray-400 hover:text-[#f53003] transition-colors cursor-pointer" title="Remove item">
                                    ✕
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary Column (Right 1 column) -->
            <div class="space-y-6">
                
                <!-- Pricing Card -->
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-6">
                    <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Order Summary</h2>
                    
                    <div class="divide-y divide-gray-100 dark:divide-zinc-800/80 text-xs">
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="font-bold text-gray-800 dark:text-zinc-200">${{ number_format($total, 2) }}</span>
                        </div>
                        @if ($discount > 0)
                            <div class="flex items-center justify-between py-2.5 text-emerald-600 dark:text-emerald-400">
                                <span>Discount ({{ $coupon }})</span>
                                <span>-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-3 text-sm">
                            <span class="font-bold text-gray-900 dark:text-white">Total Amount</span>
                            <span class="font-black text-[#f53003] dark:text-[#FF4433]">${{ number_format($finalTotal, 2) }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.index') }}" class="block w-full text-center text-xs py-3 bg-[#f53003] hover:bg-red-700 text-white rounded-lg font-bold transition-all">
                        Proceed to Checkout
                    </a>
                </div>

                <!-- Coupon Box -->
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    <h3 class="font-bold text-xs text-gray-900 dark:text-white uppercase tracking-wider">Promo Coupon</h3>
                    
                    @if ($coupon)
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded flex items-center justify-between text-xs">
                            <span class="font-bold text-emerald-800 dark:text-emerald-400">{{ $coupon }} Applied!</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] text-red-500 hover:underline cursor-pointer">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input 
                                type="text" 
                                id="coupon-input"
                                name="coupon" 
                                placeholder="Enter coupon code..." 
                                class="flex-1 px-3 py-1.5 rounded border border-gray-200 dark:border-zinc-800 text-xs bg-gray-50 dark:bg-zinc-900 focus:outline-none focus:border-[#f53003] uppercase"
                            >
                            <button type="submit" class="text-xs px-3 py-1.5 bg-[#111] dark:bg-white text-white dark:text-black rounded font-medium hover:opacity-90 cursor-pointer">
                                Apply
                            </button>
                        </form>

                        <!-- Available Coupons Hint -->
                        <div class="pt-2 border-t border-gray-100 dark:border-zinc-800/50 space-y-2">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Available Test Coupons — click to use:</p>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $coupons = [
                                        ['code' => 'SAVE10',  'desc' => '-$10 off',  'color' => 'blue'],
                                        ['code' => 'SAVE20',  'desc' => '-$20 off',  'color' => 'purple'],
                                        ['code' => 'WELCOME', 'desc' => '-$5 off',   'color' => 'green'],
                                        ['code' => 'FREE100', 'desc' => '-$100 off', 'color' => 'red'],
                                    ];
                                @endphp
                                @foreach ($coupons as $c)
                                    <button 
                                        type="button"
                                        onclick="document.getElementById('coupon-input').value='{{ $c['code'] }}'"
                                        class="text-[10px] px-2.5 py-1 rounded-full border font-bold font-mono transition-all cursor-pointer
                                            @if($c['color'] === 'blue')   border-blue-200 text-blue-700 bg-blue-50 hover:bg-blue-100 dark:border-blue-800 dark:text-blue-400 dark:bg-blue-950/20
                                            @elseif($c['color'] === 'purple') border-purple-200 text-purple-700 bg-purple-50 hover:bg-purple-100 dark:border-purple-800 dark:text-purple-400 dark:bg-purple-950/20
                                            @elseif($c['color'] === 'green') border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:border-emerald-800 dark:text-emerald-400 dark:bg-emerald-950/20
                                            @else border-red-200 text-red-700 bg-red-50 hover:bg-red-100 dark:border-red-800 dark:text-red-400 dark:bg-red-950/20 @endif"
                                    >
                                        {{ $c['code'] }} <span class="opacity-60 font-normal">{{ $c['desc'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    @endif
@endsection

