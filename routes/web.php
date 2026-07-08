<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// ─── Public Store Routes ────────────────────────────────────────────────────
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/search', [StoreController::class, 'search'])->name('search');

// Products
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/review', [ProductController::class, 'review'])->name('products.review');

// Cart (session-based, no auth required to add)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// ─── Authenticated Routes ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // User Library / Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Secure Downloads
    Route::get('/download/{item}', [DownloadController::class, 'download'])->name('download');
});

// ─── Admin Routes ───────────────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'productCreate'])->name('products.create');
        Route::post('/products', [AdminController::class, 'productStore'])->name('products.store');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::patch('/orders/{order}/revoke', [AdminController::class, 'revokeDownload'])->name('orders.revoke');
    });

// ─── Settings & Customizations ──────────────────────────────────────────────
Route::post('/settings/language', [SettingsController::class, 'setLanguage'])->name('settings.language');
Route::middleware('auth')->group(function () {
    Route::post('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.password');
    Route::post('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');
    Route::get('/settings/switch-account/{id}', [SettingsController::class, 'switchAccount'])->name('settings.switch');
    Route::post('/settings/remove-account/{id}', [SettingsController::class, 'removeSavedAccount'])->name('settings.remove');
});

// ─── Auto-create admin on first visit ──────────────────────────────────────
Route::get('/setup', function () {
    $admin = \App\Models\User::where('email', 'admin@store.com')->first();
    if (!$admin) {
        $admin = \App\Models\User::create([
            'name'     => 'Admin',
            'email'    => 'admin@store.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'is_admin'  => true,
        ]);
        return response()->json(['message' => 'Admin account created!', 'email' => 'admin@store.com', 'password' => 'password']);
    }
    return response()->json(['message' => 'Admin already exists.', 'email' => 'admin@store.com']);
})->name('setup');

// ─── Laravel Auth Routes ────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
