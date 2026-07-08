@extends('layouts.store')

@section('title', $product->name . ' - Store13')

@section('content')
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-[#f53003] transition-colors flex items-center gap-1">
            ← Back to Store
        </a>
    </div>

    <!-- Product Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Media & Description -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Main Title & Meta -->
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-red-50 dark:bg-red-950/20 text-[#f53003] dark:text-[#FF4433]">
                        {{ $product->category->name }}
                    </span>
                    <span class="text-xs text-gray-400">Released: {{ $product->created_at->format('M d, Y') }}</span>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                    {{ $product->name }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-zinc-400 italic">
                    {{ $product->short_description }}
                </p>
            </div>

            <!-- Media Preview Showcase (Dynamic based on Category) -->
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden custom-shadow">
                
                @if ($product->category->slug === 'music')
                    <!-- Audio Player Interface -->
                    <div class="p-8 bg-zinc-50 dark:bg-zinc-900/50 flex flex-col items-center justify-center space-y-4 border-b border-gray-150 dark:border-zinc-800">
                        <div class="p-4 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl text-[#f53003] dark:text-[#FF4433] shadow-sm">
                            @include('components.category-icon', ['slug' => 'music', 'class' => 'w-12 h-12'])
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-sm text-gray-800 dark:text-zinc-200">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-400">{{ $product->metadata['artist'] ?? 'Unknown Artist' }} • {{ $product->metadata['album'] ?? 'Single' }}</p>
                        </div>
                        
                        <!-- 30-Second Audio Preview Player -->
                        <div class="w-full max-w-md pt-2">
                            <audio controls class="w-full">
                                <source src="{{ $product->preview_url ?? 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3' }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <span class="block text-[10px] text-gray-400 text-center mt-2">Preview Mode (30 Seconds Audio Clip)</span>
                        </div>
                    </div>
                @elseif ($product->category->slug === 'videos' || ($product->category->slug === 'games' && $product->trailer_url))
                    <!-- Video Embed Player (Trailer / Course Clip) -->
                    <div class="aspect-video bg-black relative">
                        @if ($product->trailer_url)
                            @php
                                // Simple YouTube URL parser for embed
                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $product->trailer_url, $match);
                                $embedId = $match[1] ?? null;
                            @endphp

                            @if ($embedId)
                                <iframe 
                                    class="w-full h-full" 
                                    src="https://www.youtube.com/embed/{{ $embedId }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                ></iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-zinc-500">
                                    <video controls class="w-full h-full object-cover">
                                        <source src="https://assets.mixkit.co/videos/preview/mixkit-forest-stream-in-the-sunlight-529-large.mp4" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-500">
                                <video controls class="w-full h-full object-cover">
                                    <source src="https://assets.mixkit.co/videos/preview/mixkit-forest-stream-in-the-sunlight-529-large.mp4" type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Cover Image Placeholder (Games/Apps with no video) -->
                    <div class="aspect-video bg-gray-50 dark:bg-zinc-900 border-b border-gray-150 dark:border-zinc-800 flex items-center justify-center">
                        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Long Description -->
                <div class="p-6 space-y-4">
                    <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Product Description</h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-zinc-300 leading-relaxed whitespace-pre-line">
                        {{ $product->description ?? 'No detailed description available.' }}
                    </p>
                </div>
            </div>

            <!-- Technical Specifications / Track List Metadata -->
            @if ($product->metadata)
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Specifications / Details</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        @foreach ($product->metadata as $key => $value)
                            <div class="flex items-center justify-between p-2 border-b border-gray-100 dark:border-zinc-800">
                                <span class="text-gray-400 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="font-semibold text-gray-800 dark:text-zinc-200">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reviews and Comments -->
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-6">
                <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Reviews ({{ $product->reviews->count() }})</h2>
                
                @if ($product->reviews->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-6">No reviews yet for this product.</p>
                @else
                    <div class="space-y-4 divide-y divide-gray-100 dark:divide-zinc-800">
                        @foreach ($product->reviews as $rev)
                            <div class="pt-4 first:pt-0 space-y-1">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="font-bold text-xs text-gray-800 dark:text-zinc-200">{{ $rev->user->name }}</span>
                                    <span class="text-xs text-amber-500">{{ str_repeat('★', $rev->rating) }}{{ str_repeat('☆', 5 - $rev->rating) }}</span>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-zinc-300 leading-snug">{{ $rev->comment }}</p>
                                <span class="block text-[10px] text-gray-400">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Post a Review Form (Only for purchasers) -->
                @auth
                    @if ($hasPurchased && !$userReview)
                        <div class="border-t border-gray-150 dark:border-zinc-800 pt-6 space-y-4">
                            <h3 class="font-bold text-xs text-gray-800 dark:text-zinc-200">Write a Review</h3>
                            <form action="{{ route('products.review', $product) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-[11px] text-gray-400 mb-1">Rating</label>
                                    <select name="rating" class="text-xs p-2 rounded border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 w-24">
                                        <option value="5">★★★★★ (5)</option>
                                        <option value="4">★★★★☆ (4)</option>
                                        <option value="3">★★★☆☆ (3)</option>
                                        <option value="2">★★☆☆☆ (2)</option>
                                        <option value="1">★☆☆☆☆ (1)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-400 mb-1">Comment</label>
                                    <textarea name="comment" rows="3" class="text-xs p-2 rounded border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 w-full" placeholder="Share your experience downloading this product..."></textarea>
                                </div>
                                <button type="submit" class="text-xs px-4 py-2 bg-[#f53003] text-white rounded-md font-semibold cursor-pointer">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

        </div>

        <!-- Right 1 Column: Checkout / Download Action Box -->
        <div class="space-y-6">
            
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-6 sticky top-6">
                
                <div class="space-y-1">
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Price</span>
                    <div class="text-3xl font-black text-gray-900 dark:text-white">
                        {{ $product->formatted_price }}
                    </div>
                </div>

                <!-- Specs summary -->
                <div class="divide-y divide-gray-100 dark:divide-zinc-800/80 text-xs">
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-gray-400">File Size</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200">{{ $product->file_size ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-gray-400">Current Version</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200">v{{ $product->version ?? '1.0' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-gray-400">License keys</span>
                        <span class="font-bold text-gray-800 dark:text-zinc-200">{{ $product->has_license_keys ? 'Included' : 'None Required' }}</span>
                    </div>
                </div>

                <!-- Primary Action Button -->
                <div class="space-y-3">
                    @if ($product->is_free)
                        @auth
                            <!-- Since it is free, we can simulate adding it to user dashboard immediately -->
                            <form action="{{ route('checkout.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="card_name" value="Free Item">
                                <input type="hidden" name="card_number" value="0000-0000-0000-0000">
                                <input type="hidden" name="card_expiry" value="00/00">
                                <input type="hidden" name="card_cvv" value="000">
                                <!-- Pass item ID via custom session payload -->
                                @php session()->put('cart', [$product->id => 1]); @endphp
                                <button type="submit" class="w-full text-center text-xs py-3 bg-[#f53003] hover:bg-red-700 text-white rounded-lg font-bold transition-all cursor-pointer">
                                    Download Free
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center text-xs py-3 bg-[#111111] dark:bg-white text-white dark:text-black rounded-lg font-bold hover:opacity-90 transition-all">
                                Log in to Download
                            </a>
                        @endauth
                    @else
                        <!-- Paid product checkouts -->
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center text-xs py-3 bg-[#f53003] hover:bg-red-700 text-white rounded-lg font-bold transition-all cursor-pointer">
                                Buy Now
                            </button>
                        </form>
                    @endif
                </div>

                <div class="text-[10px] text-gray-400 dark:text-zinc-500 text-center leading-relaxed">
                    Downloads are scanned for malware and hosted on secure private storage nodes.
                </div>
            </div>

        </div>

    </div>
@endsection
