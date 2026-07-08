@extends('layouts.store')

@section('title', 'My Library - Store13')

@section('content')
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white">My Library</h1>
        <p class="text-xs text-gray-400 mt-1">Manage and download your purchased digital products.</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-3 sm:p-5 custom-shadow">
            <span class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wider block">Total Spent</span>
            <span class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white">${{ number_format($totalSpent, 2) }}</span>
        </div>
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-3 sm:p-5 custom-shadow">
            <span class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wider block">Items</span>
            <span class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white">{{ $totalProducts }}</span>
        </div>
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-3 sm:p-5 custom-shadow">
            <span class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-wider block">Downloads</span>
            <span class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white">{{ $totalDownloads }}x</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Library List (Left 2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Your Digital Content</h2>
            
            @php $hasItems = false; @endphp
            @foreach ($orders as $order)
                @foreach ($order->items as $item)
                    @php 
                        $hasItems = true; 
                        $p = $item->product;
                    @endphp
                    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-5 custom-shadow flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        
                        <div class="flex items-center gap-4 min-w-0">
                            <img src="{{ $p->thumbnail_url }}" alt="{{ $p->name }}" class="w-16 h-16 object-cover rounded-lg border border-gray-100 dark:border-zinc-800">
                            <div class="min-w-0">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">{{ $p->category->name }}</span>
                                <h3 class="font-bold text-sm text-gray-800 dark:text-zinc-200 truncate">{{ $p->name }}</h3>
                                <div class="text-[10px] text-gray-400 flex flex-wrap gap-2 items-center mt-1">
                                    <span>Version: v{{ $p->version ?? '1.0' }}</span>
                                    <span>•</span>
                                    <span>Size: {{ $p->file_size ?? 'N/A' }}</span>
                                </div>

                                @if ($item->license_key)
                                    <div class="mt-2 p-1.5 bg-gray-50 dark:bg-zinc-900 border border-gray-150 dark:border-zinc-800 rounded text-[10px] font-mono select-all inline-block">
                                        License Key: <span class="font-bold text-[#f53003] dark:text-[#FF4433]">{{ $item->license_key }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4 justify-between sm:justify-end border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0">
                            <div class="text-right text-[10px] text-gray-400">
                                Downloaded: {{ $item->download_count }}/{{ $item->max_downloads }} times
                            </div>
                            
                            @if ($order->canDownload() && $item->download_count < $item->max_downloads)
                                <a 
                                    href="{{ route('download', $item) }}" 
                                    class="text-xs px-4 py-2 bg-[#f53003] hover:bg-red-700 text-white rounded-lg font-bold transition-all"
                                >
                                    Download File
                                </a>
                            @else
                                <span class="text-xs px-4 py-2 bg-gray-100 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-400 rounded-lg cursor-not-allowed">
                                    Disabled
                                </span>
                            @endif
                        </div>

                    </div>
                @endforeach
            @endforeach

            @if (!$hasItems)
                <div class="text-center py-16 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl custom-shadow">
                    <svg class="w-12 h-12 text-gray-300 dark:text-zinc-700 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">Your library is empty</h3>
                    <p class="mt-1 text-xs text-gray-400">Purchase items on the store, and they will be listed here.</p>
                </div>
            @endif
        </div>

        <!-- Invoices/Receipts History (Right 1 Column) -->
        <div class="space-y-6">
            <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Purchase History</h2>
            
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                @if ($orders->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-4">No order history available.</p>
                @else
                    <div class="space-y-4 divide-y divide-gray-100 dark:divide-zinc-800/80">
                        @foreach ($orders as $o)
                            <div class="pt-4 first:pt-0 space-y-2">
                                <div class="flex items-center justify-between text-xs font-bold text-gray-800 dark:text-zinc-200">
                                    <span>{{ $o->order_number }}</span>
                                    <span>${{ number_format($o->total_amount, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-gray-400">
                                    <span>Date: {{ $o->created_at->format('Y-m-d') }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $o->downloads_revoked ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                                        {{ $o->downloads_revoked ? 'Revoked' : 'Active' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
