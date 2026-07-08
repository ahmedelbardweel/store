<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description',
        'price', 'is_free', 'is_featured', 'thumbnail', 'gallery',
        'file_path', 'file_size', 'version', 'trailer_url', 'preview_url',
        'metadata', 'download_count', 'rating', 'rating_count', 'has_license_keys',
    ];

    protected $casts = [
        'is_free'          => 'boolean',
        'is_featured'      => 'boolean',
        'has_license_keys' => 'boolean',
        'gallery'          => 'array',
        'metadata'         => 'array',
        'price'            => 'decimal:2',
        'rating'           => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function licenseKeys(): HasMany
    {
        return $this->hasMany(LicenseKey::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAvailableLicenseKey(): ?LicenseKey
    {
        return $this->licenseKeys()->where('is_used', false)->first();
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail && str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : 'https://placehold.co/400x300/1b1b18/f53003?text=' . urlencode($this->name);
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->is_free ? 'Free' : '$' . number_format($this->price, 2);
    }

    public function getStarRatingAttribute(): string
    {
        $full  = (int) floor($this->rating);
        $half  = ($this->rating - $full) >= 0.5;
        $empty = 5 - $full - ($half ? 1 : 0);

        return str_repeat('★', $full)
             . ($half ? '½' : '')
             . str_repeat('☆', $empty);
    }

    public function getFileSizeAttribute($value): string
    {
        // 1. Real file physically on disk → most accurate
        if ($this->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($this->file_path)) {
            $bytes = \Illuminate\Support\Facades\Storage::disk('local')->size($this->file_path);
            return self::formatBytes($bytes);
        }

        // 2. DB field set (either manually entered by admin or auto-calculated on upload)
        if (!empty($value)) {
            return $value;
        }

        // 3. Smart metadata-based calculation (when metadata has duration/bitrate)
        $slug   = strtolower($this->category->slug ?? '');
        $name   = strtolower($this->category->name ?? '');
        $catKey = $slug ?: $name;
        $meta   = $this->metadata ?? [];

        if (in_array($catKey, ['music', 'songs', 'audio'])) {
            $seconds = self::parseDurationToSeconds($meta['duration'] ?? '');
            if ($seconds > 0) {
                $bitrate = isset($meta['bitrate']) ? (int)$meta['bitrate'] : 320;
                $bytes = $seconds * ($bitrate * 1000) / 8;
                return self::formatBytes($bytes);
            }
        } elseif (in_array($catKey, ['videos', 'video', 'movies'])) {
            $seconds = self::parseDurationToSeconds($meta['duration'] ?? '');
            if ($seconds > 0) {
                $resolution = strtolower($meta['resolution'] ?? '1080p');
                $bitrateOverride = isset($meta['bitrate']) ? (float)$meta['bitrate'] : null;

                if ($bitrateOverride) {
                    $bitrateMbps = $bitrateOverride;
                } elseif (str_contains($resolution, '4k') || str_contains($resolution, '2160')) {
                    $bitrateMbps = 40;
                } elseif (str_contains($resolution, '1440') || str_contains($resolution, '2k')) {
                    $bitrateMbps = 16;
                } elseif (str_contains($resolution, '1080')) {
                    $bitrateMbps = 8;
                } elseif (str_contains($resolution, '720')) {
                    $bitrateMbps = 4;
                } else {
                    $bitrateMbps = 2;
                }
                $bytes = $seconds * ($bitrateMbps * 1000000) / 8;
                return self::formatBytes($bytes);
            }
        } elseif (in_array($catKey, ['games'])) {
            if (!empty($meta['storage'])) {
                return preg_replace('/\s*ssd|\s*hdd/i', '', trim($meta['storage']));
            }
        }

        // 4. Demo defaults (no metadata available)
        $defaults = [
            'music'        => '7.5 MB',
            'songs'        => '7.5 MB',
            'audio'        => '7.5 MB',
            'videos'       => '1.2 GB',
            'video'        => '1.2 GB',
            'movies'       => '2.0 GB',
            'games'        => '12.0 GB',
            'applications' => '450 MB',
            'apps'         => '450 MB',
            'software'     => '450 MB',
            'ebooks'       => '8.0 MB',
            'books'        => '8.0 MB',
        ];

        return $defaults[$catKey] ?? 'N/A';
    }


    private static function parseDurationToSeconds(?string $duration): int
    {
        if (empty($duration)) {
            return 0;
        }
        $duration = strtolower(trim($duration));
        
        // formats like 3:20
        if (preg_match('/^(\d+):(\d+)$/', $duration, $matches)) {
            return ((int)$matches[1] * 60) + (int)$matches[2];
        }
        if (preg_match('/^(\d+):(\d+):(\d+)$/', $duration, $matches)) {
            return ((int)$matches[1] * 3600) + ((int)$matches[2] * 60) + (int)$matches[3];
        }

        $seconds = 0;

        // Match hours
        if (preg_match('/(\d+)\s*(h|hour|hrs)/', $duration, $matches)) {
            $seconds += (int)$matches[1] * 3600;
        }

        // Match minutes
        if (preg_match('/(\d+)\s*(m|min|minute)/', $duration, $matches)) {
            $seconds += (int)$matches[1] * 60;
        }

        // Match seconds
        if (preg_match('/(\d+)\s*(s|sec|second)/', $duration, $matches)) {
            $seconds += (int)$matches[1];
        }

        if ($seconds === 0 && is_numeric($duration)) {
            return (int)$duration;
        }

        return $seconds;
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
}
