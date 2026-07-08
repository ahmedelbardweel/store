<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LicenseKey;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@store.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        // Create test user
        $user = User::firstOrCreate(
            ['email' => 'user@store.com'],
            ['name' => 'Ahmed Test', 'password' => Hash::make('password')]
        );

        // Categories
        $categories = [
            ['name' => 'Games',        'slug' => 'games',        'icon' => '🎮', 'color' => '#f53003'],
            ['name' => 'Music',        'slug' => 'music',        'icon' => '🎵', 'color' => '#f8b803'],
            ['name' => 'Videos',       'slug' => 'videos',       'icon' => '🎬', 'color' => '#4F46E5'],
            ['name' => 'Applications', 'slug' => 'applications', 'icon' => '📱', 'color' => '#059669'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $games = Category::where('slug', 'games')->first();
        $music = Category::where('slug', 'music')->first();
        $videos = Category::where('slug', 'videos')->first();
        $apps = Category::where('slug', 'applications')->first();

        // ---- GAMES ----
        $gameProducts = [
            [
                'name'              => 'Cyberpunk 2077',
                'slug'              => 'cyberpunk-2077',
                'short_description' => 'An open-world, action-adventure story set in Night City.',
                'description'       => 'Cyberpunk 2077 is an open-world, action-adventure RPG set in the megalopolis of Night City, where you play as a cyberpunk mercenary wrapped up in a do-or-die fight for survival.',
                'price'             => 59.99,
                'is_free'           => false,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/f53003?text=Cyberpunk+2077',
                'file_size'         => '70 GB',
                'version'           => '2.1.0',
                'trailer_url'       => 'https://www.youtube.com/watch?v=8X2kIfS6fb8',
                'has_license_keys'  => true,
                'metadata'          => [
                    'os'       => 'Windows 10/11 64-bit',
                    'cpu'      => 'Intel Core i7-6700K / AMD Ryzen 5 1600',
                    'ram'      => '12 GB RAM',
                    'gpu'      => 'NVIDIA GTX 1060 6GB / AMD RX 580 8GB',
                    'storage'  => '70 GB SSD',
                    'platform' => 'PC',
                ],
                'rating'            => 4.5,
                'rating_count'      => 1240,
                'download_count'    => 45800,
            ],
            [
                'name'              => 'The Witcher 3: Wild Hunt',
                'slug'              => 'witcher-3-wild-hunt',
                'short_description' => 'Award-winning open world RPG.',
                'description'       => 'You are Geralt of Rivia, mercenary monster slayer. Before you stands a war-torn, monster-infested continent you can explore at will.',
                'price'             => 39.99,
                'is_free'           => false,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/f8b803?text=Witcher+3',
                'file_size'         => '35 GB',
                'version'           => '4.04',
                'trailer_url'       => 'https://www.youtube.com/watch?v=c0i88t0Kacs',
                'has_license_keys'  => true,
                'metadata'          => [
                    'os'       => 'Windows 7/8/10 64-bit',
                    'cpu'      => 'Intel Core i5-2500K 3.3GHz / AMD Phenom II X4 940',
                    'ram'      => '6 GB RAM',
                    'gpu'      => 'NVIDIA GTX 660 / AMD Radeon HD 7870',
                    'storage'  => '35 GB',
                    'platform' => 'PC',
                ],
                'rating'            => 4.9,
                'rating_count'      => 3560,
                'download_count'    => 128000,
            ],
            [
                'name'              => 'Minecraft',
                'slug'              => 'minecraft',
                'short_description' => 'Build anything, survive everything.',
                'description'       => 'Minecraft is a game about placing blocks and going on adventures. Build anything you can imagine, or survive the night.',
                'price'             => 26.99,
                'is_free'           => false,
                'is_featured'       => false,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/059669?text=Minecraft',
                'file_size'         => '1 GB',
                'version'           => '1.21',
                'trailer_url'       => null,
                'has_license_keys'  => true,
                'metadata'          => [
                    'os'       => 'Windows 10/11',
                    'cpu'      => 'Intel Core i3-3210 / AMD A8-7600',
                    'ram'      => '4 GB RAM',
                    'gpu'      => 'NVIDIA GeForce 400 / AMD Radeon HD 7000',
                    'storage'  => '4 GB',
                    'platform' => 'PC / Mobile',
                ],
                'rating'            => 4.7,
                'rating_count'      => 8900,
                'download_count'    => 320000,
            ],
            [
                'name'              => 'Valorant',
                'slug'              => 'valorant',
                'short_description' => 'A 5v5 character-based tactical shooter.',
                'description'       => 'Valorant is a free-to-play 5v5 character-based tactical shooter that blends precise gunplay with unique agent abilities.',
                'price'             => 0,
                'is_free'           => true,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/4F46E5?text=Valorant',
                'file_size'         => '20 GB',
                'version'           => '9.0',
                'trailer_url'       => null,
                'has_license_keys'  => false,
                'metadata'          => [
                    'os'       => 'Windows 10/11 64-bit',
                    'cpu'      => 'Intel Core 2 Duo E8400',
                    'ram'      => '4 GB RAM',
                    'gpu'      => 'Intel HD 4000',
                    'storage'  => '20 GB',
                    'platform' => 'PC',
                ],
                'rating'            => 4.3,
                'rating_count'      => 5600,
                'download_count'    => 890000,
            ],
        ];

        foreach ($gameProducts as $data) {
            $product = Product::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['category_id' => $games->id])
            );
            // Add license keys for paid games
            if ($product->has_license_keys && $product->licenseKeys()->count() === 0) {
                for ($i = 0; $i < 10; $i++) {
                    LicenseKey::create([
                        'product_id' => $product->id,
                        'key'        => strtoupper(implode('-', str_split(substr(md5(uniqid()), 0, 16), 4))),
                    ]);
                }
            }
        }

        // ---- MUSIC ----
        $musicProducts = [
            [
                'name'              => 'Blinding Lights - The Weeknd',
                'slug'              => 'blinding-lights-the-weeknd',
                'short_description' => 'Hit single from "After Hours" album.',
                'description'       => 'Blinding Lights is a song by Canadian singer The Weeknd. It was released as the second single from his fourth studio album After Hours.',
                'price'             => 1.29,
                'is_free'           => false,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x400/1b1b18/f53003?text=Blinding+Lights',
                'file_size'         => '8.5 MB',
                'version'           => null,
                'has_license_keys'  => false,
                'metadata'          => [
                    'artist'   => 'The Weeknd',
                    'album'    => 'After Hours',
                    'duration' => '3:20',
                    'genre'    => 'Synth-pop, R&B',
                    'year'     => 2019,
                    'format'   => 'MP3 320kbps / FLAC',
                ],
                'rating'            => 4.8,
                'rating_count'      => 2300,
                'download_count'    => 56000,
            ],
            [
                'name'              => 'Shape of You - Ed Sheeran',
                'slug'              => 'shape-of-you-ed-sheeran',
                'short_description' => 'Most streamed song on Spotify.',
                'description'       => 'Shape of You is a song by English singer-songwriter Ed Sheeran. It was released as one of two lead singles from his third studio album ÷.',
                'price'             => 1.29,
                'is_free'           => false,
                'is_featured'       => false,
                'thumbnail'         => 'https://placehold.co/400x400/1b1b18/f8b803?text=Shape+of+You',
                'file_size'         => '7.8 MB',
                'version'           => null,
                'has_license_keys'  => false,
                'metadata'          => [
                    'artist'   => 'Ed Sheeran',
                    'album'    => '÷ (Divide)',
                    'duration' => '3:53',
                    'genre'    => 'Pop, Dancehall',
                    'year'     => 2017,
                    'format'   => 'MP3 320kbps',
                ],
                'rating'            => 4.6,
                'rating_count'      => 1800,
                'download_count'    => 43000,
            ],
            [
                'name'              => 'Chill Lofi Beats Pack',
                'slug'              => 'chill-lofi-beats-pack',
                'short_description' => '50 free lofi tracks for studying.',
                'description'       => 'A collection of 50 high-quality lo-fi hip hop tracks perfect for studying, relaxing, or working.',
                'price'             => 0,
                'is_free'           => true,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x400/1b1b18/059669?text=Lofi+Beats',
                'file_size'         => '420 MB',
                'version'           => null,
                'has_license_keys'  => false,
                'metadata'          => [
                    'artist'    => 'Various Artists',
                    'tracks'    => 50,
                    'duration'  => '3h 20min total',
                    'genre'     => 'Lo-Fi, Hip Hop, Chillhop',
                    'format'    => 'MP3 192kbps',
                ],
                'rating'            => 4.4,
                'rating_count'      => 680,
                'download_count'    => 24000,
            ],
        ];

        foreach ($musicProducts as $data) {
            Product::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['category_id' => $music->id])
            );
        }

        // ---- VIDEOS ----
        $videoProducts = [
            [
                'name'              => 'Complete Web Dev Course 2024',
                'slug'              => 'complete-web-dev-course-2024',
                'short_description' => 'Full-stack development from zero to hero.',
                'description'       => 'Learn HTML, CSS, JavaScript, Node.js, React, and more in this comprehensive course with 50+ hours of video content.',
                'price'             => 19.99,
                'is_free'           => false,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/4F46E5?text=Web+Dev+Course',
                'file_size'         => '15 GB',
                'version'           => '2024',
                'has_license_keys'  => false,
                'metadata'          => [
                    'instructor' => 'John Smith',
                    'duration'   => '52 hours',
                    'lessons'    => 320,
                    'language'   => 'English',
                    'resolution' => '1080p Full HD',
                    'format'     => 'MP4',
                ],
                'rating'            => 4.7,
                'rating_count'      => 1200,
                'download_count'    => 18500,
            ],
            [
                'name'              => 'Nature Documentary Pack',
                'slug'              => 'nature-documentary-pack',
                'short_description' => '10 stunning 4K nature documentaries.',
                'description'       => 'A collection of 10 breathtaking 4K Ultra HD nature documentaries exploring Earth\'s most remote and spectacular environments.',
                'price'             => 9.99,
                'is_free'           => false,
                'is_featured'       => false,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/059669?text=Nature+Docs',
                'file_size'         => '25 GB',
                'version'           => null,
                'has_license_keys'  => false,
                'metadata'          => [
                    'episodes'   => 10,
                    'duration'   => '10 hours total',
                    'resolution' => '4K Ultra HD',
                    'format'     => 'MKV / MP4',
                    'subtitles'  => 'English, Arabic',
                ],
                'rating'            => 4.5,
                'rating_count'      => 450,
                'download_count'    => 6700,
            ],
            [
                'name'              => 'Free Stock Footage Pack Vol.1',
                'slug'              => 'free-stock-footage-pack-v1',
                'short_description' => '100 free 4K stock video clips.',
                'description'       => 'Download 100 free 4K stock video clips for your projects. No attribution required.',
                'price'             => 0,
                'is_free'           => true,
                'is_featured'       => false,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/f53003?text=Stock+Footage',
                'file_size'         => '8 GB',
                'version'           => null,
                'has_license_keys'  => false,
                'metadata'          => [
                    'clips'      => 100,
                    'resolution' => '4K 3840×2160',
                    'format'     => 'MP4 H.264',
                    'license'    => 'Free for commercial use',
                ],
                'rating'            => 4.2,
                'rating_count'      => 320,
                'download_count'    => 15000,
            ],
        ];

        foreach ($videoProducts as $data) {
            Product::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['category_id' => $videos->id])
            );
        }

        // ---- APPLICATIONS ----
        $appProducts = [
            [
                'name'              => 'Adobe Photoshop 2024',
                'slug'              => 'adobe-photoshop-2024',
                'short_description' => 'Industry-leading photo editing software.',
                'description'       => 'Adobe Photoshop 2024 is the world\'s best imaging and graphic design software. Create and enhance photos, illustrations, and 3D artwork.',
                'price'             => 54.99,
                'is_free'           => false,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/4F46E5?text=Photoshop+2024',
                'file_size'         => '4.5 GB',
                'version'           => '25.0',
                'has_license_keys'  => true,
                'metadata'          => [
                    'os'      => 'Windows 10/11 (64-bit)',
                    'cpu'     => 'Intel or AMD processor with 64-bit support',
                    'ram'     => '8 GB RAM (16 GB recommended)',
                    'gpu'     => '2 GB GPU VRAM',
                    'storage' => '20 GB available HDD space',
                    'type'    => 'Image Editing',
                ],
                'rating'            => 4.6,
                'rating_count'      => 3200,
                'download_count'    => 78000,
            ],
            [
                'name'              => 'VLC Media Player',
                'slug'              => 'vlc-media-player',
                'short_description' => 'Free and open source multimedia player.',
                'description'       => 'VLC is a free and open source cross-platform multimedia player and framework that plays most multimedia files as well as DVDs, Audio CDs, VCDs, and various streaming protocols.',
                'price'             => 0,
                'is_free'           => true,
                'is_featured'       => false,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/f8b803?text=VLC+Player',
                'file_size'         => '45 MB',
                'version'           => '3.0.20',
                'has_license_keys'  => false,
                'metadata'          => [
                    'os'      => 'Windows / macOS / Linux',
                    'cpu'     => 'Any modern CPU',
                    'ram'     => '512 MB RAM',
                    'storage' => '150 MB',
                    'type'    => 'Media Player',
                    'license' => 'GNU GPL',
                ],
                'rating'            => 4.8,
                'rating_count'      => 12000,
                'download_count'    => 2400000,
            ],
            [
                'name'              => 'Microsoft Office 2024',
                'slug'              => 'microsoft-office-2024',
                'short_description' => 'Complete productivity suite.',
                'description'       => 'Microsoft Office 2024 includes Word, Excel, PowerPoint, and Outlook. The complete productivity suite for home and business.',
                'price'             => 149.99,
                'is_free'           => false,
                'is_featured'       => true,
                'thumbnail'         => 'https://placehold.co/400x300/1b1b18/059669?text=Office+2024',
                'file_size'         => '5.2 GB',
                'version'           => '16.0',
                'has_license_keys'  => true,
                'metadata'          => [
                    'os'       => 'Windows 10/11',
                    'cpu'      => '1.6 GHz, 2-core processor',
                    'ram'      => '4 GB RAM',
                    'storage'  => '4 GB',
                    'includes' => 'Word, Excel, PowerPoint, Outlook, Teams',
                    'type'     => 'Office Suite',
                ],
                'rating'            => 4.5,
                'rating_count'      => 5600,
                'download_count'    => 340000,
            ],
        ];

        foreach ($appProducts as $data) {
            $product = Product::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['category_id' => $apps->id])
            );
            if ($product->has_license_keys && $product->licenseKeys()->count() === 0) {
                for ($i = 0; $i < 10; $i++) {
                    LicenseKey::create([
                        'product_id' => $product->id,
                        'key'        => strtoupper(implode('-', str_split(substr(md5(uniqid()), 0, 20), 5))),
                    ]);
                }
            }
        }

        // Add some sample reviews
        $products = Product::all();
        $reviewMessages = [
            ['rating' => 5, 'comment' => 'Absolutely amazing! Best purchase I made this year.'],
            ['rating' => 4, 'comment' => 'Really good, works perfectly. Minor issues but overall great.'],
            ['rating' => 5, 'comment' => 'Incredible quality. Highly recommend to everyone!'],
            ['rating' => 3, 'comment' => 'It\'s okay. Does what it says but nothing special.'],
            ['rating' => 5, 'comment' => 'Perfect! Exactly what I needed. Very fast download.'],
        ];

        foreach ($products->take(5) as $index => $product) {
            Review::firstOrCreate(
                ['user_id' => $user->id, 'product_id' => $product->id],
                $reviewMessages[$index % count($reviewMessages)]
            );
        }
    }
}
