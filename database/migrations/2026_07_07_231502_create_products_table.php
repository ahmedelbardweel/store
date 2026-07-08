<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('thumbnail')->nullable();
            $table->json('gallery')->nullable(); // array of image paths
            $table->string('file_path')->nullable(); // path to downloadable file
            $table->string('file_size')->nullable(); // e.g. "2.3 GB"
            $table->string('version')->nullable();
            $table->string('trailer_url')->nullable(); // YouTube URL for games/videos
            $table->string('preview_url')->nullable(); // 30s audio preview for songs
            $table->json('metadata')->nullable(); // category-specific: specs, duration, etc.
            $table->integer('download_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->boolean('has_license_keys')->default(false);
            $table->timestamps();

            $table->index(['category_id', 'is_free']);
            $table->index('is_featured');
            $table->index('download_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
