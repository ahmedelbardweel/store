@extends('layouts.store')

@section('title', __('Manage Products') . ' - ' . __('Admin Portal'))

@section('content')
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">{{ __('Store Products') }}</h1>
            <p class="text-xs text-gray-400 mt-1">{{ __('Add, update, and manage license keys for your products.') }}</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="text-xs px-4 py-2.5 bg-[#f53003] hover:bg-red-700 text-white rounded-lg font-bold transition-all">
            + {{ __('Add New Product') }}
        </a>
    </div>

    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl p-4 custom-shadow mb-6 flex flex-wrap gap-4 items-center justify-between">
        <form action="" method="GET" class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search products...') }}" class="px-3 py-1.5 rounded-md border border-gray-200 dark:border-zinc-800 text-xs bg-gray-50 dark:bg-zinc-900 focus:outline-none focus:border-[#f53003]">
            <select name="category" class="px-3 py-1.5 rounded-md border border-gray-200 dark:border-zinc-800 text-xs bg-gray-50 dark:bg-zinc-900">
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ __($cat->name) }}</option>
                @endforeach
            </select>
            <button type="submit" class="text-xs px-3 py-1.5 bg-[#111] dark:bg-white text-white dark:text-black rounded-md font-medium hover:opacity-90 cursor-pointer">
                {{ __('Filter') }}
            </button>
        </form>
        @if (request('search') || request('category'))
            <a href="{{ route('admin.products') }}" class="text-xs text-gray-400 hover:text-red-500">✕ {{ __('Clear filters') }}</a>
        @endif
    </div>

    <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-xl overflow-hidden custom-shadow">
        @if ($products->isEmpty())
            <p class="text-xs text-gray-400 text-center py-12">{{ __('No products added yet.') }}</p>
        @else
            <table class="w-full text-xs text-left">
                <thead class="bg-gray-50 dark:bg-zinc-900/50 border-b border-gray-200 dark:border-zinc-800 text-gray-400">
                    <tr>
                        <th class="p-4">{{ __('Product details') }}</th>
                        <th class="p-4">{{ __('Category') }}</th>
                        <th class="p-4">{{ __('Price') }}</th>
                        <th class="p-4 text-center">{{ __('Version') }}</th>
                        <th class="p-4 text-center">{{ __('Downloads') }}</th>
                        <th class="p-4 text-center">{{ __('Featured') }}</th>
                        <th class="p-4 text-center">{{ __('Keys') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/80">
                    @foreach ($products as $p)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-900/30">
                            <td class="p-4 flex items-center gap-3">
                                <img src="{{ $p->thumbnail_url }}" class="w-10 h-10 object-cover rounded-lg border border-gray-100 dark:border-zinc-800">
                                <div class="min-w-0">
                                    <span class="font-bold text-gray-800 dark:text-zinc-200 block truncate max-w-[200px]">{{ $p->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $p->file_size ?? __('N/A') }}</span>
                                </div>
                            </td>
                            <td class="p-4 uppercase font-semibold text-[10px] tracking-wider text-gray-400">
                                {{ __($p->category->name) }}
                            </td>
                            <td class="p-4 font-black">{{ $p->formatted_price }}</td>
                            <td class="p-4 text-center text-gray-500">v{{ $p->version ?? '1.0' }}</td>
                            <td class="p-4 text-center font-bold text-gray-800 dark:text-zinc-200">{{ number_format($p->download_count) }}</td>
                            <td class="p-4 text-center">
                                <span class="px-1.5 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider {{ $p->is_featured ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-400' }}">
                                    {{ $p->is_featured ? __('Yes') : __('No') }}
                                </span>
                            </td>
                            <td class="p-4 text-center font-bold">
                                @if ($p->has_license_keys)
                                    <span class="text-amber-600">{{ $p->licenseKeys()->where('is_used', false)->count() }} {{ __('key(s)') }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800/80">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection






