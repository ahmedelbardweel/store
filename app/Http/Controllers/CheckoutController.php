<?php

namespace App\Http\Controllers;

use App\Models\LicenseKey;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{

    public function index()
    {
        $cart  = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items    = [];
        $subtotal = 0;

        foreach ($cart as $productId => $qty) {
            $product = Product::find($productId);
            if ($product) {
                $items[]   = ['product' => $product, 'quantity' => $qty];
                $subtotal += $product->price;
            }
        }

        $discount   = session()->get('discount', 0);
        $coupon     = session()->get('coupon');
        $total      = max(0, $subtotal - $discount);

        return view('store.checkout', compact('items', 'subtotal', 'discount', 'coupon', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'card_name'   => 'required|string|max:255',
            'card_number' => 'required|string|size:19', // formatted: XXXX-XXXX-XXXX-XXXX
            'card_expiry' => 'required|string',
            'card_cvv'    => 'required|string|min:3|max:4',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $products = [];

            foreach ($cart as $productId => $qty) {
                $product = Product::findOrFail($productId);
                $subtotal += $product->price;
                $products[] = $product;
            }

            $discount = session()->get('discount', 0);
            $coupon   = session()->get('coupon');
            $total    = max(0, $subtotal - $discount);

            // Create order
            $order = Order::create([
                'user_id'        => Auth::id(),
                'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'coupon_code'    => $coupon,
                'total_amount'   => $total,
                'payment_status' => 'completed', // Mock payment always succeeds
                'payment_method' => 'card',
                'payment_id'     => 'PAY-' . strtoupper(Str::random(12)),
            ]);

            // Create order items & assign license keys
            foreach ($products as $product) {
                $licenseKey = null;

                if ($product->has_license_keys) {
                    $key = $product->getAvailableLicenseKey();
                    if ($key) {
                        $key->update([
                            'is_used'              => true,
                            'assigned_to_user_id'  => Auth::id(),
                            'assigned_at'          => now(),
                        ]);
                        $licenseKey = $key->key;
                    }
                }

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'price'       => $product->price,
                    'license_key' => $licenseKey,
                ]);

                // Increment download count
                $product->increment('download_count');
            }

            DB::commit();

            // Clear cart and coupon
            session()->forget(['cart', 'coupon', 'discount']);

            return redirect()->route('dashboard')->with('success', "Order #{$order->order_number} placed successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }
}
