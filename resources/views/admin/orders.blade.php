@extends('layouts.store')

@section('title', 'Manage Orders - Admin Portal')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Store Orders</h1>
        <p class="text-xs text-gray-400 mt-1">Review purchase receipts, transaction statuses, and manage download access.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-4 custom-shadow mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
            <select name="status" class="px-3 py-1.5 rounded border border-gray-200 dark:border-zinc-800 text-xs bg-gray-50 dark:bg-zinc-900">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
            <button type="submit" class="text-xs px-3 py-1.5 bg-[#111] dark:bg-white text-white dark:text-black rounded font-medium hover:opacity-90 cursor-pointer">
                Filter
            </button>
        </form>

        @if (request('status'))
            <a href="{{ route('admin.orders') }}" class="text-xs text-gray-400 hover:text-red-500">
                ✕ Clear filters
            </a>
        @endif
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl overflow-hidden custom-shadow">
        @if ($orders->isEmpty())
            <p class="text-xs text-gray-400 text-center py-12">No orders processed yet.</p>
        @else
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 dark:bg-zinc-900/50 border-b border-gray-200 dark:border-zinc-800 text-gray-400">
                    <tr>
                        <th class="p-4">Order ID</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Items Ordered</th>
                        <th class="p-4 text-right">Amount</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Downloads</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/80">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-900/30">
                            <!-- ID -->
                            <td class="p-4 font-bold">{{ $order->order_number }}</td>
                            <!-- Customer -->
                            <td class="p-4">
                                <span class="font-bold text-gray-800 dark:text-zinc-200 block">{{ $order->user->name }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $order->user->email }}</span>
                            </td>
                            <!-- Items -->
                            <td class="p-4">
                                <ul class="list-disc pl-4 space-y-0.5">
                                    @foreach ($order->items as $item)
                                        <li>
                                            {{ $item->product->name }} 
                                            @if ($item->license_key)
                                                <span class="text-[9px] font-mono text-amber-600 block">(Key: {{ $item->license_key }})</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <!-- Amount -->
                            <td class="p-4 text-right font-black">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <!-- Status -->
                            <td class="p-4 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <!-- Downloads -->
                            <td class="p-4 text-center font-bold">
                                <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $order->downloads_revoked ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ $order->downloads_revoked ? 'Revoked' : 'Active' }}
                                </span>
                            </td>
                            <!-- Action (Revoke/Restore) -->
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.orders.revoke', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button 
                                        type="submit" 
                                        class="text-xs px-2.5 py-1 rounded border {{ $order->downloads_revoked ? 'border-emerald-500 text-emerald-500 hover:bg-emerald-500 hover:text-white' : 'border-red-500 text-red-500 hover:bg-red-500 hover:text-white' }} font-semibold transition-all cursor-pointer"
                                    >
                                        {{ $order->downloads_revoked ? 'Restore Access' : 'Revoke Access' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
