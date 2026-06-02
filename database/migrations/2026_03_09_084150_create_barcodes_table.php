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
        Schema::create('barcodes', function (Blueprint $table) {
            $table->id();

            // The actual barcode string (EAN-13, UPC, Code128, etc.)
            $table->string('barcode')->unique();

            // Link to the product this barcode represents
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('cascade'); // If product deleted, remove its barcodes

            // Barcode type/standard
            $table->enum('type', [
                'ean13',
                'ean8',
                'upc',
                'code128',
                'code39',
                'qr',
                'datamatrix',
                'custom'
            ])->default('code128');

            // Human-readable format (optional, for display)
            $table->string('format')->nullable();

            // Flag for primary barcode (each product may have one primary)
            $table->boolean('is_primary')->default(false);

            // Status
            $table->boolean('is_active')->default(true);

            // Additional info
            $table->text('notes')->nullable();

            // Who created/updated it
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Optional, in case you want to keep history

            // Critical indexes for fast scanning
            $table->index('barcode');                          // Most important: lookup by barcode
            $table->index('product_item_id');                  // Find all barcodes for a product
            $table->index(['product_item_id', 'is_primary']); // Quickly get primary barcode
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcodes');
    }
};
