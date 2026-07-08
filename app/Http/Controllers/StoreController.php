<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $featured = Product::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $tab  = $request->get('tab', 'top');
        $slug = $request->get('category', null);

        $query = Product::with('category');

        if ($slug) {
            $query->whereHas('category', fn($q) => $q->where('slug', $slug));
        }

        $products = match ($tab) {
            'newest' => $query->latest()->paginate(12),
            'free'   => $query->where('is_free', true)->latest()->paginate(12),
            'paid'   => $query->where('is_free', false)->latest()->paginate(12),
            default  => $query->orderByDesc('download_count')->paginate(12),
        };

        return view('store.index', compact('categories', 'featured', 'products', 'tab', 'slug'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = Product::with('category')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('short_description', 'like', "%{$query}%")
            ->take(8)
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'name'      => $p->name,
                'slug'      => $p->slug,
                'category'  => $p->category->name,
                'thumbnail' => $p->thumbnail_url,
                'price'     => $p->formatted_price,
                'url'       => route('products.show', $p->slug),
            ]);

        return response()->json($results);
    }
}
