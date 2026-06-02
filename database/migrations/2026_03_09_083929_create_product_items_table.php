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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();

            // Core Identification
            $table->string('sku')->unique();               // Stock Keeping Unit
            $table->string('name');                          // Product name
            $table->string('slug')->unique();               // URL-friendly name
            $table->text('description')->nullable();         // Full description
            $table->text('short_description')->nullable();   // Brief description

            // Pricing`qq
            $table->decimal('cost_price', 15, 2)->nullable();   // What you paid
            $table->decimal('selling_price', 15, 2);            // Regular price
            $table->decimal('compare_price', 15, 2)->nullable(); // "Was" price for sales
            $table->decimal('wholesale_price', 15, 2)->nullable(); // For B2B customers

            // Stock & Inventory
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(5);     // Low stock threshold
            $table->integer('maximum_stock')->nullable();      // Overstock threshold
            $table->integer('reorder_point')->nullable();      // When to reorder
            $table->integer('safety_stock')->default(0);       // Buffer stock

            // Relationships
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable(); // If you have brands table
            $table->foreignId('primary_supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('primary_warehouse_id')->nullable()->constrained('warehouses');

            // Physical Attributes
            $table->string('unit')->default('piece');         // piece, kg, meter, box
            $table->decimal('weight', 10, 2)->nullable();     // in kg
            $table->decimal('length', 10, 2)->nullable();     // in cm
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();

            // Status Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_tracked')->default(true);      // Track inventory or not
            $table->boolean('allow_backorder')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_on_sale')->default(false);

            // Tax & Compliance
            $table->string('tax_class')->nullable();           // e.g., 'standard', 'reduced'
            $table->string('hs_code')->nullable();             // Harmonized System code for customs
            $table->string('country_of_origin')->nullable();

            // Barcode (primary) – can be moved to barcodes table if multiple barcodes needed
            $table->string('barcode')->nullable()->unique();   // Main barcode (EAN/UPC)

            // SEO & Meta
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // Media
            $table->string('featured_image')->nullable();      // Main image path
            $table->json('gallery_images')->nullable();        // Array of image paths

            // Settings
            $table->integer('views_count')->default(0);
            $table->integer('sales_count')->default(0);
            $table->json('attributes')->nullable();             // JSON for custom attributes (color, size, etc.)
            $table->json('variations')->nullable();             // If product has variants (size, color)

            // Tracking
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();  // Allows recycling bin

            // Indexes for performance
            $table->index('sku');
            $table->index('name');
            $table->index('slug');
            $table->index('barcode');
            $table->index('is_active');
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('current_stock');
            $table->index('selling_price');

            // Composite indexes for common queries
            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'current_stock']);
            $table->index(['minimum_stock', 'current_stock']);  // For low stock queries
            $table->index(['created_at', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
