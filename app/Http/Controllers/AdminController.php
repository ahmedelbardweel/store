<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->email !== 'admin@store.com') {
            abort(403, 'Admin access required.');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $stats = [
            'total_revenue'  => Order::where('payment_status', 'completed')->sum('total_amount'),
            'total_orders'   => Order::where('payment_status', 'completed')->count(),
            'total_products' => Product::count(),
            'total_users'    => User::count(),
        ];

        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        $topProducts = Product::orderByDesc('download_count')
            ->take(5)
            ->get();

        $categoryStats = Category::withCount('products')
            ->withSum(['products as total_downloads' => function ($q) {
                $q->selectRaw('sum(download_count)');
            }], 'download_count')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'categoryStats'));
    }

    public function products(Request $request)
    {
        $this->checkAdmin();
        $products = Product::with('category')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->whereHas('category', fn($cq) => $cq->where('slug', $request->category)))
            ->latest()
            ->paginate(20);

        $categories = Category::all();

        return view('admin.products', compact('products', 'categories'));
    }

    public function productCreate()
    {
        $this->checkAdmin();
        $categories = Category::all();
        return view('admin.product-form', compact('categories'));
    }

    public function productStore(Request $request)
    {
        $this->checkAdmin();
        $data = $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'is_free'           => 'boolean',
            'is_featured'       => 'boolean',
            'thumbnail'         => 'nullable|url',
            'file_size'         => 'nullable|string',
            'version'           => 'nullable|string',
            'trailer_url'       => 'nullable|url',
            'has_license_keys'  => 'boolean',
            'product_file'      => 'nullable|file|max:102400',   // max 100 MB
            'thumbnail_file'    => 'nullable|image|max:5120',    // max 5 MB image
            'trailer_file'      => 'nullable|file|mimes:mp4,mov,mkv,webm,avi|max:512000', // max 500 MB video
        ]);

        $data['is_free']           = $request->boolean('is_free');
        $data['is_featured']       = $request->boolean('is_featured');
        $data['has_license_keys']  = $request->boolean('has_license_keys');

        // Handle downloadable product file upload
        if ($request->hasFile('product_file')) {
            $file = $request->file('product_file');
            $path = $file->store('downloads', 'local'); // private
            $data['file_path'] = $path;
            $bytes = \Illuminate\Support\Facades\Storage::disk('local')->size($path);
            $data['file_size'] = self::formatBytes($bytes);
        }

        // Handle thumbnail/cover image upload → public disk (accessible via URL)
        if ($request->hasFile('thumbnail_file')) {
            $img  = $request->file('thumbnail_file');
            $path = $img->store('thumbnails', 'public');
            $data['thumbnail'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        // Handle trailer/preview video upload → public disk
        if ($request->hasFile('trailer_file')) {
            $vid  = $request->file('trailer_file');
            $path = $vid->store('trailers', 'public');
            $data['trailer_url'] = \Illuminate\Support\Facades\Storage::url($path);
        }

        // Handle metadata based on category
        $cat = Category::find($data['category_id']);
        if (in_array($cat->slug, ['games', 'applications'])) {
            $data['metadata'] = [
                'os'      => $request->meta_os,
                'cpu'     => $request->meta_cpu,
                'ram'     => $request->meta_ram,
                'storage' => $request->meta_storage,
            ];
        } elseif ($cat->slug === 'music') {
            $data['metadata'] = [
                'artist'   => $request->meta_artist,
                'album'    => $request->meta_album,
                'duration' => $request->meta_duration,
                'genre'    => $request->meta_genre,
                'format'   => $request->meta_format,
            ];
        } elseif ($cat->slug === 'videos') {
            $data['metadata'] = [
                'duration'   => $request->meta_duration,
                'resolution' => $request->meta_resolution,
                'format'     => $request->meta_format,
            ];
        }

        $product = Product::create($data);

        // Handle license keys
        if ($product->has_license_keys && $request->license_keys) {
            $keys = array_filter(explode("\n", $request->license_keys));
            foreach ($keys as $key) {
                \App\Models\LicenseKey::create([
                    'product_id' => $product->id,
                    'key'        => trim($key),
                ]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    private static function formatBytes(float $bytes, int $precision = 1): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pow = floor(log($bytes) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function orders(Request $request)
    {
        $this->checkAdmin();
        $orders = Order::with(['user', 'items.product'])
            ->when($request->status, fn($q) => $q->where('payment_status', $request->status))
            ->latest()
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function revokeDownload(Order $order)
    {
        $this->checkAdmin();
        $order->update(['downloads_revoked' => !$order->downloads_revoked]);
        $msg = $order->downloads_revoked ? 'Download access revoked.' : 'Download access restored.';
        return back()->with('success', $msg);
    }
}
