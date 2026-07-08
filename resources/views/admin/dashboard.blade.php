@extends('layouts.store')

@section('title', 'Admin Dashboard - Store13')

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="text-xs text-gray-400 mt-1">General system statistics, sales overview and item logs.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products') }}" class="text-xs px-3.5 py-2 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:border-[#f53003] rounded-md font-semibold transition-all">
                Manage Products
            </a>
            <a href="{{ route('admin.orders') }}" class="text-xs px-3.5 py-2 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-zinc-300 hover:border-[#f53003] rounded-md font-semibold transition-all">
                Manage Orders
            </a>
        </div>
    </div>

    <!-- Stats Widgets Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-5 custom-shadow">
            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Total Revenue</span>
            <span class="text-2xl font-black text-emerald-650 dark:text-emerald-400">${{ number_format($stats['total_revenue'], 2) }}</span>
        </div>
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-5 custom-shadow">
            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Completed Orders</span>
            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</span>
        </div>
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-5 custom-shadow">
            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Active Products</span>
            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['total_products'] }}</span>
        </div>
        <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-5 custom-shadow">
            <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Registered Users</span>
            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['total_users'] }}</span>
        </div>
    </div>

    <!-- Dashboard Content Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Orders (Left 2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">Recent Orders</h2>
            
            <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden custom-shadow">
                @if ($recentOrders->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-8">No orders processed yet.</p>
                @else
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 dark:bg-zinc-900/50 border-b border-gray-200 dark:border-zinc-800 text-gray-400">
                            <tr>
                                <th class="p-4">Order</th>
                                <th class="p-4">Customer</th>
                                <th class="p-4">Items</th>
                                <th class="p-4 text-right">Amount</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/80">
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td class="p-4 font-bold">{{ $order->order_number }}</td>
                                    <td class="p-4">{{ $order->user->name }}<br><span class="text-[9px] text-gray-400">{{ $order->user->email }}</span></td>
                                    <td class="p-4">
                                        @foreach ($order->items as $item)
                                            <div class="truncate max-w-[150px]">{{ $item->product->name }}</div>
                                        @endforeach
                                    </td>
                                    <td class="p-4 text-right font-black">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="p-4 text-center">
                                        <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Top Products & Category Stats (Right 1 Column) -->
        <div class="space-y-8">
            <!-- Top Products -->
            <div>
                <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider mb-4">Top Downloads</h2>
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    @foreach ($topProducts as $p)
                        <div class="flex items-center justify-between gap-4 text-xs">
                            <div class="min-w-0">
                                <span class="font-bold text-gray-800 dark:text-zinc-200 truncate block">{{ $p->name }}</span>
                                <span class="text-[9px] text-gray-400 uppercase">{{ $p->category->name }}</span>
                            </div>
                            <span class="font-black text-gray-900 dark:text-white bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 px-2 py-1 rounded">
                                {{ $p->download_count }} dl
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Category Stats -->
            <div>
                <h2 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider mb-4">Downloads By Category</h2>
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    @foreach ($categoryStats as $c)
                        <div class="text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-gray-800 dark:text-zinc-200 flex items-center gap-1.5">
                                    @include('components.category-icon', ['slug' => $c->slug, 'class' => 'w-3.5 h-3.5 text-gray-500'])
                                    {{ $c->name }}
                                </span>
                                <span class="text-gray-400">{{ $c->total_downloads ?? 0 }} downloads</span>
                            </div>
                            <!-- Simple custom HTML progress bar -->
                            <div class="w-full h-1 bg-gray-100 dark:bg-zinc-900 rounded-full overflow-hidden">
                                <div 
                                    class="h-full rounded-full" 
                                    style="background-color: {{ $c->color }}; width: {{ $stats['total_orders'] > 0 ? min(100, (($c->total_downloads ?? 0) / max(1, $stats['total_orders'])) * 10) : 0 }}%"
                                ></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
