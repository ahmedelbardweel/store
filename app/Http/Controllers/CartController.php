<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                $items[]  = ['product' => $product, 'quantity' => $qty];
                $total   += $product->price * $qty;
            }
        }

        $coupon   = session()->get('coupon');
        $discount = session()->get('discount', 0);
        $finalTotal = max(0, $total - $discount);

        return view('store.cart', compact('items', 'total', 'coupon', 'discount', 'finalTotal'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = 1; // digital goods: always qty 1

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "{$product->name} added to cart!",
                'count'   => count($cart),
            ]);
        }

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        // also remove coupon if cart is empty
        if (empty($cart)) {
            session()->forget(['cart', 'coupon', 'discount']);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon' => 'required|string']);

        $coupons = [
            'SAVE10'  => 10,
            'SAVE20'  => 20,
            'FREE100' => 100,
            'WELCOME' => 5,
        ];

        $code = strtoupper(trim($request->coupon));

        if (array_key_exists($code, $coupons)) {
            session()->put('coupon', $code);
            session()->put('discount', $coupons[$code]);
            return back()->with('success', "Coupon applied! You saved \${$coupons[$code]}.");
        }

        return back()->with('error', 'Invalid coupon code.');
    }

    public function removeCoupon()
    {
        session()->forget(['coupon', 'discount']);
        return back()->with('success', 'Coupon removed.');
    }

    public function count()
    {
        return response()->json(['count' => count(session()->get('cart', []))]);
    }
}
