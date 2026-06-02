<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            // Foreign key to product
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('cascade'); // If product deleted, delete its images

            // Image details
            $table->string('image_path');               // Full S3 path or URL
            $table->string('file_name')->nullable();    // Original file name
            $table->string('mime_type')->nullable();    // image/jpeg, image/png, etc.
            $table->integer('file_size')->nullable();   // In bytes
            $table->integer('sort_order')->default(0);  // Display order

            // Image variations (optional – can store paths to thumbnails)
            $table->string('thumbnail_path')->nullable();
            $table->string('medium_path')->nullable();
            $table->string('large_path')->nullable();

            // Metadata
            $table->string('alt_text')->nullable();      // Alternative text for accessibility/SEO
            $table->string('title')->nullable();         // Image title attribute
            $table->string('caption')->nullable();       // Optional caption

            // Flags
            $table->boolean('is_primary')->default(false); // Main product image
            $table->boolean('is_active')->default(true);   // Hide/show image

            // Who uploaded it
            $table->foreignId('uploaded_by')->nullable()->constrained('users');

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Optional – in case you want to keep history

            // Indexes for performance
            $table->index('product_item_id');
            $table->index('is_primary');
            $table->index('sort_order');
            $table->index(['product_item_id', 'is_primary']); // Common query: get primary image
            $table->index(['product_item_id', 'sort_order']); // Get all images sorted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
