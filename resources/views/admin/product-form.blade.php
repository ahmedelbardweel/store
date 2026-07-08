@extends('layouts.store')

@section('title', 'Add New Product - Admin Portal')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products') }}" class="text-xs text-gray-400 hover:text-[#f53003] transition-colors">
            ← Back to Products
        </a>
    </div>    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">Add New Product</h1>
                <p class="text-xs text-gray-400 mt-1">Fill in details. Dynamic inputs will adjust based on the selected Category.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-3 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-400 rounded text-xs">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            @csrf

            <!-- Left Side: Main Info (2 Columns) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Core Details -->
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800 dark:text-zinc-200">Product Info</h2>
                    
                    <!-- Category (Dynamic trigger) -->
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Product Category</label>
                        <select 
                            name="category_id" 
                            id="category_selector" 
                            required 
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003] transition-colors"
                        >
                            <option value="">Select a Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Standard Fields Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Product Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                required 
                                placeholder="e.g. Grand Theft Auto V" 
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                            >
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Price ($)</label>
                            <input 
                                type="number" 
                                name="price" 
                                step="0.01" 
                                required 
                                placeholder="0.00" 
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Short Description</label>
                        <input 
                            type="text" 
                            name="short_description" 
                            placeholder="Brief headline..." 
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                        >
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Full Description</label>
                        <textarea 
                            name="description" 
                            rows="5" 
                            placeholder="Enter HTML or plain text specifications..." 
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                        ></textarea>
                    </div>
                </div>

                <!-- Dynamic Sections Container -->
                <div class="space-y-6">
                    <!-- Dynamic Section: Games / Apps (System Specs) -->
                    <div id="dynamic-specs-section" class="hidden space-y-3 p-6 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl custom-shadow animate-fadeIn">
                        <span class="block font-bold text-xs text-gray-800 dark:text-zinc-200 uppercase tracking-wider">System Specifications</span>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] text-gray-400">OS</label>
                                <input type="text" name="meta_os" placeholder="Windows 10 64-bit" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400">RAM</label>
                                <input type="text" name="meta_ram" placeholder="8 GB RAM" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400">CPU</label>
                                <input type="text" name="meta_cpu" placeholder="Intel Core i5" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400">Storage Required</label>
                                <input type="text" name="meta_storage" placeholder="50 GB SSD" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-150 dark:border-zinc-800">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="has_license_keys" id="has_license_keys" class="w-3.5 h-3.5 text-[#f53003] border-gray-300 rounded focus:ring-0">
                                <label for="has_license_keys" class="text-xs text-gray-655 dark:text-zinc-400">Distribute License Keys on purchase</label>
                            </div>
                            <div id="license-keys-container" class="hidden mt-3">
                                <label class="block text-[10px] text-gray-400 mb-1 font-semibold uppercase">Keys Stock (one per line)</label>
                                <textarea name="license_keys" rows="3" placeholder="KEY-XXXX-YYYY&#10;KEY-AAAA-BBBB" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003] font-mono"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Section: Music (Artist/Album info) -->
                    <div id="dynamic-music-section" class="hidden space-y-3 p-6 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl custom-shadow animate-fadeIn">
                        <div class="flex items-center justify-between">
                            <span class="block font-bold text-xs text-gray-800 dark:text-zinc-200 uppercase tracking-wider">🎵 Track Information</span>
                            <span id="music-calc-badge" class="hidden text-[10px] bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-full font-semibold animate-pulse"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Artist Name</label>
                                <input type="text" name="meta_artist" placeholder="The Weeknd" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Album Name</label>
                                <input type="text" name="meta_album" placeholder="Starboy" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Duration <span class="text-gray-300">(mm:ss)</span></label>
                                <input type="text" name="meta_duration" id="music_duration" placeholder="3:45" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Genre</label>
                                <input type="text" name="meta_genre" placeholder="Pop / Synth" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Bitrate (kbps)</label>
                                <select name="meta_bitrate" id="music_bitrate" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                                    <option value="128">128 kbps (Standard)</option>
                                    <option value="192">192 kbps (Good)</option>
                                    <option value="256">256 kbps (High)</option>
                                    <option value="320" selected>320 kbps (Premium)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Format</label>
                                <select name="meta_format" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                                    <option value="MP3">MP3</option>
                                    <option value="FLAC">FLAC (Lossless)</option>
                                    <option value="AAC">AAC</option>
                                    <option value="WAV">WAV</option>
                                    <option value="OGG">OGG Vorbis</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 mb-1">Audio Preview URL <span class="text-gray-305 dark:text-zinc-500">(30s clip)</span></label>
                            <input type="url" name="preview_url" placeholder="https://domain.com/preview.mp3" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                        </div>
                    </div>

                    <!-- Dynamic Section: Video (Resolution/Duration details) -->
                    <div id="dynamic-video-section" class="hidden space-y-3 p-6 bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl custom-shadow animate-fadeIn">
                        <div class="flex items-center justify-between">
                            <span class="block font-bold text-xs text-gray-800 dark:text-zinc-200 uppercase tracking-wider">🎬 Video Content Information</span>
                            <span id="video-calc-badge" class="hidden text-[10px] bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-2 py-0.5 rounded-full font-semibold animate-pulse"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Resolution</label>
                                <select name="meta_resolution" id="video_resolution" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                                    <option value="480p">480p (SD)</option>
                                    <option value="720p">720p HD</option>
                                    <option value="1080p" selected>1080p Full HD</option>
                                    <option value="1440p">1440p (2K)</option>
                                    <option value="4K">4K Ultra HD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Format</label>
                                <select name="meta_format" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                                    <option value="MP4">MP4 (H.264)</option>
                                    <option value="MKV">MKV (H.265/HEVC)</option>
                                    <option value="AVI">AVI</option>
                                    <option value="MOV">MOV (QuickTime)</option>
                                    <option value="WEBM">WebM (VP9)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Duration <span class="text-gray-300">(e.g. 2h 15min or 1:30:00)</span></label>
                                <input type="text" name="meta_duration" id="video_duration" placeholder="1:30:00" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Encoding / Bitrate override</label>
                                <select name="meta_bitrate" id="video_bitrate" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                                    <option value="auto" selected>Auto (based on resolution)</option>
                                    <option value="1">1 Mbps (Compressed)</option>
                                    <option value="4">4 Mbps (720p Standard)</option>
                                    <option value="8">8 Mbps (1080p Standard)</option>
                                    <option value="16">16 Mbps (1080p High)</option>
                                    <option value="25">25 Mbps (2K)</option>
                                    <option value="40">40 Mbps (4K Standard)</option>
                                    <option value="80">80 Mbps (4K High)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Language</label>
                                <input type="text" name="meta_language" placeholder="English, Arabic" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-400 mb-1">Subtitles</label>
                                <input type="text" name="meta_subtitles" placeholder="English, Arabic" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>
                        </div>
                        <!-- Trailer / Preview Video: Upload OR YouTube URL -->
                        <div class="space-y-1 pt-2">
                            <label class="block text-[10px] text-gray-400 mb-1 font-semibold uppercase">Trailer / Preview Video</label>

                            <!-- Tab switcher -->
                            <div class="flex gap-2 mb-2">
                                <button type="button" id="trailer-tab-url"
                                    onclick="switchTab('trailer','url')"
                                    class="text-[10px] px-3 py-1 rounded-full bg-[#f53003] text-white font-semibold">
                                    🔗 YouTube / URL
                                </button>
                                <button type="button" id="trailer-tab-upload"
                                    onclick="switchTab('trailer','upload')"
                                    class="text-[10px] px-3 py-1 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 font-semibold">
                                    ↑ Upload Video
                                </button>
                            </div>

                            <!-- URL panel (default) -->
                            <div id="trailer-panel-url">
                                <input type="url" name="trailer_url"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]">
                            </div>

                            <!-- Upload panel (hidden by default) -->
                            <div id="trailer-panel-upload" class="hidden">
                                <div class="relative border border-dashed border-gray-200 dark:border-zinc-800 rounded-xl p-4 bg-gray-50 dark:bg-zinc-900/50 flex flex-col items-center justify-center hover:border-[#f53003] transition group">
                                    <input type="file" name="trailer_file" id="trailer_file"
                                        accept="video/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <svg id="trailer-upload-icon" class="w-7 h-7 text-gray-400 dark:text-zinc-500 mb-1 group-hover:text-[#f53003] transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                    <span id="trailer-upload-label" class="text-xs font-semibold text-gray-650 dark:text-zinc-300">Click or drag video here</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5">MP4, MOV, MKV, WebM — max 500 MB</span>
                                    <span id="trailer-size-info" class="text-[10px] text-zinc-400 mt-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Secondary Settings & Thumbnails (1 Column) -->
            <div class="space-y-6 lg:sticky lg:top-20">
                <!-- Card 2: Version & Size & File -->
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-zinc-200">Files & Release</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Version</label>
                            <input 
                                type="text" 
                                name="version" 
                                placeholder="e.g. 1.0.4" 
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                            >
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">File Size</label>
                            <input 
                                type="text" 
                                id="file_size_input"
                                name="file_size" 
                                placeholder="e.g. 450 MB" 
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                            >
                        </div>
                    </div>

                    <!-- File Upload Dropzone -->
                    <div class="space-y-1">
                        <label class="block text-xs text-gray-400 mb-1">Upload Product File (Optional)</label>
                        <div class="relative border border-dashed border-gray-200 dark:border-zinc-800 rounded-xl p-4 bg-gray-50 dark:bg-zinc-900/50 flex flex-col items-center justify-center transition-all hover:border-[#f53003] group">
                            <input 
                                type="file" 
                                name="product_file" 
                                id="product_file"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            >
                            <svg class="w-7 h-7 text-gray-400 dark:text-zinc-500 mb-2 group-hover:text-[#f53003] transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5h10.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0017.25 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <span class="text-xs font-semibold text-gray-700 dark:text-zinc-300 text-center" id="file-upload-label">Drag & drop or click</span>
                            <span class="text-[10px] text-gray-450 dark:text-zinc-500 mt-1" id="file-size-preview">No file selected</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Thumbnail -->
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-zinc-200">Thumbnail / Cover</h2>

                    <div class="space-y-2">
                        <!-- Tab switcher -->
                        <div class="flex gap-2">
                            <button type="button" id="thumb-tab-upload"
                                onclick="switchTab('thumb','upload')"
                                class="text-[10px] px-3 py-1.5 rounded-full bg-[#f53003] text-white font-semibold">
                                ↑ Upload Image
                            </button>
                            <button type="button" id="thumb-tab-url"
                                onclick="switchTab('thumb','url')"
                                class="text-[10px] px-3 py-1.5 rounded-full bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-400 font-semibold">
                                🔗 Paste URL
                            </button>
                        </div>

                        <!-- Upload panel -->
                        <div id="thumb-panel-upload" class="overflow-hidden">
                            <div class="relative border border-dashed border-gray-200 dark:border-zinc-800 rounded-xl p-4 bg-gray-50 dark:bg-zinc-900/50 flex flex-col items-center justify-center hover:border-[#f53003] transition group">
                                <input type="file" name="thumbnail_file" id="thumbnail_file"
                                    accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div id="thumb-preview-wrap" class="hidden mb-2 w-full">
                                    <img id="thumb-preview-img" src="" alt="Preview" class="w-full max-h-40 object-cover rounded-lg">
                                </div>
                                <svg id="thumb-upload-icon" class="w-7 h-7 text-gray-400 dark:text-zinc-500 mb-1 group-hover:text-[#f53003] transition-colors" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 21h18M3.75 3h16.5A.75.75 0 0121 3.75v13.5A.75.75 0 0120.25 18H3.75A.75.75 0 013 17.25V3.75A.75.75 0 013.75 3z" />
                                </svg>
                                <span id="thumb-upload-label" class="text-xs font-semibold text-gray-600 dark:text-zinc-300">Click or drag image here</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">max 5 MB</span>
                            </div>
                        </div>

                        <!-- URL panel (hidden by default) -->
                        <div id="thumb-panel-url" class="hidden">
                            <input
                                type="url"
                                name="thumbnail"
                                id="thumbnail_url_input"
                                placeholder="https://images.unsplash.com/photo-..."
                                class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-900 text-xs focus:outline-none focus:border-[#f53003]"
                            >
                        </div>
                    </div>
                </div>

                <!-- Card 4: Settings & Submit -->
                <div class="bg-white dark:bg-[#161615] border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 custom-shadow space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 dark:text-zinc-200">Settings</h2>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_free" id="is_free" class="w-3.5 h-3.5 text-[#f53003] border-gray-300 rounded focus:ring-0">
                            <label for="is_free" class="text-xs text-gray-650 dark:text-zinc-400">Make this product Free</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_featured" id="is_featured" class="w-3.5 h-3.5 text-[#f53003] border-gray-300 rounded focus:ring-0">
                            <label for="is_featured" class="text-xs text-gray-650 dark:text-zinc-400">Feature on homepage</label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full text-center text-xs py-3 bg-[#f53003] hover:bg-red-700 text-white rounded-lg font-bold transition-all cursor-pointer">
                            Save Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>      </form>
    </div>
@endsection

@section('scripts')
<script>
    // ─── Tab Switcher (Upload ↔ URL) ──────────────────────────────────────
    function switchTab(group, tab) {
        const uploadPanel = document.getElementById(group + '-panel-upload');
        const urlPanel    = document.getElementById(group + '-panel-url');
        const uploadBtn   = document.getElementById(group + '-tab-upload');
        const urlBtn      = document.getElementById(group + '-tab-url');

        const activeCls   = ['bg-[#f53003]', 'text-white'];
        const inactiveCls = ['bg-gray-100', 'dark:bg-zinc-800', 'text-gray-600', 'dark:text-zinc-400'];

        if (tab === 'upload') {
            uploadPanel.classList.remove('hidden');
            urlPanel.classList.add('hidden');
            uploadBtn.classList.add(...activeCls);
            uploadBtn.classList.remove(...inactiveCls);
            urlBtn.classList.remove(...activeCls);
            urlBtn.classList.add(...inactiveCls);
        } else {
            urlPanel.classList.remove('hidden');
            uploadPanel.classList.add('hidden');
            urlBtn.classList.add(...activeCls);
            urlBtn.classList.remove(...inactiveCls);
            uploadBtn.classList.remove(...activeCls);
            uploadBtn.classList.add(...inactiveCls);
        }
    }

    // ─── Thumbnail image upload: instant preview ─────────────────────────
    const thumbnailFile = document.getElementById('thumbnail_file');
    if (thumbnailFile) {
        thumbnailFile.addEventListener('change', function () {
            const file = this.files && this.files[0];
            const previewWrap = document.getElementById('thumb-preview-wrap');
            const previewImg  = document.getElementById('thumb-preview-img');
            const icon        = document.getElementById('thumb-upload-icon');
            const label       = document.getElementById('thumb-upload-label');

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewWrap.classList.remove('hidden');
                    if (icon) icon.classList.add('hidden');
                    if (label) label.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                previewWrap.classList.add('hidden');
                if (icon) icon.classList.remove('hidden');
                if (label) label.textContent = 'Click or drag image here';
            }
        });
    }

    // ─── Trailer video upload: show file name & size ─────────────────────
    const trailerFile = document.getElementById('trailer_file');
    if (trailerFile) {
        trailerFile.addEventListener('change', function () {
            const file      = this.files && this.files[0];
            const label     = document.getElementById('trailer-upload-label');
            const sizeInfo  = document.getElementById('trailer-size-info');
            const icon      = document.getElementById('trailer-upload-icon');

            if (file) {
                if (label) label.textContent = file.name;
                if (sizeInfo) sizeInfo.textContent = '📦 ' + formatBytes(file.size);
                if (icon) icon.classList.add('hidden');
            } else {
                if (label) label.textContent = 'Click or drag video here';
                if (sizeInfo) sizeInfo.textContent = '';
                if (icon) icon.classList.remove('hidden');
            }
        });
    }

    // ─── Utility: Format bytes to human-readable size ─────────────────────
    function formatBytes(bytes, precision = 2) {
        if (bytes <= 0) return '0 B';
        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return parseFloat((bytes / Math.pow(1024, i)).toFixed(precision)) + ' ' + units[i];
    }

    // ─── Utility: Parse duration string to seconds ───────────────────────
    function parseDurationToSeconds(str) {
        if (!str || !str.trim()) return 0;
        str = str.trim().toLowerCase();
        
        // H:MM:SS or MM:SS
        const colonParts = str.split(':');
        if (colonParts.length === 3) {
            return parseInt(colonParts[0]) * 3600 + parseInt(colonParts[1]) * 60 + parseInt(colonParts[2]);
        }
        if (colonParts.length === 2) {
            return parseInt(colonParts[0]) * 60 + parseInt(colonParts[1]);
        }

        let secs = 0;
        const hMatch = str.match(/(\d+)\s*h/);
        const mMatch = str.match(/(\d+)\s*m/);
        const sMatch = str.match(/(\d+)\s*s/);
        if (hMatch) secs += parseInt(hMatch[1]) * 3600;
        if (mMatch) secs += parseInt(mMatch[1]) * 60;
        if (sMatch) secs += parseInt(sMatch[1]);
        
        if (secs === 0 && /^\d+$/.test(str)) return parseInt(str);
        return secs;
    }

    // ─── Global refs ──────────────────────────────────────────────────────
    const selector       = document.getElementById('category_selector');
    const specsSection   = document.getElementById('dynamic-specs-section');
    const musicSection   = document.getElementById('dynamic-music-section');
    const videoSection   = document.getElementById('dynamic-video-section');
    const licenseChk     = document.getElementById('has_license_keys');
    const licenseBox     = document.getElementById('license-keys-container');
    const fileSizeInput  = document.getElementById('file_size_input');
    const fileInput      = document.getElementById('product_file');
    const fileLabel      = document.getElementById('file-upload-label');
    const sizePreview    = document.getElementById('file-size-preview');
    const musicBadge     = document.getElementById('music-calc-badge');
    const videoBadge     = document.getElementById('video-calc-badge');

    // ─── Category switcher ────────────────────────────────────────────────
    if (selector) {
        selector.addEventListener('change', function() {
            const slug = this.options[this.selectedIndex].getAttribute('data-slug');
            specsSection.classList.add('hidden');
            musicSection.classList.add('hidden');
            videoSection.classList.add('hidden');

            if (slug === 'games' || slug === 'applications') {
                specsSection.classList.remove('hidden');
            } else if (slug === 'music') {
                musicSection.classList.remove('hidden');
            } else if (slug === 'videos') {
                videoSection.classList.remove('hidden');
            }
        });
    }

    // ─── License keys toggle ──────────────────────────────────────────────
    if (licenseChk) {
        licenseChk.addEventListener('change', function() {
            licenseBox.classList.toggle('hidden', !this.checked);
        });
    }

    // ─── File Upload → auto-detect size ──────────────────────────────────
    let realFileUploaded = false;
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const formattedSize = formatBytes(file.size);
                realFileUploaded = true;

                fileSizeInput.value = formattedSize;
                fileLabel.textContent = file.name;
                sizePreview.textContent = `✅ Real file detected: ${formattedSize}`;
                sizePreview.className = 'text-[10px] text-emerald-600 dark:text-emerald-400 mt-1 font-semibold';
            } else {
                realFileUploaded = false;
                fileLabel.textContent = 'Drag & drop or click to upload file';
                sizePreview.textContent = 'No file selected';
                sizePreview.className = 'text-[10px] text-zinc-500 mt-1';
            }
        });
    }

    // ─── Music smart size calculation ─────────────────────────────────────
    function calcMusicSize() {
        if (realFileUploaded) return;
        const durEl     = document.getElementById('music_duration');
        const bitEl     = document.getElementById('music_bitrate');
        if (!durEl || !bitEl) return;

        const seconds = parseDurationToSeconds(durEl.value);
        const bitrate = parseInt(bitEl.value) || 320;
        if (seconds <= 0) {
            if (musicBadge) { musicBadge.classList.add('hidden'); musicBadge.textContent = ''; }
            return;
        }

        // bytes = seconds × (bitrate_kbps × 1000 / 8)
        const bytes = seconds * (bitrate * 1000 / 8);
        const formatted = formatBytes(bytes);

        if (fileSizeInput) fileSizeInput.value = formatted;
        if (musicBadge) {
            musicBadge.textContent = `🎵 Est. Size: ${formatted}`;
            musicBadge.classList.remove('hidden');
        }
        if (sizePreview) {
            sizePreview.textContent = `🎵 Calculated from duration × ${bitrate} kbps = ${formatted}`;
            sizePreview.className = 'text-[10px] text-emerald-600 dark:text-emerald-400 mt-1 font-semibold';
        }
    }

    const musicDurEl  = document.getElementById('music_duration');
    const musicBitEl  = document.getElementById('music_bitrate');
    if (musicDurEl)  musicDurEl.addEventListener('input', calcMusicSize);
    if (musicBitEl)  musicBitEl.addEventListener('change', calcMusicSize);

    // ─── Video smart size calculation ─────────────────────────────────────
    function calcVideoSize() {
        if (realFileUploaded) return;
        const durEl = document.getElementById('video_duration');
        const resEl = document.getElementById('video_resolution');
        const bitEl = document.getElementById('video_bitrate');
        if (!durEl || !resEl) return;

        const seconds = parseDurationToSeconds(durEl.value);
        if (seconds <= 0) {
            if (videoBadge) { videoBadge.classList.add('hidden'); videoBadge.textContent = ''; }
            return;
        }

        let bitrateMbps;
        const bitrateOverride = bitEl ? bitEl.value : 'auto';
        if (bitrateOverride !== 'auto') {
            bitrateMbps = parseFloat(bitrateOverride);
        } else {
            const res = resEl.value;
            if (res === '4K')    bitrateMbps = 40;
            else if (res === '1440p') bitrateMbps = 16;
            else if (res === '1080p') bitrateMbps = 8;
            else if (res === '720p')  bitrateMbps = 4;
            else bitrateMbps = 2; // 480p
        }

        // bytes = seconds × (bitrate_Mbps × 1,000,000 / 8)
        const bytes = seconds * (bitrateMbps * 1000000 / 8);
        const formatted = formatBytes(bytes);

        if (fileSizeInput) fileSizeInput.value = formatted;
        if (videoBadge) {
            videoBadge.textContent = `🎬 Est. Size: ${formatted}`;
            videoBadge.classList.remove('hidden');
        }
        if (sizePreview) {
            sizePreview.textContent = `🎬 Calculated: ${resEl.value} × ${parseDurationToSeconds(durEl.value)}s @ ${bitrateMbps} Mbps = ${formatted}`;
            sizePreview.className = 'text-[10px] text-blue-600 dark:text-blue-400 mt-1 font-semibold';
        }
    }

    const videoDurEl = document.getElementById('video_duration');
    const videoResEl = document.getElementById('video_resolution');
    const videoBitEl = document.getElementById('video_bitrate');
    if (videoDurEl) videoDurEl.addEventListener('input', calcVideoSize);
    if (videoResEl) videoResEl.addEventListener('change', calcVideoSize);
    if (videoBitEl) videoBitEl.addEventListener('change', calcVideoSize);
</script>
@endsection

