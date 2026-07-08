@extends('layouts.store')

@section('title', __('Manage Orders') . ' - ' . __('Admin Portal'))

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">{{ __('Store Orders') }}</h1>
        <p class="text-xs text-gray-400 mt-1">{{ __('Review purchase receipts, transaction statuses, and manage download access.') }}</p>
    </div>

    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-4 custom-shadow mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
            <select name="status" class="px-3 py-1.5 rounded-md border border-gray-200 dark:border-zinc-800 text-xs bg-gray-50 dark:bg-zinc-900">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
            </select>
            <button type="submit" class="text-xs px-3 py-1.5 bg-[#111] dark:bg-white text-white dark:text-black rounded-md font-medium hover:opacity-90 cursor-pointer">
                {{ __('Filter') }}
            </button>
        </form>
        @if (request('status'))
            <a href="{{ route('admin.orders') }}" class="text-xs text-gray-400 hover:text-red-500">✕ {{ __('Clear filters') }}</a>
        @endif
    </div>

    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl overflow-hidden custom-shadow">
        @if ($orders->isEmpty())
            <p class="text-xs text-gray-400 text-center py-12">{{ __('No orders processed yet.') }}</p>
        @else
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 dark:bg-zinc-900/50 border-b border-gray-200 dark:border-zinc-800 text-gray-400">
                    <tr>
                        <th class="p-4">{{ __('Order ID') }}</th>
                        <th class="p-4">{{ __('Customer') }}</th>
                        <th class="p-4">{{ __('Items Ordered') }}</th>
                        <th class="p-4 text-right">{{ __('Amount') }}</th>
                        <th class="p-4 text-center">{{ __('Status') }}</th>
                        <th class="p-4 text-center">{{ __('Downloads') }}</th>
                        <th class="p-4 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/80">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-900/30">
                            <td class="p-4 font-bold">{{ $order->order_number }}</td>
                            <td class="p-4">
                                <span class="font-bold text-gray-800 dark:text-zinc-200 block">{{ $order->user->name }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $order->user->email }}</span>
                            </td>
                            <td class="p-4">
                                <ul class="list-disc pl-4 space-y-0.5">
                                    @foreach ($order->items as $item)
                                        <li>
                                            {{ $item->product->name }}
                                            @if ($item->license_key)
                                                <span class="text-[9px] font-mono text-amber-600 block">({{ __('Key') }}: {{ $item->license_key }})</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="p-4 text-right font-black">${{ number_format($order->total_amount, 2) }}</td>
                            <td class="p-4 text-center">
                                <span class="px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-bold">
                                <span class="px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider {{ $order->downloads_revoked ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }}">
                                    {{ $order->downloads_revoked ? __('Revoked') : __('Active') }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form action="{{ route('admin.orders.revoke', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="text-xs px-2.5 py-1 rounded-md border {{ $order->downloads_revoked ? 'border-emerald-500 text-emerald-500 hover:bg-emerald-500 hover:text-white' : 'border-red-500 text-red-500 hover:bg-red-500 hover:text-white' }} font-semibold transition-all cursor-pointer">
                                        {{ $order->downloads_revoked ? __('Restore Access') : __('Revoke Access') }}
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