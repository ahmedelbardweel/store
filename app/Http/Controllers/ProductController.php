<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product  = Product::with(['category', 'reviews.user'])->where('slug', $slug)->firstOrFail();
        $related  = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $userReview   = null;
        $hasPurchased = false;

        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            $hasPurchased = Auth::user()
                ->orders()
                ->where('payment_status', 'completed')
                ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                ->exists();
        }

        return view('store.product', compact('product', 'related', 'userReview', 'hasPurchased'));
    }

    public function review(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in to write a review.');
        }

        $hasPurchased = Auth::user()
            ->orders()
            ->where('payment_status', 'completed')
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased.');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $product->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        // Recalculate product rating
        $avg   = Review::where('product_id', $product->id)->avg('rating');
        $count = Review::where('product_id', $product->id)->count();
        $product->update(['rating' => $avg, 'rating_count' => $count]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
