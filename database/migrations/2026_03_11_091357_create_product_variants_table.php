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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            // Link to the parent product
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('cascade'); // If product deleted, delete its variants

            // Variant identification
            $table->string('sku')->unique(); // Unique SKU for this variant
            $table->string('name'); // e.g., "Large Red T-Shirt"

            // Attributes stored as JSON (e.g., {"size": "L", "color": "Red"})
            $table->json('attributes')->nullable();

            // Pricing – can be an adjustment from the base price or a separate price
            $table->decimal('price_adjustment', 15, 2)->default(0); // positive or negative
            $table->decimal('price', 15, 2)->nullable(); // if you want to override base price entirely

            // Stock (if tracked separately from the main product)
            $table->integer('stock')->default(0);

            // Media
            $table->string('image')->nullable(); // variant-specific image

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('product_item_id');
            $table->index('sku');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
