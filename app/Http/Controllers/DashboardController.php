<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $orders = Order::with(['items.product.category'])
            ->where('user_id', Auth::id())
            ->where('payment_status', 'completed')
            ->latest()
            ->get();

        $totalSpent      = $orders->sum('total_amount');
        $totalDownloads  = $orders->flatMap->items->sum('download_count');
        $totalProducts   = $orders->flatMap->items->count();

        return view('store.dashboard', compact('orders', 'totalSpent', 'totalDownloads', 'totalProducts'));
    }
}
